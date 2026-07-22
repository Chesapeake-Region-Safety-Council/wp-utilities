<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * Course Salesforce Model
 */
class Course extends ModelsSalesforce {
	public const string OBJECT_NAME = 'Course__c';
	// Field API Names
	public const string FIELD_COMPLETION_MATERIAL                    = 'CompletionMaterial__c';
	public const string FIELD_COST                                   = 'Cost__c';
	public const string FIELD_MEMBER_COST                            = 'MemberCost__c';
	public const string FIELD_CONTACT_HOURS                          = 'ContactHour__c';
	public const string FIELD_CONTINUING_EDUCATION_UNITS             = 'ContinuingEducationUnit__c';
	public const string FIELD_PUBLIC_COURSE_ID                       = 'PublicCourseID__c';
	public const string FIELD_PRIVATE_COURSE_ID                      = 'PrivateCourseID__c';
	public const string FIELD_ACTIVE_COURSE_VERSIONS                 = 'ActiveCourseVersions__c';
	public const string FIELD_COURSE_LANGUAGE_AVAILABLE              = 'CourseLanguageAvailable__c';
	public const string FIELD_PREREQUISITE_INFORMATION               = 'PrerequisiteInformation__c';
	public const string FIELD_PREREQUISITE_INFORMATION_SPANISH       = 'PrerequisiteInformationSpanish__c';
	public const string FIELD_PREREQUISITE_VERIFICATION_FORM         = 'PrerequisiteVerificationForm__c';
	public const string FIELD_PREREQUISITE_VERIFICATION_FORM_SPANISH = 'PrerequisiteVerificationFormSpanish__c';
	public const string FIELD_DAYS                                   = 'Day__c';
	public const string FIELD_COURSE_LONG_NAME                       = 'CourseLongName__c';
	public const string FIELD_COURSE_LONG_DESCRIPTION                = 'CourseLongDescription__c';
	public const string FIELD_COURSE_LONG_DESCRIPTION_SPANISH        = 'CourseLongDescriptionSpanish__c';
	public const string FIELD_IS_ACTIVE                              = 'IsActive__c';
	public const string FIELD_PRIVATE                                = 'Private__c';
	public const string FIELD_PUBLISHED                              = 'Published__c';
	public const string FIELD_COURSE_URL_CHESAPEAKESC                = 'CourseURLChesapeakeSC__c';
	public const string FIELD_COURSE_URL_OSHAMIDATLANTIC             = 'CourseURLOSHAMidatlantic__c';
	public const string FIELD_PUBLISH_COURSE_CHESAPEAKESC            = 'PublishCourseToChesapeakeSC__c';
	public const string FIELD_PUBLISH_COURSE_OSHAMIDATLANTIC         = 'PublishCourseToOSHAMidatlantic__c';
	public const string FIELD_SYNC_DATE_OSHAMIDATLANTIC              = 'OSHASyncDate__c';
	public const string FIELD_SYNC_DATE_CHESAPEAKE                   = 'ChesapeakeSyncDate__c';

	/**
	 * Get the Salesforce Course ID.
	 *
	 * @return string
	 */
	public function get_course_id(): string {
		return (string) get_post_meta( $this->post_id, '_course_id', true );
	}

	/**
	 * Set the Salesforce Course ID.
	 *
	 * @param string $course_id The Salesforce Course ID.
	 */
	public function set_course_id( string $course_id ): void {
		update_post_meta( $this->post_id, '_course_id', $course_id );
	}

	/**
	 * Get the Salesforce Course ID.
	 *
	 * @return string
	 */
	public function get_public_course_id(): string {
		return (string) get_post_meta( $this->post_id, '_course_id_public', true );
	}

	/**
	 * Set the course ID slug for the associated post.
	 *
	 * @param string $course_id The course ID to be set as a slug.
	 *
	 * @return void
	 */
	public function set_course_id_slug( string $course_id ): void {
		update_post_meta( $this->post_id, '_course_id_slug', sanitize_title( $course_id ) );
	}

	/**
	 * Retrieve the course ID slug for the current post.
	 *
	 * @return string
	 */
	public function get_course_id_slug(): string {
		return (string) get_post_meta( $this->post_id, '_course_id_slug', true );
	}

	/**
	 * Set the Salesforce Course ID.
	 *
	 * @param string $course_id The Salesforce Course ID.
	 */
	public function set_public_course_id( string $course_id ): void {
		update_post_meta( $this->post_id, '_course_id_public', $course_id );
	}

	/**
	 * Get the Salesforce Course ID.
	 *
	 * @return string
	 */
	public function get_private_course_id(): string {
		return (string) get_post_meta( $this->post_id, '_course_id_private', true );
	}

	/**
	 * Set the Salesforce Course ID.
	 *
	 * @param string $course_id The Salesforce Course ID.
	 */
	public function set_private_course_id( string $course_id ): void {
		update_post_meta( $this->post_id, '_course_id_private', $course_id );
	}

	/**
	 * Get the course ID for a given post ID.
	 *
	 * @param string|int $post_id The post ID.
	 * @return string
	 */
	public static function get_public_course_id_static( string|int $post_id ): string {
		return (string) get_post_meta( $post_id, '_course_id_public', true );
	}

