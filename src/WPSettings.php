<?php
declare(strict_types=1);

namespace CRSC\WPUtilities;

class WPSettings {
	/**
	 * Get the Salesforce mode (live or sandbox/test).
	 *
	 * Checks for CRSC_SALESFORCE_ENVIRONMENT constant, environment variable,
	 * or falls back to CRSC\DataBridgeSync\API\get_salesforce_client()->get_sf_mode().
	 *
	 * @return string
	 */
	public static function get_sf_mode(): string {
		if ( defined( 'CRSC_SALESFORCE_ENVIRONMENT' ) ) {
			return (string) constant( 'CRSC_SALESFORCE_ENVIRONMENT' );
		}

		$env_val = getenv( 'CRSC_SALESFORCE_ENVIRONMENT' );
		if ( false !== $env_val && '' !== $env_val ) {
			return (string) $env_val;
		}

		if ( is_callable( 'CRSC\DataBridgeSync\API\get_salesforce_client' ) ) {
			$client = \CRSC\DataBridgeSync\API\get_salesforce_client();
			if ( $client && method_exists( $client, 'get_sf_mode' ) ) {
				return $client->get_sf_mode();
			}
		}

		return 'live';
	}
}