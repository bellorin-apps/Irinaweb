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
		$hours = empty( $p['horario_oculto'] ) ? self::opening_hours( (string) ( $p['horario'] ?? '' ) ) : [];
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
		$hospitals = array_values( array_filter( array_map( 'trim', (array) preg_split( '/\r?\n/', (string) ( $p['hospitales'] ?? '' ) ) ) ) );
		if ( $hospitals ) {
			$node['hospitalAffiliation'] = array_map(
				static fn( string $h ): array => [
					'@type' => 'Hospital',
					'name'  => $h,
				],
				$hospitals
			);
		}
		$node = array_merge( $node, self::credential_nodes() );
		return apply_filters( 'dra_irina_schema_physician', $node );
	}

	/**
	 * Nodos memberOf, alumniOf, hasCredential y publicaciones desde el CPT credencial (solo mostrar=true).
	 *
	 * @return array<string, mixed>
	 */
	private static function credential_nodes(): array {
		if ( ! post_type_exists( 'credencial' ) ) {
			return [];
		}
		$out = [];
		$q   = get_posts(
			[
				'post_type'      => 'credencial',
				'posts_per_page' => 50,
				'meta_key'       => 'di_mostrar', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);
		foreach ( $q as $c ) {
			$tipo = (string) get_post_meta( $c->ID, 'di_tipo', true );
			$inst = (string) get_post_meta( $c->ID, 'di_institucion', true );
			$anio = (string) get_post_meta( $c->ID, 'di_anio', true );
			$url  = (string) get_post_meta( $c->ID, 'di_url', true );
			switch ( $tipo ) {
				case 'membresia':
					$out['memberOf'][] = [
						'@type' => 'Organization',
						'name'  => $c->post_title,
					];
					break;
				case 'formacion':
				case 'especialidad':
					if ( '' !== $inst ) {
						$out['alumniOf'][] = [
							'@type' => 'EducationalOrganization',
							'name'  => $inst,
						];
					}
					$out['hasCredential'][] = array_filter(
						[
							'@type'              => 'EducationalOccupationalCredential',
							'name'               => $c->post_title,
							'credentialCategory' => 'formacion' === $tipo ? 'degree' : 'specialty',
							'recognizedBy'       => '' !== $inst ? [
								'@type' => 'Organization',
								'name'  => $inst,
							] : null,
						]
					);
					break;
				case 'certificacion':
					$out['hasCredential'][] = [
						'@type'              => 'EducationalOccupationalCredential',
						'name'               => $c->post_title,
						'credentialCategory' => 'certification',
					];
					break;
				case 'publicacion':
					$out['subjectOf'][] = array_filter(
						[
							'@type'         => 'ScholarlyArticle',
							'name'          => trim( $c->post_title, '«»' ),
							'isPartOf'      => '' !== $inst ? [
								'@type' => 'Periodical',
								'name'  => $inst,
							] : null,
							'datePublished' => '' !== $anio ? $anio : null,
							'url'           => '' !== $url ? $url : null,
							'author'        => [ '@id' => self::physician_id() ],
						]
					);
					break;
			}
		}
		return $out;
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
		if ( is_page( 'preguntas-frecuentes' ) && 'publish' === get_post_status( get_queried_object_id() ) ) {
			$faq = $this->faq_page( get_queried_object_id() );
			if ( $faq ) {
				$nodes[] = $faq;
			}
		}
		// Sin nombre profesional confirmado en Ajustes → Consultorio no se emite schema médico (MASTER_PROMPT §3, §40).
		if ( '' === trim( (string) PracticeSettings::get( 'nombre_profesional' ) ) ) {
			return apply_filters( 'dra_irina_schema_graph', $nodes );
		}
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

	/** FAQ desde los mismos widgets y respuestas que se muestran, sin duplicar contenido. */
	private function faq_page( int $post_id ): array {
		$data = json_decode( (string) get_post_meta( $post_id, '_elementor_data', true ), true );
		if ( ! is_array( $data ) ) {
			return [];
		}
		$questions = [];
		$walk      = static function ( array $elements ) use ( &$walk, &$questions ): void {
			foreach ( $elements as $element ) {
				if ( ! is_array( $element ) ) {
					continue;
				}
				if ( 'di-faq' === ( $element['widgetType'] ?? '' ) ) {
					foreach ( (array) ( $element['settings']['items'] ?? [] ) as $item ) {
						if ( ! is_array( $item ) ) {
							continue;
						}
						$name = trim( wp_strip_all_tags( (string) ( $item['pregunta'] ?? '' ) ) );
						$text = trim( wp_strip_all_tags( do_shortcode( (string) ( $item['text'] ?? '' ) ) ) );
						if ( '' !== $name && '' !== $text ) {
							$questions[] = [
								'@type'          => 'Question',
								'name'           => $name,
								'acceptedAnswer' => [
									'@type' => 'Answer',
									'text'  => $text,
								],
							];
						}
					}
				}
				if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
					$walk( $element['elements'] );
				}
			}
		};
		$walk( $data );
		return $questions ? [
			'@type'      => 'FAQPage',
			'@id'        => get_permalink( $post_id ) . '#faq',
			'url'        => get_permalink( $post_id ),
			'inLanguage' => 'es-MX',
			'mainEntity' => $questions,
		] : [];
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
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
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
