<?php
declare(strict_types=1);

namespace CRSC\WPUtilities;

/**
 * Trait Share
 *
 * A trait that provides a proxy interface to a Utilities instance for classes within a plugin.
 * This allows classes to use methods like register_plugin_script() as if they were their own,
 * while the actual logic resides in the Utilities class.
 */
trait Share {

	/**
	 * The Utilities instance.
	 *
	 * @var Utilities|null
	 */
	protected ?Utilities $utilities = null;

	/**
	 * Set the Utilities instance.
	 *
	 * @param Utilities $utilities The Utilities instance.
	 * @return void
	 */
	public function set_utilities( Utilities $utilities ): void {
		$this->utilities = $utilities;
	}

	/**
	 * Get the Utilities instance.
	 *
	 * @return Utilities|null
	 */
	public function get_utilities(): ?Utilities {
		return $this->utilities;
	}

	/**
	 * Proxy to Utilities::register_plugin_script()
	 */
	public function register_plugin_script( string $handle, string $path, $deps = array(), $strategy = null ): void {
		if ( $this->utilities ) {
			$this->utilities->register_plugin_script( $handle, $path, $deps, $strategy );
		}
	}

	/**
	 * Proxy to Utilities::register_plugin_style()
	 */
	public function register_plugin_style( string $handle, string $path, $deps = array(), $media = '' ): void {
		if ( $this->utilities ) {
			$this->utilities->register_plugin_style( $handle, $path, $deps, $media );
		}
	}

	/**
	 * Proxy to Utilities::get_plugin_file_uri()
	 */
	public function get_plugin_file_uri( string $file = '' ): ?string {
		return $this->utilities ? $this->utilities->get_plugin_file_uri( $file ) : null;
	}

	/**
	 * Proxy to Utilities::get_plugin_file_uri_and_version()
	 */
	public function get_plugin_file_uri_and_version( string $file = '' ): array {
		return $this->utilities ? $this->utilities->get_plugin_file_uri_and_version( $file ) : array( 'url' => '', 'version' => time() );
	}

	/**
	 * Proxy to Utilities::get_plugin_file_path()
	 */
	public function get_plugin_file_path( string $file = '' ): string {
		return $this->utilities ? $this->utilities->get_plugin_file_path( $file ) : '';
	}

	/**
	 * Proxy to Utilities::get_version()
	 */
	public function get_version(): string {
		return $this->utilities ? $this->utilities->get_version() : '1.0.0';
	}

	/**
	 * Proxy to Utilities::get_text_domain()
	 */
	public function get_text_domain(): string {
		return $this->utilities ? $this->utilities->get_text_domain() : '';
	}

	/**
	 * Proxy to Utilities::get_plugin_slug()
	 */
	public function get_plugin_slug(): string {
		return $this->utilities ? $this->utilities->get_plugin_slug() : '';
	}
}
