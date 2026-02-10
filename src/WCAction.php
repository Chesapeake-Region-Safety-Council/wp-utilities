<?php
declare(strict_types=1);
namespace CRSC\WPUtilities;

class WCAction {
	/**
	 * Determines if the current request is an AJAX action.
	 *
	 * @return bool True if the request is an AJAX action, false otherwise.
	 */
	public static function is_ajax_action(): bool {
		return defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['wc-ajax'] );
	}

	/**
	 * Checks if the current AJAX action is an "add to cart" request.
	 *
	 * @return bool True if the current request is an "add to cart" AJAX action, false otherwise.
	 */
	public static function is_add_to_cart_ajax_action(): bool {
		return self::is_ajax_action() && 'add_to_cart' === $_REQUEST['wc-ajax'];
	}

	/**
	 * Determines if the current request is a "Add to Cart" action via WooCommerce's REST API.
	 *
	 * This method checks if the current request matches the specific route for
	 * adding an item to the cart through WooCommerce's REST API and verifies it
	 * is a REST API request.
	 *
	 * @return bool Returns true if the current request is a "Add to Cart" REST action, otherwise false.
	 */
	public static function is_add_to_cart_rest_action(): bool {
		global $wp;
		$is_rest_rote = false;

		// test if we are hitting the woocommerce store cart/add-item route
		if ( function_exists( '\WC' ) && str_contains( $wp->request, 'wc/store' ) && str_contains( $wp->request, 'cart/add-item' ) ) {
			$is_rest_rote = true;
		}

		return defined( 'REST_REQUEST' ) && REST_REQUEST && $is_rest_rote;
	}

	/**
	 * Retrieves the draft order from the session if available. Useful if a darft order has been created and we
	 * need to access it to attach information to it.
	 *
	 * @return \WC_Order|null The draft order object if found, or null if not available.
	 */
	public static function get_draft_order(): \WC_Order|null {
		if ( WC()->session ) {
			$order_id = WC()->session->get( 'store_api_draft_order' );

			if ( ! empty( $order_id ) ) {
				$order    = wc_get_order( $order_id );

				if ( $order instanceof \WC_Order ) {
					return $order;
				}
			}
		}

		return null;
	}
}
