<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce;

use CRSC\WPUtilities\WCSettingsHelper;
use CRSC\WPUtilities\WCSettings;
use CRSC\WPUtilities\WPSettings;
use CRSC\WPUtilities\WCOrder;

/**
 * Salesforce Model
 *
 * Represents data synced from Salesforce to WordPress and vice-versa.
 */
class Salesforce {
	use WCSettingsHelper;

	const string FIELD_ID   = 'Id';
	const string FIELD_NAME = 'Name';

	/**
	 * @var int WordPress Post ID
	 */
	protected int $post_id = 0;

	/**
	 * @var int WordPress Taxonomy Term ID
	 */
	protected int $term_id = 0;

	/**
	 * @var mixed WooCommerce data object (Product, Order, etc.)
	 */
	protected mixed $wc_object = null;

	/**
	 * Salesforce constructor.
	 *
	 * @param int|string|object $id_or_object Post ID or WooCommerce object.
	 */
	public function __construct( int|string|object $id_or_object, string $object_type = 'post' ) {
		if ( is_numeric( $id_or_object ) ) {
			if ( 'term' === $object_type ) {
				$this->term_id = (int) $id_or_object;
			} else {
				$this->post_id = (int) $id_or_object;
			}
		} elseif ( is_object( $id_or_object ) ) {
			if ( $id_or_object instanceof \WC_Order || $id_or_object instanceof \WC_Product ) {
				$this->wc_object = $id_or_object;
				$this->post_id   = (int) $id_or_object->get_id();
			} elseif ( isset( $id_or_object->ID ) && $id_or_object instanceof \WP_Term ) {
				$this->term_id = (int) $id_or_object->term_id;
			} elseif ( isset( $id_or_object->ID ) ) {
				$this->post_id = (int) $id_or_object->ID;
			}
		}
	}

	/**
	 * Set the Salesforce record ID.
	 *
	 * Writes to meta based on current Salesforce mode.
	 *
	 * @param string $salesforce_record_id Salesforce record ID.
	 * @return void
	 */
	public function set_salesforce_record_id( string $salesforce_record_id, string $salesforce_object_type = '' ): void {
		$mode      = $this->get_sf_environment();
		$meta_key  = ( 'live' === $mode ) ? '_production_salesforce_record_id' : '_sandbox_salesforce_record_id';
		$sync_time = current_time( 'mysql', false );

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( empty( $salesforce_object_type ) && isset( $this->OBJECT_NAME ) ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$salesforce_object_type = $this->OBJECT_NAME;
		}

		if ( $this->wc_object ) {
			// Object exists but is not yet saved to DB or just a WooCommerce object like order or product
			$this->wc_object->update_meta_data( $meta_key, $salesforce_record_id );
			$this->wc_object->update_meta_data( '_salesforce_sync_mode', $mode );
			$this->wc_object->update_meta_data( '_salesforce_sync_time', $sync_time );

			if ( ! empty( $salesforce_object_type ) ) {
				$this->wc_object->update_meta_data( '_salesforce_object_type', $salesforce_object_type );
			}

			// if this object has a Post ID, update the object in WooCommerce's database since that mean it has been saved before
			if ( 0 !== $this->post_id ) {
				$this->wc_object->save();
			}
		} elseif ( ! empty( $this->term_id ) ) {
			// Existing term
			update_term_meta( $this->term_id, $meta_key, $salesforce_record_id );
			update_term_meta( $this->term_id, '_salesforce_sync_mode', $mode );
			update_term_meta( $this->term_id, '_salesforce_sync_time', $sync_time );

			if ( ! empty( $salesforce_object_type ) ) {
				update_term_meta( $this->term_id, '_salesforce_object_type', $salesforce_object_type );
			}
		} else {
			// Existing post or object already in DB
			update_post_meta( $this->post_id, $meta_key, $salesforce_record_id );
			update_post_meta( $this->post_id, '_salesforce_sync_mode', $mode );
			update_post_meta( $this->post_id, '_salesforce_sync_time', $sync_time );

			if ( ! empty( $salesforce_object_type ) ) {
				update_post_meta( $this->post_id, '_salesforce_object_type', $salesforce_object_type );
			}
		}
	}

	/**
	 * Retrieves the metadata key name for the Salesforce record ID based on the current Salesforce mode.
	 *
	 * @return string The metadata key name for the Salesforce record ID, either '_production_salesforce_record_id' for live mode or '_sandbox_salesforce_record_id' for sandbox mode.
	 */
	public static function get_salesforce_record_id_key_name(): string {
		return ( 'live' === self::get_sf_environment() ) ? '_production_salesforce_record_id' : '_sandbox_salesforce_record_id';
	}

