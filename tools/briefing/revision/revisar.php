<?php
/**
 * Backend de la mini app de revisión médica (/briefing/revision/). D-040.
 * Carga WordPress y actúa como el usuario con rol revisor_medico: el gate del plugin y las capacidades son los reales.
 * La app nunca publica: lee fichas clínicas, guarda correcciones por campo y cambia el estado médico (aprobar, pedir cambios, no publicar).
 * Acceso solo con el token del briefing (config.php, fuera del repo). Respuestas sin caché y noindex.
 * Sin correos (instrucción del propietario 2026-10-09): el registro queda en data_dir/revision-log.jsonl y revision-latest.json.
 */

declare( strict_types=1 );

header( 'Content-Type: application/json; charset=utf-8' );
header( 'Cache-Control: no-store, private' );
header( 'X-LiteSpeed-Cache-Control: no-cache' );
header( 'X-Robots-Tag: noindex, nofollow' );

function di_out( array $data, int $code = 200 ): void {
	http_response_code( $code );
	echo json_encode( $data, JSON_UNESCAPED_UNICODE );
	exit;
}

$config_file = __DIR__ . '/../config.php';
if ( ! is_readable( $config_file ) ) {
	di_out( [ 'error' => 'config' ], 500 );
}
$config = require $config_file; // ['token' => ..., 'data_dir' => ..., 'reviewer_login' => (opcional)]

$method = (string) ( $_SERVER['REQUEST_METHOD'] ?? 'GET' );
$raw    = '';
$body   = [];
if ( 'POST' === $method ) {
	$raw = (string) file_get_contents( 'php://input', false, null, 0, 400001 );
	if ( strlen( $raw ) > 400000 ) {
		di_out( [ 'error' => 'size' ], 413 );
	}
	$body = json_decode( $raw, true );
	$body = is_array( $body ) ? $body : [];
}
$token = 'POST' === $method ? ( $body['token'] ?? '' ) : ( $_GET['token'] ?? '' );
if ( ! is_string( $token ) || '' === $token || ! hash_equals( (string) $config['token'], $token ) ) {
	di_out( [ 'error' => 'token' ], 403 );
}

// ---- WordPress (sin tema) ----
define( 'WP_USE_THEMES', false );
$wp_load = dirname( __DIR__, 2 ) . '/wp-load.php';
if ( ! is_readable( $wp_load ) ) {
	di_out( [ 'error' => 'wp-load' ], 500 );
}
require $wp_load;

if ( ! class_exists( '\DraIrina\Core\Workflow\MedicalReview' ) ) {
	di_out( [ 'error' => 'plugin' ], 500 );
}
$role_slug = \DraIrina\Core\Workflow\MedicalReview::ROLE;
$cap       = \DraIrina\Core\Workflow\MedicalReview::CAP;
$clinical  = \DraIrina\Core\Workflow\MedicalReview::CLINICAL;

$reviewer = null;
if ( ! empty( $config['reviewer_login'] ) ) {
	$u = get_user_by( 'login', (string) $config['reviewer_login'] );
	if ( $u && in_array( $role_slug, (array) $u->roles, true ) ) {
		$reviewer = $u;
	}
}
if ( ! $reviewer ) {
	$users    = get_users( [ 'role' => $role_slug, 'number' => 1, 'orderby' => 'ID', 'order' => 'ASC' ] );
	$reviewer = $users ? $users[0] : null;
}
if ( ! $reviewer ) {
	di_out( [ 'error' => 'reviewer', 'hint' => 'No existe un usuario con el rol revisor_medico.' ], 500 );
}
wp_set_current_user( $reviewer->ID );
if ( ! current_user_can( $cap ) ) {
	di_out( [ 'error' => 'cap' ], 500 );
}

$data_dir = rtrim( (string) ( $config['data_dir'] ?? '' ), '/' );
if ( '' !== $data_dir && ! is_dir( $data_dir ) ) {
	mkdir( $data_dir, 0700, true );
}

