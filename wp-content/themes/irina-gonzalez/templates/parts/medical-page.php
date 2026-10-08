<?php
/**
 * Página médica (condición o tratamiento): cabecera a sangre, artículo con TOC, revisión, FAQ, fuentes y CTA.
 * Lee exclusivamente los campos registrados por dra-irina-core.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_id      = get_the_ID();
$irina_type    = get_post_type( $irina_id );
$irina_sleep   = has_term( 'sueno', 'area', $irina_id );
$irina_meta    = static fn( string $key ) => get_post_meta( $irina_id, 'di_' . $key, true );
$irina_list    = static function ( $value ): array {
	return is_array( $value ) ? array_values( array_filter( array_map( 'strval', $value ) ) ) : [];
};
$irina_area    = get_the_terms( $irina_id, 'area' );
$irina_area    = is_array( $irina_area ) && $irina_area ? $irina_area[0] : null;
$irina_crumb   = $irina_sleep
	? [ [ __( 'Sueño', 'irina-gonzalez' ), home_url( '/sueno/' ) ] ]
	: [ [ __( 'Otorrinolaringología', 'irina-gonzalez' ), home_url( '/otorrinolaringologia/' ) ], [ 'condicion' === $irina_type ? __( 'Padecimientos', 'irina-gonzalez' ) : __( 'Tratamientos', 'irina-gonzalez' ), (string) get_post_type_archive_link( $irina_type ) ] ];
$irina_crumb[] = [ get_the_title( $irina_id ), '' ];

$irina_sections = [];
if ( 'condicion' === $irina_type ) {
	$irina_sections = [
		'que-es'           => [ __( 'Qué es', 'irina-gonzalez' ), 'content' ],
		'sintomas'         => [ __( 'Síntomas frecuentes', 'irina-gonzalez' ), 'list:sintomas' ],
		'causas'           => [ __( 'Causas', 'irina-gonzalez' ), 'list:causas' ],
		'cuando-consultar' => [ __( '¿Cuándo consultar?', 'irina-gonzalez' ), 'callout:cuando_consultar' ],
		'diagnostico'      => [ __( 'Cómo se diagnostica', 'irina-gonzalez' ), 'rich:diagnostico' ],
		'tratamiento'      => [ __( 'Tratamientos', 'irina-gonzalez' ), 'related:tratamientos_relacionados' ],
		'enfoque'          => [ __( 'El enfoque de la Dra.', 'irina-gonzalez' ), 'rich:enfoque_dra' ],
	];
} else {
	$irina_sections = [
		'que-es'         => [ __( 'En qué consiste', 'irina-gonzalez' ), 'content' ],
		'candidatos'     => [ __( 'Para quién está indicado', 'irina-gonzalez' ), 'list:candidatos' ],
		'estudio-previo' => [ __( 'Estudio previo', 'irina-gonzalez' ), 'rich:estudio_previo' ],
		'como'           => [ __( 'Cómo se realiza', 'irina-gonzalez' ), 'rich:como_se_realiza' ],
		'recuperacion'   => [ __( 'Recuperación', 'irina-gonzalez' ), 'rich:recuperacion' ],
		'riesgos'        => [ __( 'Riesgos y alternativas', 'irina-gonzalez' ), 'rich:riesgos_y_alternativas' ],
		'resuelve'       => [ __( 'Qué resuelve', 'irina-gonzalez' ), 'related:que_resuelve' ],
		'enfoque'        => [ __( 'El enfoque de la Dra.', 'irina-gonzalez' ), 'rich:enfoque_dra' ],
	];
}
$irina_faq     = is_array( $irina_meta( 'faq' ) ) ? $irina_meta( 'faq' ) : [];
$irina_fuentes = is_array( $irina_meta( 'fuentes' ) ) ? $irina_meta( 'fuentes' ) : [];
$irina_toc     = [];

irina_part(
	'bleed',
	[
		'title'    => get_the_title( $irina_id ),
		'eyebrow'  => $irina_area ? $irina_area->name : '',
		'lead'     => (string) $irina_meta( 'resumen_paciente' ),
		'variant'  => $irina_sleep ? 'night' : 'default',
		'breath'   => $irina_sleep,
		'crumbs'   => $irina_crumb,
		'image_id' => (int) get_post_thumbnail_id( $irina_id ),
		'cta'      => irina_whatsapp_button( $irina_sleep ? __( 'Agendar valoración de ronquido y apnea', 'irina-gonzalez' ) : '', sprintf( /* translators: %s: título de la página */ __( 'Hola, quisiera agendar una consulta sobre %s con la Dra. Irina González Sáez.', 'irina-gonzalez' ), get_the_title( $irina_id ) ), 'di-btn ' . ( $irina_sleep ? 'di-btn--light' : 'di-btn--whatsapp' ) ),
	]
);
?>
<div class="di-container di-article di-section">
	<article>
		<?php echo irina_medical_review_badge( $irina_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php
		foreach ( $irina_sections as $irina_anchor => [ $irina_label, $irina_kind ] ) {
			[ $irina_k, $irina_field ] = array_pad( explode( ':', $irina_kind, 2 ), 2, '' );
			$irina_html                = '';
			switch ( $irina_k ) {
				case 'content':
					$irina_html = apply_filters( 'the_content', get_the_content( null, false, $irina_id ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- filtro de core.
					break;
				case 'list':
					$irina_items = $irina_list( $irina_meta( $irina_field ) );
					$irina_html  = $irina_items ? '<ul><li>' . implode( '</li><li>', array_map( 'esc_html', $irina_items ) ) . '</li></ul>' : '';
					break;
				case 'callout':
					$irina_items = $irina_list( $irina_meta( $irina_field ) );
					$irina_html  = $irina_items ? '<div class="di-callout"><ul><li>' . implode( '</li><li>', array_map( 'esc_html', $irina_items ) ) . '</li></ul></div>' : '';
					break;
				case 'rich':
					$irina_html = wp_kses_post( wpautop( (string) $irina_meta( $irina_field ) ) );
					break;
				case 'related':
					$irina_ids = array_filter( array_map( 'intval', (array) $irina_meta( $irina_field ) ) );
					if ( $irina_ids ) {
						$irina_html = '<ol class="di-path">';
						$irina_n    = 0;
						foreach ( $irina_ids as $irina_rid ) {
							if ( 'publish' !== get_post_status( $irina_rid ) ) {
								continue;
							}
							++$irina_n;
							$irina_html .= sprintf( '<li><b>%02d</b><span><a href="%s">%s</a>%s</span></li>', $irina_n, esc_url( get_permalink( $irina_rid ) ), esc_html( get_the_title( $irina_rid ) ), '' !== (string) get_post_meta( $irina_rid, 'di_resumen_paciente', true ) ? '<br><span class="di-muted">' . esc_html( (string) get_post_meta( $irina_rid, 'di_resumen_paciente', true ) ) . '</span>' : '' );
						}
						$irina_html .= '</ol>';
					}
					break;
			}
			if ( '' === trim( wp_strip_all_tags( $irina_html ) ) ) {
				continue;
			}
			$irina_toc[ $irina_anchor ] = $irina_label;
			if ( 'callout' === $irina_k ) {
				printf( '<div class="di-callout di-reveal" id="%s"><h4>%s</h4>%s</div>', esc_attr( $irina_anchor ), esc_html( $irina_label ), $irina_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				printf( '<h2 id="%s" class="di-reveal">%s</h2><div class="di-reveal di-prose">%s</div>', esc_attr( $irina_anchor ), esc_html( $irina_label ), $irina_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			if ( 'diagnostico' === $irina_anchor || 'como' === $irina_anchor ) {
				printf( '<div class="di-inline-cta di-reveal"><div><b>%s</b><br><span class="di-muted">%s</span></div>%s</div>', esc_html__( '¿Tienes dudas sobre tu caso?', 'irina-gonzalez' ), esc_html__( 'Una valoración de 30 minutos define el siguiente paso.', 'irina-gonzalez' ), irina_whatsapp_button( '', '', 'di-btn ' . ( $irina_sleep ? 'di-btn--light' : 'di-btn--whatsapp' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		if ( $irina_faq ) {
			$irina_toc['faq'] = __( 'Preguntas frecuentes', 'irina-gonzalez' );
			echo '<h2 id="faq" class="di-reveal">' . esc_html__( 'Preguntas frecuentes', 'irina-gonzalez' ) . '</h2><div class="di-faq di-reveal">';
			foreach ( $irina_faq as $irina_q ) {
				if ( empty( $irina_q['pregunta'] ) ) {
					continue;
				}
				printf( '<details><summary>%s</summary><div class="di-muted">%s</div></details>', esc_html( (string) $irina_q['pregunta'] ), wp_kses_post( wpautop( (string) ( $irina_q['respuesta'] ?? '' ) ) ) );
			}
			echo '</div>';
		}
		if ( $irina_fuentes ) {
			echo '<div class="di-sources di-reveal"><h3 style="font-size:1.2rem">' . esc_html__( 'Fuentes', 'irina-gonzalez' ) . '</h3><ol>';
			foreach ( $irina_fuentes as $irina_f ) {
				if ( empty( $irina_f['titulo'] ) ) {
					continue;
				}
				$irina_t = esc_html( (string) $irina_f['titulo'] );
				$irina_s = ! empty( $irina_f['url'] ) ? sprintf( '<a href="%s" rel="noopener nofollow" target="_blank">%s</a>', esc_url( (string) $irina_f['url'] ), $irina_t ) : $irina_t;
				printf( '<li>%s%s%s</li>', $irina_s, ! empty( $irina_f['autor'] ) ? ' · ' . esc_html( (string) $irina_f['autor'] ) : '', ! empty( $irina_f['anio'] ) ? ' (' . esc_html( (string) $irina_f['anio'] ) . ')' : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '</ol></div>';
		}
		?>
	</article>
	<aside class="di-toc">
		<b><?php esc_html_e( 'En esta página', 'irina-gonzalez' ); ?></b>
		<ol>
		<?php
		foreach ( $irina_toc as $irina_anchor => $irina_label ) :
			?>
			<li><a href="#<?php echo esc_attr( $irina_anchor ); ?>"><?php echo esc_html( $irina_label ); ?></a></li><?php endforeach; ?></ol>
		<?php
		$irina_rel = array_filter( array_map( 'intval', (array) $irina_meta( 'relacionados' ) ) );
		if ( $irina_rel ) :
			?>
			<p style="margin-top:16px"><b><?php esc_html_e( 'Relacionado', 'irina-gonzalez' ); ?></b><br>
			<?php
			foreach ( $irina_rel as $irina_rid ) :
				?>
				<a href="<?php echo esc_url( get_permalink( $irina_rid ) ); ?>"><?php echo esc_html( get_the_title( $irina_rid ) ); ?></a><br><?php endforeach; ?></p>
		<?php endif; ?>
	</aside>
</div>