	/**
	 * Save the Salesforce record name in post meta.
	 *
	 * @param string $salesforce_record_name
	 *
	 * @return void
	 */
	public function set_salesforce_record_name( string $salesforce_record_name ): void {
		if ( $this->wc_object ) {
			$this->wc_object->update_meta_data( '_salesforce_record_name', $salesforce_record_name );
			$this->wc_object->save();
		} elseif ( ! empty( $this->term_id ) ) {
			update_term_meta( $this->term_id, '_salesforce_record_name', $salesforce_record_name );
		} else {
			update_post_meta( $this->post_id, '_salesforce_record_name', $salesforce_record_name );
		}
	}

	/**
	 * Get the Salesforce record name.
	 *
	 * @return string
	 */
	public function get_salesforce_record_name(): string {
		if ( $this->wc_object ) {
			return (string) $this->wc_object->get_meta( '_salesforce_record_name', true );
		} elseif ( ! empty( $this->term_id ) ) {
			return (string) get_term_meta( $this->term_id, '_salesforce_record_name', true );
		} else {
			return (string) get_post_meta( $this->post_id, '_salesforce_record_name', true );
		}
	}

	/**
	 * Deletes the Salesforce record ID and related metadata associated with the object or post.
	 *
	 * This method removes the metadata for the Salesforce record ID, sync mode, and sync time,
	 * either from the WooCommerce object or from the post metadata based on the current mode
	 * (live or sandbox).
	 *
	 * @return void
	 */
	public function delete_salesforce_record_id(): void {
		$mode     = $this->get_sf_environment();
		$meta_key = ( 'live' === $mode ) ? '_production_salesforce_record_id' : '_sandbox_salesforce_record_id';

		if ( $this->wc_object ) {
			$this->wc_object->delete_meta_data( $meta_key );
			$this->wc_object->delete_meta_data( '_salesforce_sync_mode' );
			$this->wc_object->delete_meta_data( '_salesforce_sync_time' );
			$this->wc_object->delete_meta_data( '_salesforce_object_type' );

			if ( 0 !== $this->post_id ) {
				$this->wc_object->save();
			}
		} elseif ( ! empty( $this->term_id ) ) {
			delete_term_meta( $this->term_id, $meta_key );
			delete_term_meta( $this->term_id, '_salesforce_sync_mode' );
			delete_term_meta( $this->term_id, '_salesforce_sync_time' );
			delete_term_meta( $this->term_id, '_salesforce_object_type' );
		} else {
			delete_post_meta( $this->post_id, $meta_key );
			delete_post_meta( $this->post_id, '_salesforce_sync_mode' );
			delete_post_meta( $this->post_id, '_salesforce_sync_time' );
			delete_post_meta( $this->post_id, '_salesforce_object_type' );
		}
	}

	/**
	 * Get the Salesforce record ID based on current mode.
	 *
	 * @return string
	 */
	public function get_salesforce_record_id(): string {
		$mode     = $this->get_sf_environment();
		$meta_key = ( 'live' === $mode ) ? '_production_salesforce_record_id' : '_sandbox_salesforce_record_id';

		if ( $this->wc_object && ! empty( $this->wc_object->get_meta( $meta_key, true ) ) ) {
			return (string) $this->wc_object->get_meta( $meta_key, true );
		} elseif ( ! empty( $this->term_id ) ) {
			return (string) get_term_meta( $this->term_id, $meta_key, true );
		}

		return (string) get_post_meta( $this->post_id, $meta_key, true );
	}

	/**
	 * Get the last sync mode.
	 *
	 * @return string
	 */
	public function get_salesforce_sync_mode(): string {
		$meta_key = '_salesforce_sync_mode';

		if ( $this->wc_object && ! empty( $this->wc_object->get_meta( $meta_key, true ) ) ) {
			return (string) $this->wc_object->get_meta( $meta_key, true );
		} elseif ( ! empty( $this->term_id ) ) {
			return (string) get_term_meta( $this->term_id, $meta_key, true );
		}

		return (string) get_post_meta( $this->post_id, $meta_key, true );
	}

	/**
	 * Get the last sync time.
	 *
	 * @return string
	 */
	public function get_salesforce_sync_time(): string {
		$meta_key = '_salesforce_sync_time';

		if ( $this->wc_object && ! empty( $this->wc_object->get_meta( $meta_key, true ) ) ) {
			return (string) $this->wc_object->get_meta( $meta_key, true );
		} elseif ( ! empty( $this->term_id ) ) {
			return (string) get_term_meta( $this->term_id, $meta_key, true );
		}

		return (string) get_post_meta( $this->post_id, $meta_key, true );
	}

