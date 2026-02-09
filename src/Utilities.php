<?php
/**
 * Utility functions that are used throughout the plugin and/or debug functions that are used during development.
 */
declare( strict_types=1 );
namespace CRSC\WPUtilities;

// exit if not loading in WordPress context but don't exit if running our PHPUnit tests
if ( ! defined( 'ABSPATH' ) && ! defined( 'PHPUNIT_TESTS_RUNNING' ) ) {
	exit;
}

/**
 * Utility functions not specific to this plugin. Used through custom plugins.
 */
class Utilities {

	private ?string $main_plugin_file = null;

	/**
	 * Constructor to initialize with a main plugin file.
	 *
	 * @param string|null $main_plugin_file The main plugin file path.
	 */
	public function __construct( ?string $main_plugin_file = null ) {
		$this->main_plugin_file = $main_plugin_file;
	}

	/**
	 * Set the main plugin file path.
	 *
	 * @param string $main_plugin_file The main plugin file path.
	 * @return void
	 */
	public function set_main_plugin_file( string $main_plugin_file ): void {
		$this->main_plugin_file = $main_plugin_file;
	}

	/**
	 * Get the main plugin file path.
	 *
	 * @return string|null
	 */
	public function get_main_plugin_file(): ?string {
		return $this->main_plugin_file;
	}

	/*
	 * ==========================================================================
	 * GENERIC STATIC UTILITIES
	 * ==========================================================================
	 */

	/**
	 * Get the full path to a wp-content/plugins folder.
	 */
	public static function get_plugin_folders_path(): string {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		global $wp_filesystem;
		$plugin_directory = WP_PLUGIN_DIR;

		try {
			if ( ! empty( $wp_filesystem ) &&
				is_callable( array( $wp_filesystem, 'wp_plugins_dir' ) ) &&
				! empty( $wp_filesystem->wp_plugins_dir() ) ) {
				$plugin_directory = $wp_filesystem->wp_plugins_dir();
			}
		} catch ( \TypeError $error ) {
		}

		return $plugin_directory;
	}

	/**
	 * Get the full path to a plugin file
	 */
	public static function get_plugin_file_full_path( string $plugin_folder_name_and_main_file ): string {
		$clean_plugin_folder_path = rtrim( self::get_plugin_folders_path(), DIRECTORY_SEPARATOR );
		return sprintf( '%s%s%s', $clean_plugin_folder_path, DIRECTORY_SEPARATOR, $plugin_folder_name_and_main_file );
	}

	/**
	 * Remove the folder path to the WP_CONTENT_DIR folder from a file path and return the relative path to the file.
	 */
	public static function get_wp_content_relative_file_path( string $path, string $remove_additional_subfolder = '' ): string {
		$wp_content_path = WP_CONTENT_DIR;

		if ( 'plugins' === $remove_additional_subfolder || 'themes' === $remove_additional_subfolder || 'uploads' === $remove_additional_subfolder ) {
			$wp_content_path = sprintf( '%s/%s', $wp_content_path, $remove_additional_subfolder );
		}

		$position = stripos( $path, $wp_content_path );

		if ( false !== $position ) {
			return substr_replace( $path, '', $position, strlen( $wp_content_path ) );
		}

		return $path;
	}

	/**
	 * Check if the external URL domain is blocked by the WP_HTTP_BLOCK_EXTERNAL constant.
	 */
	public static function is_external_domain_blocked( string $url ): bool {
		$is_url_blocked = false;

		if ( defined( '\WP_HTTP_BLOCK_EXTERNAL' ) && true === \WP_HTTP_BLOCK_EXTERNAL ) {
			$url_parts      = wp_parse_url( $url );
			$url_domain     = sprintf( '%s://%s', $url_parts['scheme'], $url_parts['host'] );
			$check_host     = new \WP_Http();
			$is_url_blocked = $check_host->block_request( $url_domain );
		}

		return $is_url_blocked;
	}

	/**
	 * Determines if external requests to the WordPress.org API are blocked.
	 *
	 * @return bool True if requests to the WordPress.org API are blocked, false otherwise.
	 */
	public static function is_wordpress_org_external_request_blocked(): bool {
		return self::is_external_domain_blocked( 'https://api.wordpress.org' );
	}

