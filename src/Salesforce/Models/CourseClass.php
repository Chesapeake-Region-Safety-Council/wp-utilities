<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * ClassCourse Salesforce Model
 *
 * Represents the Class__c object in Salesforce.
 */
class CourseClass extends ModelsSalesforce {

	public const string OBJECT_NAME = 'Class__c';
	// Field API Names
	public const string FIELD_CLASS_NAME                    = 'ClassName__c';
	public const string FIELD_CLASS_DESCRIPTION             = 'ClassDescription__c';
	public const string FIELD_ADDITIONAL_CLASS_INFORMATION  = 'AdditionalClassInformation__c';
	public const string FIELD_START_DATE                    = 'StartDate__c';
	public const string FIELD_END_DATE                      = 'EndDate__c';
	public const string FIELD_START_TIME                    = 'StartTime__c';
	public const string FIELD_END_TIME                      = 'EndTime__c';
	public const string FIELD_LOCATION                      = 'Location__c';
	public const string FIELD_HOST_SITE                     = 'HostSite__c';
	public const string FIELD_HOST_SITE_ADDRESS             = 'HostSiteAddress__c';
	public const string FIELD_COST                          = 'Cost__c';
	public const string FIELD_MEMBER_COST                   = 'MemberCost__c';
	public const string FIELD_MAX_CAPACITY                  = 'MaxCapacity__c';
	public const string FIELD_INSTRUCTOR                    = 'Instructor__c';
	public const string FIELD_STATUS                        = 'Status__c';
	public const string FIELD_TYPE                          = 'Type__c';
	public const string FIELD_IS_VIRTUAL                    = 'IsVirtual__c';
	public const string FIELD_COURSE                        = 'Course__c';
	public const string FIELD_PARENT_COURSE_ID              = 'ParentCourseID__c';
	public const string FIELD_STUDENTS_ENROLLED             = 'StudentsEnrolled__c';
	public const string FIELD_CLASS_ID                      = 'ClassID__c';
	public const string FIELD_CLOSE_DATE                    = 'CloseDate__c';
	public const string FIELD_CLASS_URL_CHESAPEAKESC        = 'ClassURLChesapeakeSC__c';
	public const string FIELD_CLASS_URL_OSHAMIDATLANTIC     = 'ClassURLOSHAMidatlantic__c';
	public const string FIELD_PUBLISH_CLASS_CHESAPEAKESC    = 'PublishClassToChesapeakeSC__c';
	public const string FIELD_PUBLISH_CLASS_OSHAMIDATLANTIC = 'PublishClassToOSHAMidatlantic__c';
	public const string FIELD_SYNC_DATE_OSHAMIDATLANTIC     = 'OSHASyncDate__c';
	public const string FIELD_SYNC_DATE_CHESAPEAKE          = 'ChesapeakeSyncDate__c';
	public const string FIELD_PUBLISHED                     = 'Published__c';
	public const string FIELD_CLASS_PASSWORD                = 'ClassPassword__c';
	public const string STATUS_PLANNED                      = 'Planned';
	public const string STATUS_CLOSED                       = 'Closed';
	public const string STATUS_CANCELLED                    = 'Cancelled';
	public const string STATUS_COMPLETED                    = 'Completed';
	public const string STATUS_POSTPONED                    = 'Postponed';
	public const string TYPE_PUBLIC_CLASS                   = 'Public Class';
	public const string TYPE_PRIVATE_CLASS                  = 'Private Class';
	public const string TYPE_INVITATION_ONLY                = 'Invitation Only';

	/**
	 * Resolves a Salesforce record ID to a real WordPress post ID for TEC events.
	 *
	 * The Events Calendar's Custom Tables v1 (CT1) architecture stores event occurrences
	 * in the `tec_occurrences` table and issues "provisional post IDs" for each occurrence.
	 * When querying via the `tribe_events()` ORM, the returned ID may be one of these
	 * provisional IDs rather than the actual `wp_posts` post ID. Provisional IDs behave
	 * like real post IDs (they work with `get_post()`, `get_post_meta()`, etc.) because
	 * CT1 pre-fills the WordPress object cache — but they are not persisted rows in `wp_posts`.
	 *
	 * This method:
	 *  1. Queries the TEC ORM across known Salesforce meta keys for the given record ID.
	 *  2. Confirms the returned ID is a real `wp_posts` row via WP_Query with `tec_events_ignore`.
	 *  3. If the ID is a provisional occurrence ID (no matching post row), calls
	 *     {@see self::convert_occurrence_to_event()} to resolve it to the underlying event post ID.
	 *  4. Falls back to the parent implementation when TEC is not active.
	 *
	 * @param string $salesforce_record_id The Salesforce object ID to look up.
	 * @param string $post_type            Optional post type hint passed to the parent fallback.
	 *
	 * @return int|false The real WordPress post ID on success, or false if not found.
	 */
	public static function find_post_id_by_salesforce_record_id( string $salesforce_record_id, string $post_type = '' ): int|false {
		$salesforce_record_id = sanitize_text_field( $salesforce_record_id );
		$meta_keys            = array(
			'_salesforce_object_id',
			'_production_salesforce_record_id',
			'_sandbox_salesforce_record_id',
		);

		if ( function_exists( '\tribe_events' ) ) {
			foreach ( $meta_keys as $meta_key ) {
				$event = tribe_events()
					->where( 'meta', $meta_key, $salesforce_record_id )
					->fields( 'ids' )->first();

				// make sure the returned event id matches a post ID since TEC Pro creates an occurence provisional ID when an event is created that @see https://docs.theeventscalendar.com/apis/custom-tables/events/
				if ( ! empty( $event ) ) {
					$find_event_post = new \WP_Query(
						array(
							'p'                      => $event,
							'post_type'              => 'tribe_events',
							'no_found_rows'          => true,
							'update_post_term_cache' => false,
							'update_post_meta_cache' => false,
							'posts_per_page'         => 1,
							'fields'                 => 'ids',
							'suppress_filters'       => true,
							'tec_events_ignore'      => true,
						)
					);

					if ( $find_event_post->have_posts() ) {
						return (int) $find_event_post->posts[0];
					} else {
						$event_ids = self::convert_occurrence_to_event( $event );
						if ( ! empty( $event_ids ) ) {
							return (int) $event_ids[0];
						}
					}
				}
			}
		}

		return parent::find_post_id_by_salesforce_record_id( $salesforce_record_id, $post_type );
	}

