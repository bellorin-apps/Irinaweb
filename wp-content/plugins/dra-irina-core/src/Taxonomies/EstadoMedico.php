<?php
/**
 * Taxonomía estado_medico: gates de aprobación médica (MASTER_PROMPT §109).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Taxonomies;

final class EstadoMedico extends AbstractTaxonomy {

	public const SLUG = 'estado_medico';

	public const DRAFT              = 'draft';
	public const TECHNICAL_REVIEW   = 'technical_review';
	public const MEDICAL_REVIEW_REQ = 'medical_review_required';
	public const MEDICALLY_APPROVED = 'medically_approved';
	public const READY              = 'ready_to_publish';

	/** Estados que permiten publicar. */
	public const PUBLISHABLE = [ self::MEDICALLY_APPROVED, self::READY ];

	protected array $object_types = [ 'condicion', 'tratamiento', 'recurso' ];

	protected function labels(): array {
		return [
			'name'          => __( 'Estado médico', 'dra-irina-core' ),
			'singular_name' => __( 'Estado médico', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [
			'hierarchical' => false,
			'meta_box_cb'  => false, // La UI la gestiona Workflow\MedicalReview (radio único, con control de capacidad).
		];
	}

	protected function default_terms(): array {
		return [
			[
				'name' => 'Borrador',
				'slug' => self::DRAFT,
			],
			[
				'name' => 'Revisión técnica / SEO',
				'slug' => self::TECHNICAL_REVIEW,
			],
			[
				'name' => 'Requiere revisión médica',
				'slug' => self::MEDICAL_REVIEW_REQ,
			],
			[
				'name' => 'Aprobado médicamente',
				'slug' => self::MEDICALLY_APPROVED,
			],
			[
				'name' => 'Listo para publicar',
				'slug' => self::READY,
			],
		];
	}
}