	/**
	 * Update the course ID for a given post ID.
	 *
	 * @param string|int $post_id   The post ID.
	 * @param string     $course_id The Salesforce Course ID.
	 */
	public static function update_public_course_id_static( string|int $post_id, string $course_id ): void {
		update_post_meta( $post_id, '_course_id_public', $course_id );
	}

	/**
	 * Get the course ID for a given post ID.
	 *
	 * @param string|int $post_id The post ID.
	 * @return string
	 */
	public static function get_private_course_id_static( string|int $post_id ): string {
		return (string) get_post_meta( $post_id, '_course_id_private', true );
	}

	/**
	 * Update the course ID for a given post ID.
	 *
	 * @param string|int $post_id   The post ID.
	 * @param string     $course_id The Salesforce Course ID.
	 */
	public static function update_private_course_id_static( string|int $post_id, string $course_id ): void {
		update_post_meta( $post_id, '_course_id_private', $course_id );
	}

	/**
	 * Get the course ID for a given post ID.
	 *
	 * @param string|int $post_id The post ID.
	 * @return string
	 */
	public static function get_course_id_static( string|int $post_id ): string {
		return (string) get_post_meta( $post_id, '_course_id', true );
	}

	/**
	 * Update the course ID for a given post ID.
	 *
	 * @param string|int $post_id   The post ID.
	 * @param string     $course_id The Salesforce Course ID.
	 */
	public static function update_course_id_static( string|int $post_id, string $course_id ): void {
		update_post_meta( $post_id, '_course_id', $course_id );
	}

	/**
	 * Find a post ID by the course ID.
	 *
	 * @param string $course_id The Salesforce Course ID.
	 * @return int|false The post ID or false if not found.
	 */
	public static function find_by_course_id( string $course_id ): int|false {
		$query = new \WP_Query(
			array(
				'post_type'      => self::get_post_type(),
				'post_status'    => 'any',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_course_id',
						'value'   => $course_id,
						'compare' => '=',
					),
					array(
						'key'     => '_course_id_public',
						'value'   => $course_id,
						'compare' => '=',
					),
					array(
						'key'     => '_course_id_private',
						'value'   => $course_id,
						'compare' => '=',
					),
				),
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		if ( $query->have_posts() ) {
			return (int) $query->posts[0];
		}

		return false;
	}

	public static function get_post_type(): string {
		if ( class_exists( '\CRSC\CorePlatform\PostTypes\CoursePostType' ) ) {
			return \CRSC\CorePlatform\PostTypes\CoursePostType::slug();
		}

		return 'crsc_course';
	}

	/**
	 * Get the completion material.
	 *
	 * @return string
	 */
	public function get_completion_material(): string {
		return (string) get_post_meta( $this->post_id, '_course_completion_material', true );
	}

	/**
	 * Set the completion material.
	 *
	 * @param string $value The completion material.
	 */
	public function set_completion_material( string $value ): void {
		update_post_meta( $this->post_id, '_course_completion_material', $value );
	}

	/**
	 * Get the course cost.
	 *
	 * @return float
	 */
	public function get_cost(): float {
		return (float) get_post_meta( $this->post_id, '_course_cost', true );
	}

	/**
	 * Set the course cost.
	 *
	 * @param float|string $value The course cost.
	 */
	public function set_cost( int|float|string $value ): void {
		update_post_meta( $this->post_id, '_course_cost', (string) $value );
	}

	/**
	 * Get the member cost.
	 *
	 * @return float
	 */
	public function get_member_cost(): float {
		return (float) get_post_meta( $this->post_id, '_course_member_cost', true );
	}

	/**
	 * Set the member cost.
	 *
	 * @param float|string $value The member cost.
	 */
	public function set_member_cost( int|float|string|null $value ): void {
		update_post_meta( $this->post_id, '_course_member_cost', (string) $value );
	}

	/**
	 * Get the contact hours.
	 *
	 * @return float
	 */
	public function get_contact_hours(): int|float {
		return (float) get_post_meta( $this->post_id, '_course_contact_hours', true );
	}

	/**
	 * Set the contact hours.
	 *
	 * @param float|string $value The contact hours.
	 */
	public function set_contact_hours( int|float|string|null $value ): void {
		if ( empty( $value ) ) {
			$value = '';
		}

		update_post_meta( $this->post_id, '_course_contact_hours', (string) $value );
	}

	/**
	 * Get the Continuing Education Units value.
	 *
	 * @return float
	 */
	public function get_continuing_education_units(): int|float {
		return (float) get_post_meta( $this->post_id, '_course_continuing_education_units', true );
	}

	/**
	 * Set the Continuing Education Units value.
	 *
	 * @param float|string $value The contact hours.
	 */
	public function set_continuing_education_units( int|float|string|null $value ): void {
		if ( empty( $value ) ) {
			$value = '';
		}

		update_post_meta( $this->post_id, '_course_continuing_education_units', (string) $value );
	}

