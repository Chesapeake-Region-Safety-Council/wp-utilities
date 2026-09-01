<?php
declare(strict_types=1);

namespace CRSC\WPUtilities;

/**
 * Trait WCSettingsHelper
 *
 * Provides helper methods to access WooCommerce settings via the static WCSettings class.
 *
 * @package CRSC\Util
 */
trait WCSettingsHelper {

	/**
	 * Retrieves the store name to be used, either from the WooCommerce POS mode setting or as the default site name.
	 */
	public function get_store_name(): string {
		return WCSettings::get_store_name();
	}

	/**
	 * Retrieves the street address of the store from the WooCommerce settings.
	 */
	public function get_store_address_street(): string {
		return WCSettings::get_store_address_street();
	}

	/**
	 * Retrieves the first line of the street address for the store from the WooCommerce settings.
	 *
	 * @return string The first line of the store's street address.
	 */
	public function get_store_street_address_street1(): string {
		return WCSettings::get_store_street_address_street1();
	}

	/**
	 * Retrieves the second line of the store address from the WooCommerce settings.
	 */
	public function get_store_address_street2(): string {
		return WCSettings::get_store_address_street2();
	}

	/**
	 * Retrieves the city of the store address from the WooCommerce settings.
	 */
	public function get_store_address_city(): string {
		return WCSettings::get_store_address_city();
	}

	/**
	 * Retrieves the store's address state in a cleaned and formatted country code representation.
	 */
	public function get_store_address_state(): string {
		return WCSettings::get_store_address_state();
	}

	/**
	 * Retrieves the store's zip code from the WooCommerce settings.
	 */
	public function get_store_address_zip(): string {
		return WCSettings::get_store_address_zip();
	}

	/**
	 * Retrieves the store's zip code from the WooCommerce settings.
	 */
	public function get_store_address_postcode(): string {
		return WCSettings::get_store_address_postcode();
	}

	/**
	 * Retrieves the store's address country code based on the WooCommerce default country setting.
	 */
	public function get_store_address_country(): string {
		return WCSettings::get_store_address_country();
	}

	/**
	 * Retrieves the phone number of the store from the WooCommerce settings.
	 */
	public function get_store_phone_number(): string {
		return WCSettings::get_store_phone_number();
	}

	/**
	 * Retrieves the email address of the store from the WooCommerce settings.
	 */
	public function get_store_email(): string {
		return WCSettings::get_store_email();
	}

	/**
	 * Clean up country and state codes from WooCommerce which might be combined as COUNTRY:STATE.
	 */
	public function get_clean_wc_country_code( string $woocommerce_setting_country_state_code ): string {
		return WCSettings::get_clean_wc_country_code( $woocommerce_setting_country_state_code );
	}
}
