<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * Certificate Program Salesforce Model
 */
class CertificateProgram extends ModelsSalesforce {
	public const string OBJECT_NAME = 'CertificateProgram__c';
	// Field API Names
	public const string FIELD_CERTIFICATE_PROGRAM_PROVIDER                    = 'CertificateProgramProvider__c';
	public const string FIELD_DESCRIPTION                                   = 'ProgramDescription__c';
	public const string FIELD_REQUIREMENTS                            = 'ProgramRequirements__c';
	public const string FIELD_COMPLETION_MATERIALS                          = 'CompletionMaterial__c';

	/**
	 * Get the completion material.
	 *
	 * @return string
	 */
	public function get_completion_material(): string {
		return (string) get_post_meta( $this->post_id, '_certificate_program_completion_material', true );
	}

	/**
	 * Set the completion material.
	 *
	 * @param string $value The completion material.
	 */
	public function set_completion_material( string $value ): void {
		update_post_meta( $this->post_id, '_certificate_program_completion_material', $value );
	}

	/**
	 * Store the Course IDs of the Courses that belong to this Certifcate Program.
	 *
	 * @param array $values Courses IDs that are a part of this Certificate Program.
	 *
	 * @return void
	 */
	public function set_certificate_program_courses( array $values ): void {
		update_post_meta( $this->post_id, '_certificate_programs_courses', wp_json_encode( $values ) );
	}

	/**
	 * Get the Course IDs of the Courses that belong to this Certificate Program.
	 *
	 * @return array|null List of the Course IDs that a part of this certificate program.
	 */
	public function get_certificate_program_courses(): array|null {
		$courses = get_post_meta( $this->post_id, '_certificate_programs_courses', true );

		if ( empty( $courses ) ) {
			return null;
		}

		return json_decode( $courses, true );
	}
}
