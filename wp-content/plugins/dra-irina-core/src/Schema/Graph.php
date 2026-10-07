<?php
/**
 * Emisor único de JSON-LD médico (ARCHITECTURE.md §5, D-015).
 * Rank Math conserva WebSite/WebPage/BreadcrumbList; aquí se emite Physician, ProfilePage, MedicalWebPage,
 * MedicalCondition y MedicalProcedure. Se desactivan en Rank Math los tipos que duplicaría.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Schema;

use DraIrina\Core\Settings\PracticeSettings;

final class Graph {

	public function register(): void {
		add_action( 'wp_head', [ $this, 'output' ], 5 );
		// Evita duplicados con Rank Math: sin Person/LocalBusiness por página.
		add_filter( 'rank_math/json_ld', [ $this, 'strip_rank_math_duplicates' ], 99 );
	}

	public static function physician_id(): string {
		return home_url( '/#physician' );
	}

	/** Nodo Physician desde la fuente única de verdad. Solo propiedades con dato. */
	public static function physician(): array {
		$p    = PracticeSettings::public_data();
		$node = [
			'@type'            => 'Physician',
			'@id'              => self::physician_id(),
			'name'             => $p['nombre_profesional'] ?? '',
			'url'              => home_url( '/' ),
			'medicalSpecialty' => 'Otolaryngologic',
		];
		$addr = array_filter(
			[
				'@type'           => 'PostalAddress',
				'streetAddress'   => trim( ( $p['calle'] ?? '' ) . ( ! empty( $p['interior'] ) ? ', ' . $p['interior'] : '' ) . ( ! empty( $p['colonia'] ) ? ', ' . $p['colonia'] : '' ) ),
				'postalCode'      => $p['cp'] ?? '',
				'addressLocality' => $p['ciudad'] ?? '',
				'addressRegion'   => $p['estado'] ?? '',
				'addressCountry'  => 'MX',
			]
		);
		if ( count( $addr ) > 2 ) {
			$node['address'] = $addr;
		}
		if ( ! empty( $p['geo_lat'] ) && ! empty( $p['geo_lng'] ) ) {
			$node['geo'] = [
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) $p['geo_lat'],
				'longitude' => (float) $p['geo_lng'],
			];
		}
		if ( ! empty( $p['telefono'] ) ) {
			$node['telephone'] = $p['telefono'];
		}
		if ( ! empty( $p['email'] ) ) {
			$node['email'] = $p['email'];
		}
		$same_as = array_values( array_filter( [ $p['instagram'] ?? '', $p['facebook'] ?? '', $p['linkedin'] ?? '', $p['tiktok'] ?? '', $p['doctoralia_url'] ?? '', $p['gbp_url'] ?? '' ] ) );
		if ( $same_as ) {
			$node['sameAs'] = $same_as;
		}
		$hours = self::opening_hours( (string) ( $p['horario'] ?? '' ) );
		if ( $hours ) {
			$node['openingHoursSpecification'] = $hours;
		}
		$image = self::physician_image();
		if ( $image ) {
			$node['image'] = $image;
		}
		if ( ! empty( $p['centro'] ) ) {
			$node['location'] = [
				'@type' => 'Place',
				'name'  => $p['centro'],
			];
		}
		return apply_filters( 'dra_irina_schema_physician', $node );
	}

	/** Convierte "Lunes|09:00-14:00,16:00-19:00" en OpeningHoursSpecification. */
	public static function opening_hours( string $raw ): array {
		$map = [
			'lunes'     => 'Monday',
			'martes'    => 'Tuesday',
			'miercoles' => 'Wednesday',
			'miércoles' => 'Wednesday',
			'jueves'    => 'Thursday',
			'viernes'   => 'Friday',
			'sabado'    => 'Saturday',
			'sábado'    => 'Saturday',
			'domingo'   => 'Sunday',
		];
		$out = [];
		foreach ( (array) preg_split( '/\r?\n/', $raw ) as $line ) {
			$parts = array_map( 'trim', explode( '|', $line ) );
			if ( count( $parts ) < 2 ) {
				continue;
			}
			$day = $map[ mb_strtolower( $parts[0] ) ] ?? null;
			if ( ! $day ) {
				continue;
			}
			foreach ( explode( ',', $parts[1] ) as $range ) {
				$hm = array_map( 'trim', explode( '-', $range ) );
				if ( 2 === count( $hm ) && preg_match( '/^\d{2}:\d{2}$/', $hm[0] ) && preg_match( '/^\d{2}:\d{2}$/', $hm[1] ) ) {
					$out[] = [
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => $day,
						'opens'     => $hm[0],
						'closes'    => $hm[1],
					];
				}
			}
		}
		return $out;
	}

	private static function physician_image(): string {
		$id = (int) apply_filters( 'dra_irina_physician_image_id', 0 );
		if ( $id ) {
			$src = wp_get_attachment_image_url( $id, 'large' );
			return is_string( $src ) ? $src : '';
		}
		return '';
	}

	/** Grafo por tipo de página. */
	public function graph(): array {
		$nodes = [];
		if ( is_front_page() ) {
			$nodes[] = self::physician();
		} elseif ( is_page() && 'dra-irina-gonzalez-saez' === get_post_field( 'post_name', get_queried_object_id() ) ) {
			$nodes[] = self::physician();
			$nodes[] = [
				'@type'      => 'ProfilePage',
				'mainEntity' => [ '@id' => self::physician_id() ],
				'url'        => get_permalink(),
			];
		} elseif ( is_singular( [ 'condicion', 'tratamiento', 'recurso' ] ) ) {
			$nodes[] = self::physician();
			$nodes[] = $this->medical_web_page( get_queried_object_id() );
		} elseif ( is_page( 'contacto' ) ) {
			$nodes[] = self::physician();
			$nodes[] = [
				'@type' => 'ContactPage',
				'url'   => get_permalink(),
				'about' => [ '@id' => self::physician_id() ],
			];
		}
		return apply_filters( 'dra_irina_schema_graph', $nodes );
	}

	private function medical_web_page( int $post_id ): array {
		$type = get_post_type( $post_id );
		$page = [
			'@type'        => 'MedicalWebPage',
			'url'          => get_permalink( $post_id ),
			'name'         => get_the_title( $post_id ),
			'inLanguage'   => 'es-MX',
			'reviewedBy'   => [ '@id' => self::physician_id() ],
			'dateModified' => get_the_modified_date( 'c', $post_id ),
		];
		$last = (string) get_post_meta( $post_id, 'di_fecha_revision_medica', true );
		if ( $last ) {
			$page['lastReviewed'] = $last;
		}
		$fuentes = get_post_meta( $post_id, 'di_fuentes', true );
		if ( is_array( $fuentes ) && $fuentes ) {
			$page['citation'] = array_values(
				array_filter(
					array_map(
						static fn( $f ) => is_array( $f ) && ! empty( $f['titulo'] ) ? array_filter(
							[
								'@type'         => 'CreativeWork',
								'name'          => $f['titulo'],
								'author'        => $f['autor'] ?? '',
								'url'           => $f['url'] ?? '',
								'datePublished' => $f['anio'] ?? '',
							]
						) : null,
						$fuentes
					)
				)
			);
		}
		if ( 'condicion' === $type ) {
			$about = [
				'@type' => 'MedicalCondition',
				'name'  => get_the_title( $post_id ),
			];
			$sint  = get_post_meta( $post_id, 'di_sintomas', true );
			if ( is_array( $sint ) && $sint ) {
				$about['signOrSymptom'] = array_map(
					static fn( $s ) => [
						'@type' => 'MedicalSignOrSymptom',
						'name'  => $s,
					],
					$sint
				);
			}
			$page['about'] = $about;
		} elseif ( 'tratamiento' === $type ) {
			$kind          = (string) get_post_meta( $post_id, 'di_tipo', true );
			$types         = [
				'estudio' => 'MedicalTest',
				'terapia' => 'MedicalTherapy',
			];
			$page['about'] = [
				'@type' => $types[ $kind ] ?? 'MedicalProcedure',
				'name'  => get_the_title( $post_id ),
			];
		} elseif ( 'recurso' === $type ) {
			$page['@type']         = [ 'MedicalWebPage', 'BlogPosting' ];
			$page['headline']      = get_the_title( $post_id );
			$page['datePublished'] = get_the_date( 'c', $post_id );
			$page['author']        = [ '@id' => self::physician_id() ];
		}
		return $page;
	}

	public function output(): void {
		$nodes = $this->graph();
		if ( ! $nodes ) {
			return;
		}
		echo '<script type="application/ld+json">' . wp_json_encode(
			[
				'@context' => 'https://schema.org',
				'@graph'   => $nodes,
			],
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . '</script>' . "\n";
	}

	/**
	 * Elimina de Rank Math los nodos que este plugin ya emite.
	 *
	 * @param array $data Grafo de Rank Math.
	 * @return array
	 */
	public function strip_rank_math_duplicates( array $data ): array {
		foreach ( $data as $key => $node ) {
			$type  = $node['@type'] ?? '';
			$types = is_array( $type ) ? $type : [ $type ];
			foreach ( $types as $t ) {
				if ( in_array( $t, [ 'Person', 'LocalBusiness', 'MedicalBusiness', 'Physician', 'MedicalClinic', 'Organization' ], true ) ) {
					unset( $data[ $key ] );
					break;
				}
			}
		}
		return $data;
	}
}
