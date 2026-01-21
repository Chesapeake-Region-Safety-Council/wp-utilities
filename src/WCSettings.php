<?php
declare(strict_types=1);

namespace CRSC\WPUtilities;

/**
 * Class WCSettings
 *
 * Provides static helper methods to access WooCommerce settings.
 *
 * @package CRSC\WPUtilities
 */
class WCSettings {

	/**
	 * Retrieves the store name to be used, either from the WooCommerce POS mode setting or as the default site name.
	 *
	 * @return string The store name retrieved from the WooCommerce POS configuration. If unavailable, returns the default blog name.
	 */
	public static function get_store_name(): string {
		$blog_name = get_bloginfo( 'name' );
		// Get the store name from WooCommerce's experimental Pont of Sale (POS) mode for the WooCommerce app.
		$pos_mode_store_name = get_option( 'woocommerce_pos_store_name' );

		if ( ! empty( $pos_mode_store_name ) ) {
			return $pos_mode_store_name;
		}

		return $blog_name;
	}

	/**
	 * Retrieves the street address of the store from the WooCommerce settings.
	 *
	 * @return string The street address of the store as configured in WooCommerce.
	 */
	public static function get_store_address_street(): string {
		return (string) get_option( 'woocommerce_store_address' );
	}

	/**
	 * Alias for get_store_address_street.
	 *
	 * @return string
	 */
	public static function get_store_street_address_street1(): string {
		return self::get_store_address_street();
	}

	/**
	 * Retrieves the second line of the store address from the WooCommerce settings.
	 *
	 * @return string The second line of the store address as configured in WooCommerce.
	 */
	public static function get_store_address_street2(): string {
		return (string) get_option( 'woocommerce_store_address_2' );
	}

	/**
	 * Retrieves the city of the store address from the WooCommerce settings.
	 *
	 * @return string The city of the store address as configured in WooCommerce.
	 */
	public static function get_store_address_city(): string {
		return (string) get_option( 'woocommerce_store_city' );
	}

	/**
	 * Retrieves the store's address state in a cleaned and formatted country code representation.
	 *
	 * @return string The cleaned and formatted state code of the store's address as configured in WooCommerce settings.
	 */
	public static function get_store_address_state(): string {
		$state = '';
		if ( function_exists( 'WC' ) ) {
			$state = WC()->countries->get_base_state();
		}

		if ( empty( $state ) ) {
			$state = self::get_clean_wc_country_code( (string) get_option( 'woocommerce_store_state' ) );
		}

		return $state;
	}

	/**
	 * Retrieves the store's zip code from the WooCommerce settings.
	 *
	 * @return string The store's zip code as configured in WooCommerce.
	 */
	public static function get_store_address_zip(): string {
		return self::get_store_address_postcode();
	}

	/**
	 * Retrieves the store's zip code from the WooCommerce settings.
	 *
	 * @return string The store's zip code as configured in WooCommerce.
	 */
	public static function get_store_address_postcode(): string {
		return (string) get_option( 'woocommerce_store_postcode' );
	}

	/**
	 * Retrieves the store's address country code based on the WooCommerce default country setting.
	 *
	 * @return string The sanitized country code for the store's address, as defined in the WooCommerce default country option.
	 */
	public static function get_store_address_country(): string {
		$country = '';
		if ( function_exists( 'WC' ) ) {
			$country = WC()->countries->get_base_country();
		}

		if ( empty( $country ) ) {
			$country = self::get_clean_wc_country_code( (string) get_option( 'woocommerce_default_country' ) );
		}

		return $country;
	}

	/**
	 * Retrieves the phone number of the store from the WooCommerce settings.
	 * If no phone number is configured, a default phone number is returned.
	 *
	 * @return string The store's phone number as configured in WooCommerce, or the default phone number.
	 */
	public static function get_store_phone_number(): string {
		// Get the phone number from the WooCommerce beta POS mode for the WooCommerce app.
		$pos_store_phone = get_option( 'woocommerce_pos_store_phone' );
		if ( empty( $pos_store_phone ) ) {
			$pos_store_phone = '800-875-4770';
		}

		return (string) $pos_store_phone;
	}

	/**
	 * Retrieves the email address of the store from the WooCommerce settings.
	 * If the Point of Sale (POS) store email is not set, the admin email is returned.
	 *
	 * @return string The store email address as configured in WooCommerce, or the admin email if no POS store email is set.
	 */
	public static function get_store_email(): string {
		// Get the phone number from the WooCommerce beta POS mode for the WooCommerce app.
		$pos_store_email = get_option( 'woocommerce_pos_store_email' );
		if ( empty( $pos_store_email ) ) {
			return (string) get_option( 'admin_email' );
		}

		return (string) $pos_store_email;
	}

	/**
	 * Clean up country and state codes from WooCommerce which might be combined as COUNTRY:STATE.
	 *
	 * @param string $woocommerce_setting_country_state_code
	 *
	 * @return string
	 */
	public static function get_clean_wc_country_code( string $woocommerce_setting_country_state_code ): string {
		if ( str_contains( $woocommerce_setting_country_state_code, ':' ) ) {
			$parts = explode( ':', $woocommerce_setting_country_state_code );
			return (string) reset( $parts );
		}
		return $woocommerce_setting_country_state_code;
	}
}