// ---- Modelo de campos editables (misma fuente que MetaRegistry) ----
$fields = [
	'condicion'   => [
		'resumen_paciente' => 'text',
		'sintomas'         => 'list',
		'causas'           => 'list',
		'cuando_consultar' => 'list',
		'diagnostico'      => 'rich',
		'enfoque_dra'      => 'rich',
		'faq'              => 'faq',
		'fuentes'          => 'fuentes',
	],
	'tratamiento' => [
		'resumen_paciente'       => 'text',
		'oferta'                 => 'oferta',
		'candidatos'             => 'list',
		'estudio_previo'         => 'rich',
		'como_se_realiza'        => 'rich',
		'recuperacion'           => 'rich',
		'riesgos_y_alternativas' => 'rich',
		'enfoque_dra'            => 'rich',
		'faq'                    => 'faq',
		'fuentes'                => 'fuentes',
	],
	'recurso'     => [
		'faq'     => 'faq',
		'fuentes' => 'fuentes',
	],
];
$order  = [ 'apnea-obstructiva-del-sueno', 'ronquido', 'estudio-del-sueno', 'cpap', 'cirugia-de-ronquido-y-apnea', 'sinusitis', 'rinitis', 'desviacion-de-tabique-nasal', 'septoplastia' ];
$states = [
	'draft'                   => 'Borrador',
	'technical_review'        => 'Con cambios pedidos',
	'medical_review_required' => 'Pendiente de tu revisión',
	'medically_approved'      => 'Aprobada por ti',
	'ready_to_publish'        => 'Lista para publicar',
];

function di_state( int $id ): string {
	return \DraIrina\Core\Workflow\MedicalReview::state( $id );
}
function di_list( $v ): array {
	return is_array( $v ) ? array_values( array_filter( array_map( 'strval', $v ), static fn( $s ) => '' !== trim( $s ) ) ) : [];
}
function di_row( \WP_Post $p, array $states, array $order ): array {
	$area  = get_the_terms( $p->ID, 'area' );
	$area  = is_array( $area ) && $area ? $area[0]->name : '';
	$state = di_state( $p->ID );
	$pos   = array_search( $p->post_name, $order, true );
	return [
		'id'          => $p->ID,
		'type'        => $p->post_type,
		'slug'        => $p->post_name,
		'title'       => $p->post_title,
		'area'        => $area,
		'state'       => $state,
		'state_label' => $states[ $state ] ?? $state,
		'published'   => 'publish' === $p->post_status,
		'approved_at' => (string) get_post_meta( $p->ID, '_di_approved_at', true ),
		'note'        => (string) get_post_meta( $p->ID, '_di_review_note', true ),
		'modified'    => $p->post_modified_gmt,
		'order'       => false === $pos ? 100 : $pos,
	];
}
function di_log( string $data_dir, array $entry ): void {
	if ( '' === $data_dir ) {
		return;
	}
	$entry['at'] = gmdate( 'c' );
	file_put_contents( $data_dir . '/revision-log.jsonl', json_encode( $entry, JSON_UNESCAPED_UNICODE ) . "\n", FILE_APPEND | LOCK_EX );
}
function di_snapshot( string $data_dir, array $clinical, array $states, array $order ): void {
	if ( '' === $data_dir ) {
		return;
	}
	$posts = get_posts( [ 'post_type' => $clinical, 'post_status' => [ 'draft', 'pending', 'private', 'publish', 'future' ], 'posts_per_page' => 200 ] );
	$rows  = array_map( static fn( $p ) => di_row( $p, $states, $order ), $posts );
	$sum   = [ 'updatedAt' => gmdate( 'c' ), 'approved' => 0, 'changes' => 0, 'pending' => 0, 'items' => [] ];
	foreach ( $rows as $r ) {
		unset( $r['order'] );
		$sum['items'][] = $r;
		if ( 'medically_approved' === $r['state'] || 'ready_to_publish' === $r['state'] ) { ++$sum['approved']; } elseif ( 'technical_review' === $r['state'] || 'draft' === $r['state'] ) { ++$sum['changes']; } else { ++$sum['pending']; }
	}
	file_put_contents( $data_dir . '/revision-latest.json', json_encode( $sum, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ), LOCK_EX );
}
function di_load_post( int $id, array $clinical ): \WP_Post {
	$p = $id ? get_post( $id ) : null;
	if ( ! $p || ! in_array( $p->post_type, $clinical, true ) || 'trash' === $p->post_status ) {
		di_out( [ 'error' => 'not_found' ], 404 );
	}
	if ( ! current_user_can( 'edit_post', $p->ID ) ) {
		di_out( [ 'error' => 'forbidden' ], 403 );
	}
	return $p;
}

$action = 'POST' === $method ? (string) ( $body['action'] ?? '' ) : (string) ( $_GET['action'] ?? 'list' );

// ---- GET list ----
if ( 'GET' === $method && 'list' === $action ) {
	$posts = get_posts(
		[
			'post_type'      => $clinical,
			'post_status'    => [ 'draft', 'pending', 'private', 'publish', 'future' ],
			'posts_per_page' => 200,
			'orderby'        => 'title',
			'order'          => 'ASC',
		]
	);
	$rows  = array_map( static fn( $p ) => di_row( $p, $states, $order ), $posts );
	usort( $rows, static fn( $a, $b ) => [ $a['order'], $a['type'], $a['title'] ] <=> [ $b['order'], $b['type'], $b['title'] ] );
	di_out(
		[
			'reviewer' => $reviewer->display_name,
			'items'    => $rows,
			'states'   => $states,
		]
	);
}

