<?php
/**
 * Base de widgets Elementor del core: controles mínimos, markup en el tema (templates/parts/home/*).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

abstract class AbstractWidget extends Widget_Base {

	public function get_categories(): array {
		return [ 'dra-irina' ];
	}

	public function get_icon(): string {
		return 'eicon-section';
	}

	/** Parte del tema que renderiza este widget. */
	abstract protected function part(): string;

	/**
	 * Traduce los ajustes de Elementor a los $args de la parte.
	 *
	 * @param array<string, mixed> $s Ajustes.
	 * @return array<string, mixed>
	 */
	abstract protected function args( array $s ): array;

	protected function render(): void {
		$args = $this->args( (array) $this->get_settings_for_display() );
		if ( function_exists( 'irina_part' ) ) {
			irina_part( $this->part(), $args );
			return;
		}
		echo '<p>' . esc_html__( 'Activa el tema Irina González para mostrar este bloque.', 'dra-irina-core' ) . '</p>';
	}

	/** Texto simple. */
	protected function text( string $id, string $label, string $initial = '', bool $area = false ): void {
		$this->add_control(
			$id,
			[
				'label'       => $label,
				'type'        => $area ? Controls_Manager::TEXTAREA : Controls_Manager::TEXT,
				'default'     => $initial,
				'label_block' => true,
			]
		);
	}

	/** Lista de líneas (una por renglón). */
	protected function lines( string $id, string $label, array $initial = [] ): void {
		$this->add_control(
			$id,
			[
				'label'       => $label,
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => implode( "\n", $initial ),
				'description' => __( 'Una por línea.', 'dra-irina-core' ),
				'rows'        => 6,
			]
		);
	}

	/** Repetidor de pares etiqueta / texto / url. */
	protected function repeater( string $id, string $label, array $fields, array $initial = [] ): void {
		$r = new Repeater();
		foreach ( $fields as $key => $flabel ) {
			$r->add_control(
				$key,
				[
					'label'       => $flabel,
					'type'        => 'url' === $key ? Controls_Manager::TEXT : ( 'text' === $key ? Controls_Manager::TEXTAREA : Controls_Manager::TEXT ),
					'label_block' => true,
				]
			);
		}
		$this->add_control(
			$id,
			[
				'label'       => $label,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'default'     => $initial,
				'title_field' => '{{{ ' . array_key_first( $fields ) . ' }}}',
			]
		);
	}

	protected function image( string $id, string $label ): void {
		$this->add_control(
			$id,
			[
				'label' => $label,
				'type'  => Controls_Manager::MEDIA,
			]
		);
	}

	/** @return array<int, string> */
	protected static function to_lines( $value ): array {
		return array_values( array_filter( array_map( 'trim', (array) preg_split( '/\r\n|\r|\n/', (string) $value ) ) ) );
	}

	protected static function image_id( $value ): int {
		return is_array( $value ) ? (int) ( $value['id'] ?? 0 ) : 0;
	}
}
