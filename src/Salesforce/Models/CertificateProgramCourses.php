<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * Certificate Program Course Salesforce Model
 */
class CertificateProgramCourse extends ModelsSalesforce {
	public const string OBJECT_NAME = 'CertificateProgramCourseJunction__c';
	// Field API Names
	public const string FIELD_CERTIFICATE_PROGRAM                = 'CertificateProgram__c';
	public const string FIELD_COURSE                                   = 'Course__c';
	public const string FIELD_TYPE                            = 'CourseType__c';

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
}