	/**
	 * Retrieves the Salesforce object type associated with the current object.
	 *
	 * @return string The Salesforce object type. Returns the value from the WooCommerce object's meta data if available,
	 *                otherwise retrieves it from the post meta data.
	 */
	public function get_salesforce_object_type(): string {
		$meta_key = '_salesforce_object_type';

		if ( $this->wc_object && ! empty( $this->wc_object->get_meta( $meta_key, true ) ) ) {
			return (string) $this->wc_object->get_meta( $meta_key, true );
		} elseif ( ! empty( $this->term_id ) ) {
			return (string) get_term_meta( $this->term_id, $meta_key, true );
		}

		return (string) get_post_meta( $this->post_id, $meta_key, true );
	}

	/**
	 * Retrieves the post ID associated with a given Salesforce record ID.
	 *
	 * @param string $salesforce_record_id The Salesforce record ID used to query the post.
	 * @param string $post_type The post type to search for. Defaults to 'any'.
	 *
	 * @return int|false The post ID if found, or false if no post is associated with the given Salesforce record ID.
	 */
	public static function find_post_id_by_salesforce_record_id( string $salesforce_record_id, string $post_type = '' ): int|false {
		$salesforce_record_id = sanitize_text_field( $salesforce_record_id );

		$query = new \WP_Query(
			array(
				'post_type'              => ! empty( $post_type ) ? $post_type : 'any',
				'post_status'            => 'attachment' === $post_type ? 'inherit' : 'any',
				'meta_query'             => array(
					'relation' => 'OR',
					array(
						'key'     => '_salesforce_object_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
					array(
						'key'     => '_production_salesforce_record_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
					array(
						'key'     => '_sandbox_salesforce_record_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
				),
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
				'suppress_filters'       => 'tribe_events' === $post_type, // suppress the filters that run with the events calendar
				'tec_events_ignore'      => true,
			)
		);

		if ( $query->have_posts() ) {
			return (int) $query->get_posts()[0];
		}

		return false;
	}

	/**
	 * Finds the attachment ID associated with a given Salesforce record ID.
	 *
	 * @param string $salesforce_record_id The Salesforce record ID to search for.
	 *
	 * @return int|false The attachment ID if found, or false if no attachment matches the given Salesforce record ID.
	 */
	public static function find_attachment_by_salesforce_record_id( string $salesforce_record_id ): int|false {
		$salesforce_record_id = sanitize_text_field( $salesforce_record_id );
		$query                = new \WP_Query(
			array(
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'meta_query'             => array(
					'relation' => 'OR',
					array(
						'key'     => '_salesforce_object_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
					array(
						'key'     => '_production_salesforce_record_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
					array(
						'key'     => '_sandbox_salesforce_record_id',
						'value'   => $salesforce_record_id,
						'compare' => '=',
					),
				),
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
			)
		);

		if ( $query->have_posts() ) {
			return (int) $query->posts[0];
		}

		return false;
	}

	/**
	 * Finds the term ID associated with a given Salesforce record ID.
	 *
	 * @param string $salesforce_record_id The Salesforce record ID to search for.
	 * @param string|null $taxonomy             Optional. Limit the search to a specific taxonomy.
	 *                                     Leave empty to search across all taxonomies.
	 *
	 * @return int|false The term ID if found, or false if no term matches the given Salesforce record ID.
	 */
	public static function find_term_by_salesforce_record_id( string $salesforce_record_id, string|null $taxonomy = '' ): int|false {
		$salesforce_record_id = sanitize_text_field( $salesforce_record_id );

		$args = array(
			'meta_query'             => array(
				'relation' => 'OR',
				array(
					'key'     => '_salesforce_object_id',
					'value'   => $salesforce_record_id,
					'compare' => '=',
				),
				array(
					'key'     => '_production_salesforce_record_id',
					'value'   => $salesforce_record_id,
					'compare' => '=',
				),
				array(
					'key'     => '_sandbox_salesforce_record_id',
					'value'   => $salesforce_record_id,
					'compare' => '=',
				),
			),
			'number'                 => 1,
			'hide_empty'             => false,
			'fields'                 => 'ids',
			'update_term_meta_cache' => false,
		);

		if ( ! empty( $taxonomy ) ) {
			$args['taxonomy'] = $taxonomy;
		}

		$query = new \WP_Term_Query( $args );

		if ( ! empty( $query->terms ) ) {
			return (int) $query->terms[0];
		}

		return false;
	}

	/**
	 * Get the Salesforce environment (live or sandbox).
	 *
	 * @return string
	 */
	public static function get_sf_environment(): string {
		return WPSettings::get_sf_mode();
	}

	/**
	 * Retrieves the value of the specified field the Salesforce object array.
	 *
	 * Get fields from a Salesforce record object array.
	 *
	 * @param array $data The associative array containing the data.
	 * @param string $field The key of the field to retrieve from the array.
	 *
	 * @return string The value of the specified field.
	 */
	public static function get_field( array $data, string $field ): string {
		return $data[ $field ];
	}
}