	/**
	 * Set the Salesforce Class ID attached to the Class post.
	 * @param string|int $post_id
	 * @param string $value
	 *
	 * @return void
	 */
	public static function update_class_id( string|int $post_id, string $value ): void {
		if ( function_exists( '\tribe_events' ) ) {
			try {
				tribe_events()->by( 'ID', $post_id )->set( '_salesforce_class_id', $value )->save();
			} catch ( \Exception $e ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'Error updating Salesforce Class ID: ' . $e->getMessage() . ' ' . __METHOD__ );
			}
		}

		update_post_meta( $post_id, '_salesforce_class_id', $value );
	}

	/**
	 * Get the Salesforce Class ID attached to the Class post.
	 * @param string|int $post_id
	 *
	 * @return mixed
	 */
	public static function get_class_id( string|int $post_id ): mixed {
		return get_post_meta( $post_id, '_salesforce_class_id', true );
	}


	/**
	 * Updates the parent Salesforce course ID for a Class post.
	 *
	 * @param string|int $post_id The ID of the post to update.
	 * @param string $value The new Salesforce course ID to assign.
	 *
	 * @return void
	 */
	public static function update_course_id( string|int $post_id, string $value ): void {
		if ( function_exists( '\tribe_events' ) ) {
			try {
				tribe_events()->by( 'ID', $post_id )->set( '_salesforce_course_id', $value )->save();
			} catch ( \Exception $e ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'Error updating Salesforce Class ID: ' . $e->getMessage() . ' ' . __METHOD__ );
			}
		}

		update_post_meta( $post_id, '_salesforce_course_id', $value );
	}

	/**
	 * Retrieves the Salesforce course ID associated with a given Class post.
	 *
	 * @param string|int $post_id The ID of the post to retrieve the Salesforce course ID for.
	 *
	 * @return string The Salesforce course ID associated with the post.
	 */
	public static function get_course_id( string|int $post_id ): mixed {
		return get_post_meta( $post_id, '_salesforce_course_id', true );
	}

	/**
	 * Find an event by a meta key and value using ORM.
	 *
	 * @param string $meta_key   The meta key.
	 * @param mixed  $meta_value The meta value.
	 * @return int|false The event ID if found, or false.
	 */
	public static function find_event_by_meta( string $meta_key, mixed $meta_value ): int|false {
		if ( ! function_exists( '\tribe_events' ) ) {
			return false;
		}

		$event = tribe_events()
			->where( 'meta', $meta_key, $meta_value )
			->fields( 'ids' )
			->first();
	}

	/**
	 * Resolves provisional TEC occurrence IDs to their underlying WordPress event post IDs.
	 *
	 * The Events Calendar's Custom Tables v1 (CT1) stores each occurrence of a single or
	 * recurring event as a row in the `tec_occurrences` table. Rather than creating one
	 * `wp_posts` entry per occurrence, TEC generates provisional post IDs that are
	 * mathematically derived from the real event post ID. These provisional IDs are injected
	 * into the WordPress object cache at query time so that standard WP functions (`get_post()`,
	 * `get_post_meta()`, etc.) work transparently — but they have no corresponding row in
	 * `wp_posts` and cannot be retrieved via a direct database post query.
	 *
	 * `Occurrence::normalize_id()` maps a provisional ID back to the canonical `wp_posts`
	 * post ID of the event the occurrence belongs to. If CT1 is not active (the class does
	 * not exist), the IDs are returned unchanged.
	 *
	 * @param int|string|array $occurrence_ids A single provisional occurrence ID or an array
	 *                                         of IDs returned by the `tribe_events()` ORM.
	 *
	 * @return array Resolved event post IDs. If CT1 is unavailable, the original IDs are returned.
	 */
	public static function convert_occurrence_to_event( int|string|array $occurrence_ids ): array {
		if ( ! is_array( $occurrence_ids ) ) {
			$occurrence_ids = array( $occurrence_ids );
		}

		// convert the occurrence ID to the actual event ID/post ID that the occurrence belongs to
		if ( method_exists( '\TEC\Events\Custom_Tables\V1\Models\Occurrence', 'normalize_id' ) ) {
			return array_map(
				function ( $occurrence_id ) {
					return \TEC\Events\Custom_Tables\V1\Models\Occurrence::normalize_id( $occurrence_id );
				},
				$occurrence_ids
			);
		}

		return $occurrence_ids;
	}
}