	/**
	 * Checks if the environment is in development mode based on domain, environment variables, or specific configurations.
	 *
	 * @return bool True if the environment is detected as development mode; otherwise false.
	 */
	public static function is_development_mode(): bool {
		$home_url      = home_url( '/' );
		$domain        = wp_parse_url( $home_url, PHP_URL_HOST );
		$tlds_to_check = array(
			'kinsta.cloud',
			'pantheonsite.io',
			'wpengine.com',
			'flywheelstaging.com',
			'flywheelsites.com',
			'dreamhosters.com',
			'sg-host.com',
			'lando.dev',
			'lando.site',
			'ddev.site',
			'localhost',
		);

		foreach ( $tlds_to_check as $tld ) {
			if ( str_ends_with( $domain, $tld ) ) {
				return true;
			}
		}

		if ( ( defined( '\KINSTA_DEV_ENV' ) && true === \KINSTA_DEV_ENV ) ||
			'production' !== wp_get_environment_type() ||
			( function_exists( '\wp_get_development_mode' ) && ! empty( wp_get_development_mode() ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if the current host matches common localhost patterns.
	 *
	 * Checks the hostname against a predefined list of typical localhost
	 * identifiers, such as 'localhost', '127.0.0.1', and specific development
	 * environment domains.
	 *
	 * @return bool Returns true if the current host matches a localhost pattern, otherwise false.
	 */
	public static function is_localhost(): bool {
		$tlds         = array( 'localhost', '127.0.0.1', 'ddev.site', 'lando.site' );
		$current_host = parse_url( home_url(), PHP_URL_HOST ) ?: ( $_SERVER['HTTP_HOST'] ?? '' );
		foreach ( $tlds as $tld ) {
			if ( str_contains( $current_host, $tld ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Determines if the current environment is WP-CLI.
	 *
	 * @return bool True if the environment is WP-CLI, false otherwise.
	 */
	public static function is_wp_cli_environment(): bool {
		if ( defined( '\WP_CLI' ) && \WP_CLI && ( 'cli' === php_sapi_name() ||
			'cli-server' === php_sapi_name() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieves the current WordPress version.
	 *
	 * @return string The current version of WordPress.
	 */
	public static function get_current_wordpress_version(): string {
		global $wp_version;
		return $wp_version;
	}

	/**
	 * Retrieves the list of active plugins, including sitewide plugins on a multisite network.
	 *
	 * @return array The list of active plugin file paths.
	 */
	public static function get_active_plugins(): array {
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		if ( is_multisite() ) {
			$network_plugins = get_site_option( 'active_sitewide_plugins' );

			if ( $network_plugins && is_array( $network_plugins ) ) {
				$network_plugins = array_keys( $network_plugins );
				$active_plugins  = array_merge( $active_plugins, $network_plugins );
			}
		}

		return $active_plugins;
	}

	/**
	 * Retrieves the activation status of all installed plugins.
	 *
	 * This method provides the activation state for each plugin folder,
	 * indicating whether it is network-active, active, or inactive.
	 *
	 * @return array An associative array where each key is a plugin folder name
	 *               and the corresponding value is one of 'network-active', 'active', or 'inactive'.
	 */
	public static function get_plugins_status(): array {
		$plugins = array_keys( get_plugins() );
		$status  = array();

		foreach ( $plugins as $plugin_relative_file ) {
			$plugin_folder = dirname( $plugin_relative_file );
			if ( is_multisite() && is_plugin_active_for_network( $plugin_relative_file ) ) {
				$status[ $plugin_folder ] = 'network-active';
			} elseif ( is_plugin_active( $plugin_relative_file ) ) {
				$status[ $plugin_folder ] = 'active';
			} else {
				$status[ $plugin_folder ] = 'inactive';
			}
		}

		return $status;
	}

	/**
	 * Retrieves a list of active themes, including parent and child themes if applicable.
	 *
	 * @return array An array of active theme directory names.
	 */
	public static function get_active_themes(): array {
		$theme  = wp_get_theme();
		$themes = array();

		if ( ! empty( $theme ) ) {
			$parent_theme    = $theme->parent();
			$theme_path_info = explode( DIRECTORY_SEPARATOR, $theme->get_stylesheet_directory() );
			$themes[]        = end( $theme_path_info );

			if ( ! empty( $parent_theme ) ) {
				$parent_theme_path_info = explode( DIRECTORY_SEPARATOR, $parent_theme->get_template_directory() );
				$themes[]               = end( $parent_theme_path_info );
			}
		}

		return $themes;
	}

	public static function does_file_exists( $file_path ): bool {
		if ( empty( $file_path ) || ! is_string( $file_path ) ) {
			return false;
		}

		// First try native PHP file_exists
		if ( file_exists( $file_path ) ) {
			return true;
		}

		// Fallback to WordPress Filesystem API for FTP systems
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		global $wp_filesystem;
		if ( ! empty( $wp_filesystem ) && is_callable( array( $wp_filesystem, 'exists' ) ) ) {
			return $wp_filesystem->exists( $file_path );
		}

		return false;
	}

	/**
	 * Determine if WordPress debug mode is effectively enabled.
	 *
	 * Debug mode is considered ON if ANY of the following are true:
	 * - WP_DEBUG is true
	 * - SCRIPT_DEBUG is true
	 * - WP_ENVIRONMENT_TYPE is not 'production'
	 * - wp_get_environment_type() exists and is not 'production'
	 * - WP_DEBUG_DISPLAY is true
	 * - WP_DEBUG_LOG is true
	 *
	 * @return bool
	 */
	public static function is_wp_debug_mode_enabled(): bool {

		if ( defined( '\WP_DEBUG' ) && \WP_DEBUG ) {
			return true;
		}

		if ( defined( '\SCRIPT_DEBUG' ) && \SCRIPT_DEBUG ) {
			return true;
		}

		if ( function_exists( '\wp_get_environment_type' ) ) {
			if ( 'production' !== wp_get_environment_type() ) {
				return true;
			}
		}

		if ( defined( '\WP_ENVIRONMENT_TYPE' ) ) {
			if ( 'production' !== \WP_ENVIRONMENT_TYPE ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determines whether WP_DEBUG_LOG is enabled.
	 *
	 * Checks if WP_DEBUG is active, the WP_DEBUG_LOG constant is defined,
	 * and its value evaluates to true.
	 *
	 * @return bool True if WP_DEBUG_LOG is enabled, false otherwise.
	 */
	public static function is_wp_debug_log_enabled(): bool {
		if ( self::is_wp_debug_mode_enabled() && defined( '\WP_DEBUG_LOG' ) && \WP_DEBUG_LOG ) {
			return true;
		}

		return false;
	}

	/**
	 * Logs a var_dump of the provided object or value to the error log.
	 *
	 * @param mixed|null $object_analyze The object or value to be analyzed and logged. Defaults to null.
	 *
	 * @return void
	 */
	public static function var_dump_error_log( $object_analyze = null ): void {
		if ( empty( ob_get_level() ) || 0 === ob_get_level() ) {
			ob_start();
			var_dump( $object_analyze );
			$contents = ob_get_contents();
			ob_end_clean();
		} else {
			$contents = self::output_buffering_cast( $object_analyze );
		}
		error_log( $contents );
	}

	/**
	 * Logs a detailed representation of a variable to the error log.
	 *
	 * @param mixed|null $object_analyze The variable to be analyzed and logged. If null, no output will be logged.
	 *
	 * @return void
	 */
	public static function print_r_error_log( $object_analyze = null ): void {
		if ( empty( ob_get_level() ) || 0 === ob_get_level() ) {
			$contents = print_r( $object_analyze, true );
		} else {
			$contents = self::output_buffering_cast( $object_analyze );
		}
		error_log( $contents );
	}

	/**
	 * Logs the debug backtrace information to the error log.
	 *
	 * This method captures the current debug backtrace, processes it
	 * based on the output buffering state, and logs the resulting
	 * backtrace information to the error log.
	 *
	 * @return void
	 */
	public static function debug_backtrace_error_log(): void {
		if ( empty( ob_get_level() ) || 0 === ob_get_level() ) {
			ob_start();
			debug_print_backtrace();
			$contents = ob_get_contents();
			ob_end_clean();
		} else {
			$contents = self::output_buffering_cast( debug_backtrace() );
		}
		if ( is_null( $contents ) ) {
			$contents = '';
		}
		error_log( $contents );
	}

	/**
	 * Processes a given input and returns a human-readable string representation of its type
	 * and content, considering output buffering limitations.
	 *
	 * @param mixed $object_analyze The input to be analyzed and converted to a string.
	 *
	 * @return string A message describing the input type and content, transformed for easier logging.
	 */
	public static function output_buffering_cast( $object_analyze ): string {
		if ( is_string( $object_analyze ) ) {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. This thing passed to the error_log function is a string:) ' . $object_analyze;
		} elseif ( is_numeric( $object_analyze ) ) {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. This thing passed to the error_log function is something that is numeric:) ' . $object_analyze;
		} elseif ( is_array( $object_analyze ) ) {
			$json = wp_json_encode( $object_analyze );
			if ( empty( $json ) ) {
				$json = serialize( $object_analyze );
			}
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. This thing passed to the error_log function is a an array. We are converting it to a JSON string though for easier reading in the error_log:) ' . $json;
		} elseif ( is_object( $object_analyze ) ) {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log). This thing passed to the error_log function is a an object. We are converting it to a serialized string though for easier reading in the error_log:) ' . wp_json_encode( $object_analyze );
		} elseif ( is_null( $object_analyze ) ) {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. This thing passed to the error_log function is a null. Returning an empty string)';
		} elseif ( empty( $object_analyze ) ) {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. This thing passed to the error_log function is something that evaluates to empty returning an empty string)';
		} else {
			return '(NOTE: output buffering is on, so we cannot var_dump to the error log. We have no idea what this thing passed is and we cannot convert it to a string, so we are just returning nothing. Try turning off output buffering and try var_dump again)';
		}
	}

	/**
	 * Extracts the plugin folder name from the given plugin file name.
	 *
	 * @param string $plugin_folder_file_name The name of the plugin file including its path.
	 *
	 * @return string The extracted plugin folder name.
	 */
	public static function extract_plugin_folder_name_by_plugin_file_name( string $plugin_folder_file_name ): string {
		$plugin_folder_name = dirname( $plugin_folder_file_name );
		if ( empty( $plugin_folder_name ) || '.' === $plugin_folder_name ) {
			$plugin_folder_name = $plugin_folder_file_name;
		}
		return $plugin_folder_name;
	}

	/**
	 * Recursively trims whitespace from strings within an array.
	 *
	 * @param array $dirty_array The input array potentially containing strings or nested arrays needing trimming.
	 *
	 * @return array The array with all strings trimmed of whitespace.
	 */
	public static function recursive_trim( array $dirty_array ): array {
		$clean_array = $dirty_array;
		foreach ( $clean_array as $key => $value ) {
			if ( is_array( $value ) ) {
				$clean_array[ $key ] = self::recursive_trim( $value );
			} elseif ( is_string( $value ) ) {
				$clean_array[ $key ] = trim( $value );
			}
		}
		return $clean_array;
	}

	/**
	 * Generates a standardized REST API response.
	 *
	 * @param array $data The response data to include. Defaults to an empty array.
	 * @param string $message A message providing additional context or feedback. Defaults to an empty string.
	 * @param bool $error Indicates whether the response represents an error. Defaults to false.
	 * @param int $status The HTTP status code for the response. Defaults to 200.
	 * @param array $headers An array of headers to include in the response. Defaults to an empty array.
	 *
	 * @return \WP_REST_Response A WordPress REST API response object containing the specified data, message, error status, HTTP status code, and headers.
	 */
	public static function rest_response( array $data = array(), string $message = '', bool $error = false, int $status = 200, array $headers = array() ): \WP_REST_Response {
		$data = is_array( $data ) ? $data : array();
		if ( empty( $message ) ) {
			$message = $error ? 'Request failed' : 'Success';
		}
		$payload = array(
			'error'   => (bool) $error,
			'message' => (string) $message,
			'data'    => $data,
		);
		return new \WP_REST_Response( $payload, $status, $headers );
	}

	/**
	 * Generates a REST API error response.
	 *
	 * @param string $message The error message to include in the response.
	 * @param int $status The HTTP status code for the error response. Defaults to 400.
	 * @param array $data Additional data to be included in the error response. Defaults to an empty array.
	 *
	 * @return \WP_REST_Response The generated REST API error response.
	 */
	public static function rest_error( string $message, int $status = 400, array $data = array() ): \WP_REST_Response {
		return self::rest_response( $data, $message, true, $status );
	}

	/**
	 * Checks if the provided value is an integer within the range of 200 to 299 (inclusive). Useful for external API
	 * HTTP status codes that could return a 200, 201, 204.
	 *
	 * @param mixed $value The value to be validated as an integer within the specified range.
	 *
	 * @return int|false Returns the integer value if it is within the range, or false if it is not.
	 */
	public static function within_200_range( mixed $value ): int|false {
		$options = array(
			'options' => array(
				'min_range' => 200,
				'max_range' => 299,
			),
		);
		return filter_var( $value, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Validates whether the given value is an integer within the range of 300 to 399. Useful for external API HTTP
	 * status codes that could return a 301, or 302 code.
	 *
	 * @param mixed $value The value to validate.
	 *
	 * @return int|false Returns the validated integer if within range, or false otherwise.
	 */
	public static function within_300_range( mixed $value ): int|false {
		$options = array(
			'options' => array(
				'min_range' => 300,
				'max_range' => 399,
			),
		);
		return filter_var( $value, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Validates whether a given value is an integer within the range 400 to 499. Useful for external API HTTP status
	 * codes that could return a 400, 401, 403, 404.
	 *
	 * @param mixed $value The value to be validated.
	 *
	 * @return int|false Returns the validated integer if within the range, or false if it is not.
	 */
	public static function within_400_range( mixed $value ): int|false {
		$options = array(
			'options' => array(
				'min_range' => 400,
				'max_range' => 499,
			),
		);
		return filter_var( $value, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Validates if the given value is an integer within the range of 500 to 999. Useful for external API HTTP status
	 * codes that could return a 500, 502, 503, 504, 505, or any status code that indicates something is broken or
	 * not working properly.
	 *
	 * @param mixed $value The value to be validated.
	 *
	 * @return int|false Returns the validated integer if it falls within the range, or false otherwise.
	 */
	public static function within_500_range( mixed $value ): int|false {
		$options = array(
			'options' => array(
				'min_range' => 500,
				'max_range' => 999,
			),
		);
		return filter_var( $value, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Validates if the given value is an integer within the range of 400 to 999. Useful for external API HTTP status
	 * codes that could return a 400- 500, 502, 503, 504, 505, or any status code that indicates something is broken or
	 * not working properly.
	 *
	 * @param mixed $value The value to be validated.
	 *
	 * @return int|false Returns the validated integer if it falls within the range, or false otherwise.
	 */
	public static function within_error_range( mixed $value ): int|false {
		if ( self::within_400_range( $value) || self::within_500_range( $value ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Sanitizes a string representing a monetary value by removing any non-numeric and non-decimal characters.
	 * Ensures the result has a maximum of two decimal places.
	 *
	 * @param string $string The input string representing a monetary value, which may contain unwanted characters.
	 *
	 * @return string A sanitized string containing only numeric characters and one decimal point,
	 *                with a maximum of two decimal places.
	 */
	public static function sanitize_money_string( string $string ): string {
		$clean = preg_replace( '/[^0-9.]/', '', $string );
		if ( strpos( $clean, '.' ) !== false ) {
			$parts        = explode( '.', $clean );
			$integer_part = $parts[0];
			$decimal_part = str_pad( substr( $parts[1] ?? '', 0, 2 ), 2, '0' );
			$clean        = $integer_part . '.' . $decimal_part;
		}
		return $clean;
	}

	/**
	 * Searches for partial string matches in an array and returns matching elements or their keys.
	 *
	 * @param array $search_array The array to search in.
	 * @param string $search_string The partial string to search for within the array values.
	 * @param bool $case_sensitive Optional. Whether the search should be case-sensitive. Defaults to false.
	 * @param bool $return_keys Optional. Whether to return the keys of matching elements instead of their values. Defaults to false.
	 *
	 * @return array An array of matching values or keys, depending on the value of $return_keys.
	 */
	public static function search_partial_string_in_array( array $search_array, string $search_string, bool $case_sensitive = false, bool $return_keys = false ): array {
		$found_matches = array();
		foreach ( $search_array as $key => $value ) {
			$string_value = is_string( $value ) ? $value : (string) $value;
			$found        = $case_sensitive ? str_contains( $string_value, $search_string ) : stripos( $string_value, $search_string ) !== false;
			if ( $found ) {
				$found_matches[] = $return_keys ? $key : $value; }
		}
		return $found_matches;
	}

	/**
	 * Determines if the current request is a WordPress GraphQL request.
	 *
	 * This method checks the query parameters of the current request URL to identify if it contains a "graphql" parameter.
	 *
	 * @return bool Returns true if the current request is a WordPress GraphQL request, false otherwise.
	 */
	public static function is_wp_graphql_request(): bool {
		$params = wp_parse_url( home_url( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_QUERY );
		return ! empty( $params ) && str_contains( $params, 'graphql' );
	}

	/**
	 * Determines the current site based on the home URL.
	 *
	 * This method analyzes the site's home URL to identify and return
	 * a specific site identifier, such as 'oshamidatlantic',
	 * 'crscsafetyconference', or 'chesapeake'.
	 *
	 * @return string The identifier of the current site.
	 */
	public static function which_site(): string {
		$home_url = home_url( '/' );
		if ( str_contains( $home_url, 'oshamidatlantic' ) ) {
			return 'oshamidatlantic'; }
		if ( str_contains( $home_url, 'crscsafetyconference' ) ) {
			return 'crscsafetyconference'; }
		return 'chesapeake';
	}

	/**
	 * Determines if the current site matches the 'oshamidatlantic' keyword.
	 *
	 * @return bool Returns true if the site identifier contains 'oshamidatlantic', otherwise false.
	 */
	public static function is_oshamidatlantic(): bool {
		return str_contains( self::which_site(), 'oshamidatlantic' ); }

	/**
	 * Determines if the current site is associated with Chesapeake.
	 *
	 * @return bool Returns true if the site identifier contains 'chesapeake', otherwise false.
	 */
	public static function is_chesapeake(): bool {
		return str_contains( self::which_site(), 'chesapeake' ); }

	/**
	 * Determines if the current site is related to a safety conference.
	 *
	 * This method checks if the site identifier contains the substring 'crscsafetyconference'.
	 *
	 * @return bool Returns true if the site is identified as a safety conference, false otherwise.
	 */
	public static function is_safety_conference(): bool {
		return str_contains( self::which_site(), 'crscsafetyconference' ); }

	/**
	 * Retrieves the name of the site as defined in the WordPress settings.
	 *
	 * @return string The name of the site.
	 */
	public static function get_site_name(): string {
		return get_bloginfo( 'name' ); }

	/**
	 * Retrieves the inner HTML of the specified DOM element as a string.
	 *
	 * @param \DOMElement $element The DOM element whose inner HTML is to be retrieved.
	 *
	 * @return string The inner HTML of the provided DOM element.
	 */
	public static function get_inner_html( \DOMElement $element ): string {
		$inner_html = '';
		foreach ( $element->childNodes as $child ) {
			$inner_html .= $element->ownerDocument->saveHTML( $child );
		}
		return $inner_html;
	}

	/**
	 * Replaces the content of the specified DOM element with the provided HTML string.
	 *
	 * @param \DOMElement $element The DOM element whose inner HTML is to be set.
	 * @param string $html The HTML string to set as the inner HTML of the element.
	 *
	 * @return void This method does not
	 */
	public static function set_inner_html( \DOMElement $element, string $html ): void {
		$fragment = $element->ownerDocument->createDocumentFragment();
		$fragment->appendXML( $html );
		while ( $element->hasChildNodes() ) {
			$element->removeChild( $element->firstChild ); }
		$element->appendChild( $fragment );
	}

	/**
	 * Retrieves the IP address of the client making the request. Could be faked but we just want something.
	 *
	 * @return string The client's IP address. Returns values from HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR, or REMOTE_ADDR, depending on availability.
	 */
	public static function get_request_ip_address(): string {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return (string) $_SERVER['HTTP_CLIENT_IP'];
		}

		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return (string) $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		return (string) ( $_SERVER['REMOTE_ADDR'] ?? '' );
	}

	/**
	 * Retrieves the geolocation information of the request, such as city, region, country, and postal code.
	 *
	 * This method uses server-provided GEOIP variables if available and falls back to a remote API call for
	 * additional geolocation details based on the request's IP address.
	 *
	 * @return array{city: string, region: string, country: string, postal_code: string} An associative array containing the city, region, country, and postal code.
	 */
	public static function get_request_geolocation(): array {
		$ip_address  = self::get_request_ip_address();
		$city        = '';
		$region      = '';
		$country     = '';
		$postal_code = '';

		if ( isset( $_SERVER['GEOIP_COUNTRY_NAME'] ) ) {
			$country = sanitize_text_field( $_SERVER['GEOIP_COUNTRY_NAME'] );
		}
		if ( isset( $_SERVER['GEOIP_REGION'] ) ) {
			$region = sanitize_text_field( $_SERVER['GEOIP_REGION'] );
		}
		if ( isset( $_SERVER['GEOIP_CITY'] ) ) {
			$city = sanitize_text_field( $_SERVER['GEOIP_CITY'] );
		}
		if ( isset( $_SERVER['GEOIP_POSTAL_CODE'] ) ) {
			$postal_code = sanitize_text_field( $_SERVER['GEOIP_POSTAL_CODE'] );
		}

		if ( ! empty( $ip_address ) && empty( $country ) ) {
			$url      = sprintf( 'https://ipapi.co/%s/json', $ip_address );
			$response = wp_remote_get( $url );
			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( is_array( $data ) ) {
					$country     = sanitize_text_field( $data['country_name'] ?? '' );
					$region      = sanitize_text_field( $data['region'] ?? '' );
					$city        = sanitize_text_field( $data['city'] ?? '' );
					$postal_code = sanitize_text_field( $data['postal'] ?? '' );
				}
			}

			if ( empty( $country ) ) {
				$url      = sprintf( 'https://get.geojs.io/v1/ip/geo/%s.json', $ip_address );
				$response = wp_remote_get( $url );
				if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
					$data = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( is_array( $data ) ) {
						$country = sanitize_text_field( $data['country'] ?? '' );
						$region  = sanitize_text_field( $data['region'] ?? '' );
						$city    = sanitize_text_field( $data['city'] ?? '' );
					}
				}
			}
		}

		return array(
			'city'        => $city,
			'region'      => $region,
			'country'     => $country,
			'postal_code' => $postal_code,
		);
	}

	/**
	 * Get the location data for a US based zip code using the Zippopotam.us API.
	 *
	 * @param string $zipcode The US based zip code to get data for.
	 *
	 * @return array{city: string, region: string, region_abbr: string, country: string, country_abbr: string, postal_code: string, latitude: string, longitude: string} The location data for the zip code or an empty array if not found.
	 */
	public static function get_us_postal_code_data( string $zipcode ): array {
		if ( empty( $zipcode ) ) {
			return array();
		}

		// Sanitize and normalize ZIP code (5-digit or ZIP+4)
		$zipcode = preg_replace( '/[^0-9\-]/', '', $zipcode );

		if ( ! preg_match( '/^\d{5}(-\d{4})?$/', $zipcode ) ) {
			return array();
		}

		$url = sprintf( 'https://api.zippopotam.us/us/%s', rawurlencode( $zipcode ) );

		$response = wp_remote_get(
			$url,
			array(
				'timeout'   => 10,
				'headers'   => array( 'Accept' => 'application/json' ),
				'sslverify' => true,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array();
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $data ) || empty( $data['places'][0] ) ) {
			return array();
		}

		// Normalize the response into something predictable
		$place = $data['places'][0];

		$result = array(
			'postal_code'   => $data['post code'] ?? '',
			'country'   => $data['country'] ?? '',
			'country_abbr' => $data['country abbreviation'] ?? '',
			'region'     => $place['state'] ?? '',
			'region_abbr'=> $place['state abbreviation'] ?? '',
			'city'      => $place['place name'] ?? '',
			'latitude'  => $place['latitude'] ?? '',
			'longitude' => $place['longitude'] ?? '',
		);

		return $result;
	}

	/**
	 * Determines if the current request or a specific IP address is from the United States.
	 *
	 * @param string $ip_address Optional. The IP address to check. If empty, the current request's IP is used.
	 *
	 * @return bool True if the IP address is detected as being from the United States, false otherwise.
	 */
	public static function is_us_based_ip( string $ip_address = '' ): bool {
		if ( empty( $ip_address ) ) {
			$ip_address = self::get_request_ip_address();
		}

		if ( empty( $ip_address ) ) {
			return false;
		}

		// check server variables first
		if ( isset( $_SERVER['GEOIP_COUNTRY_CODE'] ) && 'US' === $_SERVER['GEOIP_COUNTRY_CODE'] ) {
			return true;
		}

		$url      = sprintf( 'https://ipapi.co/%s/json', $ip_address );
		$response = wp_remote_get( $url );
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $data['country_code'] ) && 'US' === $data['country_code'] ) {
				return true;
			}
		}

		// fallback to geojs
		$url      = sprintf( 'https://get.geojs.io/v1/ip/geo/%s.json', $ip_address );
		$response = wp_remote_get( $url );
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( isset( $data['country_code'] ) && 'US' === $data['country_code'] ) {
				return true;
			}
		}

		return false;
	}

	/*
	 * ==========================================================================
	 * PLUGIN-SPECIFIC INSTANCE METHODS (Formerly in Share.php)
	 * ==========================================================================
	 */

	/**
	 * Retrieves the text domain of the plugin.
	 *
	 * @return string The text domain of the plugin, or a default value if not found.
	 */
	public function get_text_domain(): string {
		$main_file = $this->get_main_plugin_file();
		if ( ! $main_file ) {
			return 'crsc-wp-utilities'; }
		$data = get_plugin_data( $main_file, false );
		return $data['TextDomain'] ?? 'crsc-wp-utilities';
	}

	/**
	 * Retrieves the plugin slug.
	 *
	 * @return string The slug of the plugin. Defaults to 'crsc-wp-utilities' if the main plugin file is not set.
	 */
	public function get_plugin_slug(): string {
		$main_file = $this->get_main_plugin_file();
		if ( ! $main_file ) {
			return 'crsc-wp-utilities'; }
		return basename( dirname( $main_file ) );
	}

	/**
	 * Retrieves the version of the plugin.
	 *
	 * @return string The version number of the plugin. Defaults to '1.0.0' if the main plugin file is not available or version information is missing.
	 */
	public function get_version(): string {
		$main_file = $this->get_main_plugin_file();
		if ( ! $main_file ) {
			return '1.0.0'; }
		$data = get_plugin_data( $main_file, false );
		return $data['Version'] ?? '1.0.0';
	}

	/**
	 * Retrieves the folder path of this plugin.
	 *
	 * @return string The absolute path to the plugin's directory.
	 */
	public function get_this_plugin_folder_path(): string {
		return dirname( $this->get_main_plugin_file() ?? '' );
	}

	/**
	 * Retrieves the folder name of the current plugin.
	 *
	 * @return string The name of the folder containing the current plugin.
	 */
	public function get_this_plugin_folder_name(): string {
		return basename( $this->get_this_plugin_folder_path() );
	}

	/**
	 * Retrieves the URI of a specific file within the plugin directory.
	 *
	 * @param string $file The relative path of the file within the plugin directory. Defaults to an empty string.
	 *
	 * @return string|null The URI of the file if it exists, or null if the file does not exist or the main plugin file is unavailable.
	 */
	public function get_plugin_file_uri( string $file = '' ): ?string {
		$main_file = $this->get_main_plugin_file();
		if ( ! $main_file ) {
			return null; }
		$file = ltrim( $file, '/' );
		if ( empty( $file ) ) {
			return plugin_dir_url( $main_file ); }
		if ( file_exists( plugin_dir_path( $main_file ) . $file ) ) {
			return plugin_dir_url( $main_file ) . $file;
		}
		return null;
	}

	/**
	 * Retrieves the URI and version of a plugin file.
	 *
	 * This method determines the appropriate file (minified or unminified) based on the environment
	 * and returns its URL and version. If the main plugin file is unavailable or the provided file
	 * does not exist, a default value is returned.
	 *
	 * @param string $file The relative path of the plugin file.
	 *
	 * @return array An associative array with 'url' as the file URI and 'version' as the file's hashed version or a timestamp.
	 */
	public function get_plugin_file_uri_and_version( string $file = '' ): array {
		$main_file = $this->get_main_plugin_file();
		if ( ! $main_file ) {
			return array(
				'url'     => '',
				'version' => time(),
			); }
		$plugin_dir = plugin_dir_path( $main_file );
		$file_path  = $plugin_dir . $file;

		$should_use_minified = ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) && ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) && 'production' === wp_get_environment_type();
		if ( $should_use_minified && ! str_contains( $file, '.min.' ) && preg_match( '/\.(js|css)$/', $file, $matches ) ) {
			$extension = $matches[1];
			$min_file  = preg_replace( '/\.' . $extension . '$/', '.min.' . $extension, $file );
			if ( file_exists( $plugin_dir . $min_file ) ) {
				$file      = $min_file;
				$file_path = $plugin_dir . $file;
			}
		}

		return array(
			'url'     => $this->get_plugin_file_uri( $file ) ?? '',
			'version' => file_exists( $file_path ) ? sha1_file( $file_path ) : time(),
		);
	}

	/**
	 * Registers a plugin script with the specified handle and path.
	 *
	 * @param string $handle The unique identifier for the script.
	 * @param string $path The relative or absolute path to the script file.
	 * @param array $deps Optional. An array of script dependencies. Defaults to an empty array.
	 * @param string|null $strategy Optional. The loading strategy for the script. Use 'module' for module type or null for default behavior.
	 *
	 * @return void
	 */
	public function register_plugin_script( string $handle, string $path, $deps = array(), $strategy = null ): void {
		$info = $this->get_plugin_file_uri_and_version( $path );
		if ( 'module' === $strategy ) {
			wp_register_script_module( $handle, $info['url'], $deps, $info['version'] );
		} else {
			wp_register_script( $handle, $info['url'], $deps, $info['version'], $strategy );
		}
	}

	/**
	 * Registers a plugin style with WordPress.
	 *
	 * @param string $handle The handle for the registered style.
	 * @param string $path The relative path to the style file.
	 * @param array $deps Optional. An array of dependencies for the style. Default is an empty array.
	 * @param string $media Optional. The media for which this stylesheet has been defined. Default is an empty string.
	 *
	 * @return void
	 */
	public function register_plugin_style( string $handle, string $path, $deps = array(), $media = '' ): void {
		$info = $this->get_plugin_file_uri_and_version( $path );
		wp_register_style( $handle, $info['url'], $deps, $info['version'], $media );
	}

	/**
	 * Retrieves the full path to a specified file within the plugin's directory.
	 *
	 * @param string $file The relative file path within the plugin directory. Defaults to an empty string.
	 *
	 * @return string The absolute file path within the plugin's directory.
	 */
	public function get_plugin_file_path( string $file = '' ): string {
		$file = ltrim( $file, '/' );
		return $this->get_this_plugin_folder_path() . DIRECTORY_SEPARATOR . $file;
	}
}
