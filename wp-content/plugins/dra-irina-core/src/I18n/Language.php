<?php
/**
 * Versión en inglés con módulo propio (D-095, OD-008 opción a; Anexo A del MASTER_PROMPT).
 *
 * - Páginas: las inglesas son hijas de la página raíz `en` (URL /en/… sin reglas nuevas).
 * - Fichas de sueño: entradas de condicion/tratamiento con `_di_lang = en`, en /en/sleep/{slug}/; pasan por la misma puerta médica.
 * - Pareja de traducción: la entrada inglesa guarda `_di_translation_of` = ID de la española (metas protegidas: no disparan la
 *   revisión médica de la ficha española).
 * - En /en/ el locale es en_US (textos del tema y del plugin desde languages/*-en_US.mo) y se imprime hreflang es-MX/en-US/x-default
 *   solo cuando las dos versiones están publicadas.
 * Nada en inglés se publica sin la aprobación de la Dra.: las páginas se crean en borrador y las fichas con el estado médico pendiente.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\I18n;

final class Language {

	public const ES       = 'es';
	public const EN       = 'en';
	public const ROOT     = 'en';
	public const META     = '_di_lang';
	public const PAIR     = '_di_translation_of';
	public const SLEEP_EN = 'en/sleep';

	/** @var array<string, string> Código de idioma → BCP 47 para hreflang, schema y html lang. */
	public const TAGS = [
		self::ES => 'es-MX',
		self::EN => 'en-US',
	];

	private static ?string $current = null;

	public function register(): void {
		add_filter( 'locale', [ $this, 'locale' ] );
		add_action( 'init', [ $this, 'rewrites' ] );
		add_filter( 'query_vars', [ $this, 'query_vars' ] );
		add_action( 'wp', [ $this, 'reset_current' ], 0 );
		add_action( 'wp_head', [ $this, 'hreflang' ], 2 );
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_filter( 'do_redirect_guess_404_permalink', [ $this, 'guess_404' ] );
	}

	/** Idioma de una entrada: inglés si es la raíz `en`, una página bajo ella o una entrada marcada con `_di_lang = en`. */
	public static function of( int $post_id ): string {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return self::ES;
		}
		if ( 'page' === $post->post_type ) {
			$root = self::root_id();
			if ( $root && ( $post->ID === $root || in_array( $root, array_map( 'intval', get_post_ancestors( $post ) ), true ) ) ) {
				return self::EN;
			}
			return self::ES;
		}
		return self::EN === get_post_meta( $post->ID, self::META, true ) ? self::EN : self::ES;
	}

	/** ID de la página raíz inglesa (/en/), en cualquier estado; 0 si no existe. */
	public static function root_id(): int {
		static $id = null;
		if ( null === $id ) {
			$page = get_page_by_path( self::ROOT, OBJECT, 'page' );
			$id   = $page && 0 === (int) $page->post_parent ? (int) $page->ID : 0;
		}
		return $id;
	}

	/** Idioma de la petición actual. Antes de resolver la consulta se deduce de la ruta (/en o /en/…). */
	public static function current(): string {
		if ( null !== self::$current ) {
			return self::$current;
		}
		if ( did_action( 'wp' ) && ! is_admin() ) {
			$id = (int) get_queried_object_id();
			if ( $id && ( is_singular() || is_page() ) ) {
				self::$current = self::of( $id );
				return self::$current;
			}
		}
		return self::EN === self::preview_lang() ? self::EN : self::from_path();
	}

	/** Idioma según la ruta pedida (sirve también para la 404 bajo /en/). */
	public static function from_path(): string {
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- solo se compara la ruta.
		$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
		$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		$rel  = '/' . ltrim( substr( $path, strlen( rtrim( $base, '/' ) ) ), '/' );
		return 1 === preg_match( '#^/' . self::ROOT . '(/|$)#', $rel ) ? self::EN : self::ES;
	}

	public function reset_current(): void {
		self::$current = null;
	}

	/** No adivinar slugs ingleses inexistentes con páginas españolas (p. ej. /en/contact/ → /contacto/). */
	public function guess_404( bool $guess ): bool {
		return self::EN === self::from_path() ? false : $guess;
	}

	/** Entrada equivalente en el otro idioma (cualquier estado); 0 si no hay. */
	public static function translation( int $post_id, string $lang ): int {
		if ( self::of( $post_id ) === $lang ) {
			return $post_id;
		}
		if ( self::ES === $lang ) {
			return (int) get_post_meta( $post_id, self::PAIR, true );
		}
		$found = get_posts(
			[
				'post_type'      => 'any',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => self::PAIR, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- pocas entradas, consulta puntual.
				'meta_value'     => (string) $post_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);
		return $found ? (int) $found[0] : 0;
	}

	/**
	 * Slug de la página española equivalente (el propio si la página es española), para que las reglas por página
	 * (registro nocturno de Sueño, schema de FAQ, área y contacto) valgan en los dos idiomas sin repetir slugs ingleses.
	 */
	public static function es_slug( int $post_id ): string {
		$es = self::translation( $post_id, self::ES );
		return $es ? (string) get_post_field( 'post_name', $es ) : '';
	}

	/**
	 * Como is_page( $slugs ), con los slugs españoles, pero también verdadero en su traducción inglesa (/en/sleep/ ↔ sueno).
	 *
	 * @param string|string[] $slugs Slugs de las páginas españolas.
	 */
	public static function is_page( $slugs ): bool {
		return is_page() && in_array( self::es_slug( (int) get_queried_object_id() ), (array) $slugs, true );
	}

	/** Portada en cualquier idioma: la estática española o la raíz /en/ (emparejada con ella). */
	public static function is_home(): bool {
		return is_front_page() || ( is_page() && self::root_id() && (int) get_queried_object_id() === self::root_id() );
	}

	/** Portada de cada idioma: / para español; /en/ solo si la raíz inglesa está publicada. */
	public static function home_url( string $lang ): string {
		if ( self::EN === $lang ) {
			$root = self::root_id();
			return $root && 'publish' === get_post_status( $root ) ? (string) get_permalink( $root ) : '';
		}
		return home_url( '/' );
	}

	/**
	 * Enlace del selector de idioma: la traducción publicada de la página actual o, si no existe, la portada de ese idioma.
	 * Vacío si el inglés aún no está publicado (el selector no se muestra).
	 */
	public static function switch_url( string $lang ): string {
		$id = is_singular() ? (int) get_queried_object_id() : 0;
		if ( $id ) {
			$other = self::translation( $id, $lang );
			if ( $other && 'publish' === get_post_status( $other ) ) {
				return (string) get_permalink( $other );
			}
		}
		return self::home_url( $lang );
	}

	/** En /en/ el sitio usa en_US (textos del tema y del plugin). wp-admin, REST y cron no cambian. */
	public function locale( string $locale ): string {
		if ( is_admin() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return $locale;
		}
		return self::EN === self::from_path() || self::EN === self::preview_lang() ? 'en_US' : $locale;
	}

	/**
	 * Vista previa de un borrador (?page_id=…&preview=true o ?p=…): la URL no lleva /en/, así que el idioma sale de la entrada.
	 * Solo para quien puede editarla; para el resto WordPress devuelve 404 de todas formas.
	 */
	private static function preview_lang(): string {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- solo lectura del ID para elegir idioma.
		if ( empty( $_GET['preview'] ) || ! function_exists( 'wp_get_current_user' ) ) {
			return self::ES;
		}
		$id = absint( $_GET['page_id'] ?? ( $_GET['p'] ?? ( $_GET['preview_id'] ?? 0 ) ) );
		// phpcs:enable
		return $id && current_user_can( 'edit_post', $id ) ? self::of( $id ) : self::ES;
	}

	/** /en/sleep/{slug}/: fichas de sueño en inglés (misma consulta que /sueno/{slug}/, filtrada por idioma en MetaRegistry). */
	public function rewrites(): void {
		add_rewrite_rule( '^' . self::SLEEP_EN . '/([^/]+)/?$', 'index.php?di_sueno_slug=$matches[1]&di_lang=en', 'top' );
	}

	/**
	 * @param string[] $vars Variables públicas.
	 * @return string[]
	 */
	public function query_vars( array $vars ): array {
		$vars[] = 'di_lang';
		return $vars;
	}

	/** Etiquetas hreflang recíprocas + x-default (español) cuando las dos versiones de la página están publicadas. */
	public function hreflang(): void {
		if ( ! is_singular() ) {
			return;
		}
		$id    = (int) get_queried_object_id();
		$es_id = self::translation( $id, self::ES );
		$en_id = self::translation( $id, self::EN );
		if ( ! $es_id || ! $en_id || 'publish' !== get_post_status( $es_id ) || 'publish' !== get_post_status( $en_id ) ) {
			return;
		}
		$es = (string) get_permalink( $es_id );
		$en = (string) get_permalink( $en_id );
		printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( self::TAGS[ self::ES ] ), esc_url( $es ) );
		printf( '<link rel="alternate" hreflang="%s" href="%s" />' . "\n", esc_attr( self::TAGS[ self::EN ] ), esc_url( $en ) );
		printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( $es ) );
	}

	/**
	 * @param string[] $classes Clases del body.
	 * @return string[]
	 */
	public function body_class( array $classes ): array {
		$classes[] = 'di-lang-' . self::current();
		return $classes;
	}
}