// ---- GET get ----
if ( 'GET' === $method && 'get' === $action ) {
	$p    = di_load_post( (int) ( $_GET['id'] ?? 0 ), $clinical );
	$row  = di_row( $p, $states, $order );
	$meta = [];
	foreach ( $fields[ $p->post_type ] as $key => $kind ) {
		$v = get_post_meta( $p->ID, 'di_' . $key, true );
		switch ( $kind ) {
			case 'list':
				$meta[ $key ] = di_list( $v );
				break;
			case 'faq':
				$meta[ $key ] = is_array( $v ) ? array_values( array_map( static fn( $q ) => [ 'pregunta' => (string) ( $q['pregunta'] ?? '' ), 'respuesta' => (string) ( $q['respuesta'] ?? '' ) ], array_filter( $v, 'is_array' ) ) ) : [];
				break;
			case 'fuentes':
				$meta[ $key ] = is_array( $v ) ? array_values( array_map( static fn( $f ) => [ 'titulo' => (string) ( $f['titulo'] ?? '' ), 'autor' => (string) ( $f['autor'] ?? '' ), 'anio' => (string) ( $f['anio'] ?? '' ), 'url' => (string) ( $f['url'] ?? '' ), 'tipo' => (string) ( $f['tipo'] ?? 'guia' ) ], array_filter( $v, 'is_array' ) ) ) : [];
				break;
			default:
				$meta[ $key ] = (string) $v;
		}
	}
	$related = [];
	foreach ( [ 'tratamientos_relacionados', 'que_resuelve', 'relacionados' ] as $rk ) {
		foreach ( array_filter( array_map( 'intval', (array) get_post_meta( $p->ID, 'di_' . $rk, true ) ) ) as $rid ) {
			$related[] = get_the_title( $rid );
		}
	}
	$row['content'] = (string) $p->post_content;
	$row['excerpt'] = (string) $p->post_excerpt;
	$row['meta']    = $meta;
	$row['kinds']   = $fields[ $p->post_type ];
	$row['related'] = array_values( array_unique( $related ) );
	di_out( $row );
}

if ( 'POST' !== $method ) {
	di_out( [ 'error' => 'method' ], 405 );
}

// ---- POST save: correcciones por campo ----
if ( 'save' === $action ) {
	$p = di_load_post( (int) ( $body['id'] ?? 0 ), $clinical );
	if ( 'publish' === $p->post_status ) {
		di_out( [ 'error' => 'published', 'hint' => 'La ficha ya está publicada: pide el cambio con «Pedir cambios» y lo aplicamos nosotros.' ], 409 );
	}
	$in      = is_array( $body['fields'] ?? null ) ? $body['fields'] : [];
	$changed = [];
	$postarr = [ 'ID' => $p->ID ];
	if ( array_key_exists( 'content', $in ) ) {
		$postarr['post_content'] = wp_slash( wp_kses_post( (string) $in['content'] ) );
		$changed[]               = 'content';
	}
	if ( array_key_exists( 'excerpt', $in ) ) {
		$postarr['post_excerpt'] = wp_slash( sanitize_textarea_field( (string) $in['excerpt'] ) );
		$changed[]               = 'excerpt';
	}
	if ( array_key_exists( 'title', $in ) ) {
		$t = sanitize_text_field( (string) $in['title'] );
		if ( '' !== $t ) {
			$postarr['post_title'] = wp_slash( $t );
			$changed[]             = 'title';
		}
	}
	if ( count( $postarr ) > 1 ) {
		$r = wp_update_post( $postarr, true );
		if ( is_wp_error( $r ) ) {
			di_out( [ 'error' => 'update', 'hint' => $r->get_error_message() ], 500 );
		}
	}
	foreach ( $fields[ $p->post_type ] as $key => $kind ) {
		if ( ! array_key_exists( $key, $in ) ) {
			continue;
		}
		$v = $in[ $key ];
		switch ( $kind ) {
			case 'text':
				$clean = sanitize_textarea_field( (string) $v );
				break;
			case 'rich':
				$clean = wp_kses_post( (string) $v );
				break;
			case 'oferta':
				$clean = in_array( $v, [ 'ofrece', 'refiere', 'no' ], true ) ? $v : 'ofrece';
				break;
			case 'list':
				$clean = array_values( array_filter( array_map( static fn( $s ) => sanitize_text_field( (string) $s ), (array) $v ), static fn( $s ) => '' !== $s ) );
				break;
			case 'faq':
				$clean = [];
				foreach ( (array) $v as $q ) {
					if ( ! is_array( $q ) ) {
						continue;
					}
					$pq = sanitize_text_field( (string) ( $q['pregunta'] ?? '' ) );
					$pr = wp_kses_post( (string) ( $q['respuesta'] ?? '' ) );
					if ( '' !== $pq || '' !== $pr ) {
						$clean[] = [ 'pregunta' => $pq, 'respuesta' => $pr ];
					}
				}
				break;
			case 'fuentes':
				$clean = [];
				foreach ( (array) $v as $f ) {
					if ( ! is_array( $f ) ) {
						continue;
					}
					$t = sanitize_text_field( (string) ( $f['titulo'] ?? '' ) );
					if ( '' === $t ) {
						continue;
					}
					$tipo    = (string) ( $f['tipo'] ?? 'guia' );
					$clean[] = [
						'titulo' => $t,
						'autor'  => sanitize_text_field( (string) ( $f['autor'] ?? '' ) ),
						'anio'   => sanitize_text_field( (string) ( $f['anio'] ?? '' ) ),
						'url'    => esc_url_raw( (string) ( $f['url'] ?? '' ) ),
						'tipo'   => in_array( $tipo, [ 'guia', 'sociedad', 'articulo', 'organismo', 'libro', 'otro' ], true ) ? $tipo : 'otro',
					];
				}
				break;
			default:
				continue 2;
		}
		update_post_meta( $p->ID, 'di_' . $key, wp_slash( $clean ) );
		$changed[] = $key;
	}
	if ( $changed ) {
		update_post_meta( $p->ID, '_di_reviewed_edit_at', current_time( 'mysql' ) );
		di_snapshot( $data_dir, $clinical, $states, $order );
		di_log( $data_dir, [ 'event' => 'save', 'id' => $p->ID, 'slug' => $p->post_name, 'fields' => $changed, 'by' => $reviewer->user_login ] );
	}
	clean_post_cache( $p->ID );
	$p = get_post( $p->ID );
	di_out( [ 'ok' => true, 'changed' => $changed, 'modified' => $p->post_modified_gmt, 'state' => di_state( $p->ID ) ] );
}

