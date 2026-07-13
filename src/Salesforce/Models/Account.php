<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * Account Salesforce Model
 */
class Account extends ModelsSalesforce {
	public const string OBJECT_NAME = 'Account';
	// Salesforce ID for the "Unknown Company" account if we cannot match a new contact to an existing Account.
	public const string UNKNOWN_COMPANY_SALESFORCE_RECORD_ID = '0014100001H8JOTAA3';
	// Salesforce ID for the "Pending Company Match" account if we cannot match a new contact to an existing Account.
	public const string PENDING_COMPANY_MATCH_SALESFORCE_RECORD_ID = '001Rl00001L0enHIAR';
	// Salesforce ID for the "No Company" account if a customer indicates they do not work for a company.
	public const string NO_COMPANY_SALESFORCE_RECORD_ID            = '001Rl00001OXsuYIAT';
	public const string FIELD_PRIMARY_EMAIL_DOMAIN                 = 'PrimaryEmailDomain__c';
	public const string FIELD_EMAIL                                = 'Email__c';
	public const string FIELD_COMPANY_MAILING_ADDRESS              = 'BillingAddress';
	public const string FIELD_COMPANY_MAILING_ADDRESS_STREET       = 'BillingStreet';
	public const string FIELD_COMPANY_MAILING_ADDRESS_CITY         = 'BillingCity';
	public const string FIELD_COMPANY_MAILING_ADDRESS_STATE_CODE   = 'BillingState';
	public const string FIELD_COMPANY_MAILING_ADDRESS_ZIP          = 'BillingPostalCode';
	public const string FIELD_COMPANY_MAILING_ADDRESS_COUNTRY_CODE = 'BillingCountry';

	/**
	 * Resolves a Salesforce record ID to a real WordPress post ID for TEC venues.
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
	 *     {@see self::convert_occurrence_to_venue()} to resolve it to the underlying event post ID.
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

		if ( function_exists( '\tribe_venues' ) ) {
			foreach ( $meta_keys as $meta_key ) {
				$venue = tribe_venues()
					->where( 'meta', $meta_key, $salesforce_record_id )
					->fields( 'ids' )->first();

				// make sure the returned event id matches a post ID since TEC Pro creates an occurence provisional ID when an event is created that @see https://docs.theeventscalendar.com/apis/custom-tables/events/
				if ( ! empty( $venue ) ) {
					$find_venue_post = new \WP_Query(
						array(
							'p'                      => $venue,
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

					if ( $find_venue_post->have_posts() ) {
						return (int) $find_venue_post->posts[0];
					} else {
						$venue_ids = self::convert_occurrence_to_venue( $venue );
						if ( ! empty( $venue_ids ) ) {
							return (int) $venue_ids[0];
						}
					}
				}
			}
		}

		return parent::find_post_id_by_salesforce_record_id( $salesforce_record_id, $post_type );
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
	public static function convert_occurrence_to_venue( int|string|array $occurrence_ids ): array {
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
