<?php
// SOLO LECTURA: predice qué páginas EN perderían la aprobación de la Dra. si se ejecutara setup-en.php (misma comparación
// exacta de _elementor_data / post_content con los marcadores de imagen rellenados igual). No escribe nada.
// Uso (sesión local): subir tools/content/en/*.json, *.html y este archivo a un temporal 700 del servidor y ejecutar
//   wp --skip-plugins=elementor,elementor-pro eval-file <tmp>/simula-setup-en.php ; luego borrar el temporal.
// Mantener $media sincronizado con tools/deploy/en/setup-en.php.
( static function (): void {
	$dir   = __DIR__;
	$pages = json_decode( (string) file_get_contents( "$dir/pages.json" ), true );
	$media = [ 'HERO' => 'hero-home', 'ORL_IMG' => 'area-orl', 'SUENO_IMG' => 'area-sueno', 'DRA_IMG' => 'dra-escucharte', 'DRA_BG' => 'dra-escucharte-bg', 'DRA_HERO' => 'hero-dra', 'DRA_ENDO' => 'dra-endoscopio', 'PC_HERO' => 'hero-primera', 'PC_BG' => 'pc-fondo', 'CO_HERO' => 'hero-contacto', 'SU_HERO' => 'hero-sueno', 'ORL_HERO' => 'hero-orl' ];
	$fill  = static function ( string $json ) use ( $media ): string {
		foreach ( $media as $marker => $slug ) {
			$att  = get_posts( [ 'post_type' => 'attachment', 'post_name__in' => [ $slug ], 'post_status' => 'any', 'posts_per_page' => 1 ] );
			$id   = $att ? (int) $att[0]->ID : 0;
			$url  = $id ? (string) wp_get_attachment_url( $id ) : '';
			$json = str_replace( [ "__{$marker}_ID__", "__{$marker}_URL__" ], [ (string) ( $id ? $id : '' ), $url ], $json );
		}
		return $json;
	};
	uksort( $pages, static fn( $a, $b ) => ( 'en' === $a ? -1 : ( 'en' === $b ? 1 : strcmp( $a, $b ) ) ) );
	$root = 0;
	foreach ( $pages as $slug => $def ) {
		$q = get_posts( [ 'post_type' => 'page', 'post_name__in' => [ $slug ], 'post_parent' => 'en' === $slug ? 0 : $root, 'post_status' => 'any', 'posts_per_page' => 1 ] );
		if ( ! $q ) { echo "$slug: NO EXISTE\n"; continue; }
		$p = $q[0]; $id = (int) $p->ID; if ( 'en' === $slug ) { $root = $id; }
		$is_html = 'html' === ( $def['kind'] ?? 'elementor' );
		$new = $is_html ? (string) file_get_contents( "$dir/{$slug}.html" ) : $fill( (string) file_get_contents( "$dir/{$slug}.elementor.json" ) );
		$old = $is_html ? (string) $p->post_content : (string) get_post_meta( $id, '_elementor_data', true );
		$ap  = (string) get_post_meta( $id, '_di_page_approved_at', true );
		$diff = '';
		if ( $old !== $new ) { $i = 0; $n = min( strlen( $old ), strlen( $new ) ); while ( $i < $n && $old[ $i ] === $new[ $i ] ) { $i++; } $diff = ' | 1.ª diferencia: «' . substr( $old, max( 0, $i - 30 ), 70 ) . '» → «' . substr( $new, max( 0, $i - 30 ), 70 ) . '»'; }
		printf( "%d %-24s %-6s %s aprob:%s%s\n", $id, $slug, $p->post_status, $old === $new ? 'IGUAL ' : 'CAMBIA', $ap ?: '—', $old !== $new && $ap ? ' ⇒ PERDERÍA LA APROBACIÓN' . $diff : $diff );
	}
} )();