// ---- POST state: aprobar / pedir cambios / no publicar ----
if ( 'state' === $action ) {
	$p    = di_load_post( (int) ( $body['id'] ?? 0 ), $clinical );
	$to   = (string) ( $body['state'] ?? '' );
	$note = sanitize_textarea_field( (string) ( $body['note'] ?? '' ) );
	$map  = [
		'approve' => 'medically_approved',
		'changes' => 'technical_review',
		'hold'    => 'draft',
	];
	if ( ! isset( $map[ $to ] ) ) {
		di_out( [ 'error' => 'state' ], 400 );
	}
	$new = $map[ $to ];
	if ( 'medically_approved' === $new && ! current_user_can( $cap ) ) {
		di_out( [ 'error' => 'cap' ], 403 );
	}
	if ( 'approve' !== $to && '' === $note ) {
		di_out( [ 'error' => 'note', 'hint' => 'Escribe qué quieres cambiar.' ], 400 );
	}
	if ( 'publish' === $p->post_status && 'changes' !== $to ) {
		di_out( [ 'error' => 'published', 'hint' => 'La ficha ya está publicada; solo puedes pedir cambios.' ], 409 );
	}
	$old = di_state( $p->ID );
	if ( 'publish' !== $p->post_status ) {
		wp_set_object_terms( $p->ID, $new, 'estado_medico', false );
	}
	if ( 'medically_approved' === $new ) {
		update_post_meta( $p->ID, '_di_approved_by', get_current_user_id() );
		update_post_meta( $p->ID, '_di_approved_at', current_time( 'mysql' ) );
		delete_post_meta( $p->ID, '_di_review_note' );
	} else {
		update_post_meta( $p->ID, '_di_review_note', wp_slash( $note ) );
		delete_post_meta( $p->ID, '_di_approved_by' );
		delete_post_meta( $p->ID, '_di_approved_at' );
	}
	di_log( $data_dir, [ 'event' => $to, 'id' => $p->ID, 'slug' => $p->post_name, 'from' => $old, 'to' => $new, 'note' => $note, 'by' => $reviewer->user_login ] );
	di_snapshot( $data_dir, $clinical, $states, $order );
	clean_post_cache( $p->ID );
	di_out( [ 'ok' => true, 'state' => di_state( $p->ID ), 'state_label' => $states[ di_state( $p->ID ) ] ?? '', 'approved_at' => (string) get_post_meta( $p->ID, '_di_approved_at', true ), 'note' => (string) get_post_meta( $p->ID, '_di_review_note', true ) ] );
}

di_out( [ 'error' => 'action' ], 400 );