	/**
	 * Check if the course is active.
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		return filter_var( get_post_meta( $this->post_id, '_course_is_active', true ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Set if the course is active.
	 *
	 * @param bool|int|string $value Active status.
	 */
	public function set_is_active( bool|int|string $value ): void {
		$is_active = filter_var( $value, FILTER_VALIDATE_BOOLEAN ) ? '1' : '0';
		update_post_meta( $this->post_id, '_course_is_active', $is_active );
	}

	/**
	 * Check if the course is private.
	 *
	 * @return bool
	 */
	public function is_private(): bool {
		return filter_var( get_post_meta( $this->post_id, '_course_is_private', true ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Set if the course is private.
	 *
	 * @param bool|int|string $value Private status.
	 */
	public function set_is_private( bool|int|string $value ): void {
		$is_private = filter_var( $value, FILTER_VALIDATE_BOOLEAN ) ? '1' : '0';
		update_post_meta( $this->post_id, '_course_is_private', $is_private );
	}

	/**
	 * Get the Course Languages available.
	 *
	 * @return string
	 */
	public function get_course_language_available(): string {
		return (string) get_post_meta( $this->post_id, '_course_language_available', true );
	}

	/**
	 * Set the Course Languages available.
	 *
	 * @param string $value The completion material.
	 */
	public function set_course_language_available( string $value ): void {
		update_post_meta( $this->post_id, '_course_language_available', $value );
	}

	/**
	 * Set the prerequisite information.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function set_prerequisite_information( string $value ): void {
		update_post_meta( $this->post_id, '_course_prerequisite_information', $value );
	}

	/**
	 * Get the prerequisite information.
	 *
	 * @return string
	 */
	public function get_prerequisite_information(): string {
		return (string) get_post_meta( $this->post_id, '_course_prerequisite_information', true );
	}

	/**
	 * Set the number of days that a class extends over,
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function set_days( string $value ): void {
		update_post_meta( $this->post_id, '_course_days', $value );
	}

	/**
	 * Get the number of days that a class extends over,
	 *
	 * @return string
	 */
	public function get_days(): string {
		return (string) get_post_meta( $this->post_id, '_course_days', true );
	}

	/**
	 * Set the prerequisite form.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function set_prerequisite_verification_form( string $value ): void {
		update_post_meta( $this->post_id, '_course_prerequisite_verification_form', $value );
	}

	/**
	 * Get the prerequisite form.
	 *
	 * @return string
	 */
	public function get_prerequisite_verification_form(): string {
		return (string) get_post_meta( $this->post_id, '_course_prerequisite_verification_form', true );
	}

	/**
	 * Set the Spanish course description for the current post ID.
	 *
	 * @param string $value The Spanish course description to set.
	 *
	 * @return void
	 */
	public function set_spanish_course_description( string $value ): void {
		update_post_meta( $this->post_id, '_course_spanish_course_description', $value );
	}

	/**
	 * Retrieve the Spanish course description for the current post.
	 *
	 * @return string
	 */
	public function get_spanish_course_description(): string {
		return (string) get_post_meta( $this->post_id, '_course_spanish_course_description', true );
	}

	/**
	 * Sets the Spanish prerequisite information for the course.
	 *
	 * @param string $value The Spanish prerequisite information to be set.
	 *
	 * @return void
	 */
	public function set_spanish_prerequisite_information( string $value ): void {
		update_post_meta( $this->post_id, '_course_spanish_prerequisite_information', $value );
	}

	/**
	 * Retrieves the Spanish prerequisite information for the course.
	 *
	 * @return string The Spanish prerequisite information associated with the course.
	 */
	public function get_spanish_prerequisite_information(): string {
		return (string) get_post_meta( $this->post_id, '_course_spanish_prerequisite_information', true );
	}

	/**
	 * Sets the Spanish prerequisite verification form for the course.
	 *
	 * @param string $value The Spanish prerequisite verification form to be set.
	 *
	 * @return void
	 */
	public function set_spanish_prerequisite_verification_form( string $value ): void {
		update_post_meta( $this->post_id, '_course_spanish_prerequisite_verification_form', $value );
	}

	/**
	 * Retrieves the Spanish prerequisite verification form for the course.
	 *
	 * @return string The Spanish prerequisite verification form associated with the course.
	 */
	public function get_spanish_prerequisite_verification_form(): string {
		return (string) get_post_meta( $this->post_id, '_course_spanish_prerequisite_verification_form', true );
	}

	/**
	 * Store the names of the certificate programs that this Course belongs to.
	 *
	 * @param array $values Names of the certificate programs that this Course is a part of.
	 *
	 * @return void
	 */
	public function set_certificate_program_names( array $values ): void {
		update_post_meta( $this->post_id, '_course_certificate_programs', wp_json_encode( $values ) );
	}

	/**
	 * Get the names of the Certificate Programs that this Course belongs to.
	 *
	 * @return array|null Names of the certificate programs that the Course belongs to.
	 */
	public function get_certificate_program_names(): array|null {
		$programs = get_post_meta( $this->post_id, '_course_certificate_programs', true );
		if ( empty( $programs ) ) {
			return null;
		}

		return json_decode( $programs, true );
	}
}
