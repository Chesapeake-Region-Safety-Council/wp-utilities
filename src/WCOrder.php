<?php
declare( strict_types=1 );

namespace CRSC\WPUtilities;

class WCOrder {
	/**
	 * Determines if a given WooCommerce order is a local pickup order.
	 *
	 * @param \WC_Order $order WooCommerce order object to check for local pickup shipping method(s).
	 *
	 * @return bool True if the order includes a local pickup shipping method, false otherwise.
	 */
	public static function is_local_pickup_order( \WC_Order $order ): bool {
		$shipping_items = $order->get_items( 'shipping' );

		if ( empty( $shipping_items ) ) {
			return false;
		}

		foreach ( $shipping_items as $shipping_item ) {
			$item_name = $shipping_item->get_name();
			$method_title = method_exists( $shipping_item, 'get_method_title' ) ? (string) $shipping_item->get_method_title() : '';

			if ( str_contains( strtolower( $item_name ), 'pickup' ) || str_contains( strtolower( $method_title ), 'pickup' ) ) {
				return true;
			}

			$method_id = method_exists( $shipping_item, 'get_method_id' ) ? (string) $shipping_item->get_method_id() : '';
			if ( ! empty( $method_id ) && is_string( $method_id ) && ( str_contains( $method_id, 'pickup' ) || str_contains( $method_id, 'pickup' ) ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determines if the given WooCommerce order is in a draft status.
	 *
	 * @param \WC_Order $order The WooCommerce order object to check.
	 *
	 * @return bool True if the order status is 'draft', 'wc-checkout-draft', or 'checkout-draft'; otherwise, false.
	 */
	public static function is_draft_order( \WC_Order $order ): bool {
		if ( method_exists( $order, 'get_status' ) ) {
			return 'draft' === $order->get_status() || 'wc-checkout-draft' === $order->get_status() || 'checkout-draft' === $order->get_status() || 'auto-draft' === $order->get_status();
		}

		return false;
	}
}
