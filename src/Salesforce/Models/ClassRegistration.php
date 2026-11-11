<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;
use TEC\Tickets\Flexible_Tickets\WP_Cli;

/**
 * ClassRegistration Salesforce Model
 *
 * Represents the Class__c object in Salesforce.
 */
class ClassRegistration extends ModelsSalesforce {

	public const string OBJECT_NAME = 'ClassRegistrationJunction__c';
	// Salesforce record id of the Class__c object.
	public const string FIELD_CLASS                       = 'Class__c';
	public const string FIELD_STUDENT                     = 'Student__c';
	public const string FIELD_OTI_APPROVAL                = 'OTIApproval__c';
	public const string FIELD_PAYMENT_METHOD              = 'PaymentMethod__c';
	public const string FIELD_REGISTRATION_FEE            = 'RegistrationFee__c';
	public const string FIELD_STATUS                      = 'Status__c';
	public const string FIELD_COMPANY                     = 'Company__c';
	public const string FIELD_NSC_MEMBERSHIP_NUMBER       = 'NSCMembershipNumber__c';
	public const string FIELD_GUEST_NSC_MEMBERSHIP_NUMBER = 'GuestNSCMembershipNumber__c';
	public const string FIELD_EXTERNAL_ATTENDEE_ID        = 'ExternalAttendeeID__c';
	public const string FIELD_EXTERNAL_REGISTRATION_ID    = 'ExternalRegistrationID__c';
	public const string FIELD_EXTERNAL_ORDER_ID           = 'ExternalOrderID__c';
	public const string FIELD_EXTERNAL_ORDER_URL          = 'ExternalOrderURL__c';
	public const string FIELD_PAYMENT_STATUS              = 'PaymentStatus__c';
	public const string FIELD_INVOICE_COMPANY_ID          = 'InvoiceCompanyID__c';
	public const string FIELD_INVOICE_NUMBER              = 'InvoiceNumber__c';
	public const string FIELD_PAYMENT_DATE                = 'PaymentDate__c';
	public const string FIELD_PAYMENT_GATEWAY = 'PaymentGateway__c';
	public const string FIELD_PAYMENT_GATEWAY_TRANSACTION_ID     = 'PaymentGatewayTransactionID__c';
	public const string FIELD_PAYMENT_RETRIEVAL_REFERENCE_NUMBER     = 'PaymentRetrievalReferenceNumber__c';
	public const string FIELD_PAYMENT_BATCH_NUMBER     = 'PaymentGatewayBatchNumber__c';
	public const string FIELD_ACCOUNTING_TRANSACTION_REFERENCE = 'AccountingTransactionReference__c';
	public const string STATUS_REGISTERED                 = 'Registered';
	public const string STATUS_CANCELED                   = 'Canceled';
	public const string STATUS_INCOMPLETE                 = 'Incomplete';
	public const string STATUS_WAITLISTED                 = 'Waitlisted';
	public const string STATUS_WITHDRAWN                  = 'Withdrawn';
	public const string PAYMENT_METHOD_CREDIT_CARD        = 'Credit Card';
	public const string PAYMENT_METHOD_ONLINE_DEBIT_CARD  = 'Online Debit Card';
	public const string PAYMENT_METHOD_ACH                = 'ACH/eCheck';
	public const string PAYMENT_METHOD_INVOICE            = 'Invoice';
	public const string PAYMENT_METHOD_ONLINE_CREDIT_CARD = 'Online Credit Card';
	public const string PAYMENT_METHOD_UNPAID             = 'Unpaid';
	public const string PAYMENT_METHOD_PAID               = 'Paid';
	public const string PAYMENT_METHOD_FREE               = 'Free';
	public const string PAYMENT_METHOD_PREVIOUSLY_PAID    = 'Previously Paid';
	public const string PAYMENT_METHOD_REFUNDED           = 'Refunded';
	public const string PAYMENT_STATUS_PAID               = 'Paid';
	public const string PAYMENT_STATUS_UNPAID             = 'Unpaid';
	public const string PAYMENT_STATUS_REFUNDED           = 'Refunded';
	public const string PAYMENT_STATUS_VOIDED      = 'Voided';
	public const string PAYMENT_STATUS_FREE               = 'Free';
	public const string PAYMENT_GATEWAY_VALOR = 'Valor';
	public const string PAYMENT_GATEWAY_AUTHORIZENET = 'Authorize.NET';

	/**
	 * Get the first name of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract first name from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null First name of the attendee
	 */
	public function get_first_name( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'first_name', 'first-name' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_first_name();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the middle name of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract middle name from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Middle name of the attendee
	 */
	public function get_middle_name( array $attendee_meta = array() ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'middle_name', 'middle-name', 'middle' );

		return $this->get_attendee_meta_value( $keys, $attendee_meta );
	}

	/**
	 * Get the last name of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract last name from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Last name of the attendee
	 */
	public function get_last_name( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'last_name', 'last-name' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_last_name();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the suffix of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract suffix from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Suffix of the attendee
	 */
	public function get_suffix( array $attendee_meta = array() ): ?string {
		$keys = array( 'suffix' );

		return $this->get_attendee_meta_value( $keys, $attendee_meta );
	}

	/**
	 * Get the email of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract email from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Email of the attendee
	 */
	public function get_email( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		$keys = array( 'email' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_email();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the mobile phone number of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract mobile phone number from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Phone number of the attendee
	 */
	public function get_mobile_phone( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'mobile_phone', 'mobile-phone' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_phone();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the work phone number of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract work phone number from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Phone number of the attendee
	 */
	public function get_work_phone( array $attendee_meta = array() ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'work_phone', 'work-phone' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get if the attendee opted in to receive SMS updates.
	 *
	 * @param array $attendee_meta Attendee meta to extract SMS opt ins. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Value of the opt-in.
	 */
	public function get_sms_optin( array $attendee_meta = array() ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'opt_into_sms_class_updates', 'opt-into-sms-class-updates' );

		return $this->get_attendee_meta_value( $keys, $attendee_meta );
	}

	/**
	 * Get the street address of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract street address from. Otherwise get the attendee
	 * information from the post id of the current object.
	 *
	 * @return string|null Street address of the attendee
	 */
	public function get_street_address( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'street_address', 'street-address' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$street  = $order->get_billing_address_1();
			$street2 = $order->get_billing_address_2();

			if ( ! empty( $street ) ) {
				$street = trim( $street );
			}

			if ( ! empty( $street2 ) ) {
				$street2 = trim( $street2 );
				$street .= ' ' . $street2;
			}

			if ( ! empty( $street ) ) {
				$data = trim( $street );
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the city of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract city from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null City of the attendee
	 */
	public function get_city( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		$keys = array( 'city' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_city();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the state of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract state from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null State of the attendee
	 */
	public function get_state( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		$keys = array( 'state' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_state();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the zip code of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract zip code from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Zip code of the atendee
	 */
	public function get_zip_code( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'zip_code', 'zip-code' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_postcode();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the material shipping street address of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract street address from. Otherwise get the attendee
	 * information from the post id of the current object.
	 *
	 * @return string|null Street address of the attendee
	 */
	public function get_material_shipping_street_address( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'material_shipping_street_address', 'material-shipping-street-address' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$street  = $order->get_billing_address_1();
			$street2 = $order->get_billing_address_2();

			if ( ! empty( $street ) ) {
				$street = trim( $street );
			}

			if ( ! empty( $street2 ) ) {
				$street2 = trim( $street2 );
				$street .= ' ' . $street2;
			}

			if ( ! empty( $street ) ) {
				$data = trim( $street );
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the material shipping city of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract city from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null City of the attendee
	 */
	public function get_material_shipping_get_city( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		$keys = array( 'material_shipping_city', 'material-shipping-city' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_shipping_city();

			if ( empty( $data ) ) {
				$data = $order->get_billing_city();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the material shipping state of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract state from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null State of the attendee
	 */
	public function get_material_shipping_get_state( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		$keys = array( 'material_shipping_state', 'material-shipping-state' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_shipping_state();

			if ( empty( $data ) ) {
				$data = $order->get_billing_state();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the material shipping zip code of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract zip code from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Zip code of the atendee
	 */
	public function get_material_shipping_zip_code( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'material_shipping_zip', 'material-shipping-zip' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_shipping_postcode();

			if ( empty( $data ) ) {
				$data = $order->get_billing_postcode();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company name of the attendee from the metadata saved in the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract zip code from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Zip code of the atendee
	 */
	public function get_company( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_name', 'company-name' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_billing_company();
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company street address of the attendee from the metadata saved in the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract the company street address from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Company street address of the atendee.
	 */
	public function get_company_street_address( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_address', 'company-address' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_meta( '_crsc_billing-company-address-1' );

			if ( empty( $data ) ) {
				$data = $order->get_billing_address_1();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company street address line 2 of the attendee from the metadata saved in the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract the company street address line 2 from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Company street address line 2 of the atendee.
	 */
	public function get_company_street_address2( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_address_2', 'company-address2' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_meta( '_crsc_billing-company-address-2' );

			if ( empty( $data ) ) {
				$data = $order->get_billing_address_2();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company city of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract the company city from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Company city of the atendee
	 */
	public function get_company_city( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_city', 'company-city' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_meta( '_crsc_billing-company-city' );

			if ( empty( $data ) ) {
				$data = $order->get_billing_city();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company state of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract the company state from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Zip code of the atendee
	 */
	public function get_company_state( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_state', 'company-state' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_meta( '_crsc_billing-company-state' );

			if ( empty( $data ) ) {
				$data = $order->get_billing_state();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the company zip code of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract the company zip code from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Zip code of the atendee
	 */
	public function get_company_zip_code( array $attendee_meta = array(), ?\WC_Order $order = null ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'company_zip', 'company-zip' );

		$data = $this->get_attendee_meta_value( $keys, $attendee_meta );

		if ( empty( $data ) && $order instanceof \WC_Order ) {
			$data = $order->get_meta( '_crsc_billing-company-postcode' );

			if ( empty( $data ) ) {
				$data = $order->get_billing_postcode();
			}
		}

		return ! empty( $data ) ? $data : null;
	}

	/**
	 * Get the guest NSC membership number of the attendee from the metadata saved in for the attendee post.
	 *
	 * @param array $attendee_meta Attendee meta to extract guest NSC membership number from. Otherwise get the
	 * attendee information from the post id of the current object.
	 *
	 * @return string|null Guest NSC membership number of the attendee
	 */
	public function get_guest_nsc_membership_number( array $attendee_meta = array() ): ?string {
		// the meta keys where this data is kept. The version with the dash happens if the tickets are created using the
		// post admin. If the tickets are created with using code, then the underscore version is used.
		$keys = array( 'guest_nsc_membership_number', 'guest-nsc-membership-number' );

		return $this->get_attendee_meta_value( $keys, $attendee_meta );
	}

	/**
	 * Get the value of the first matching key from the attendee meta, falling back to the meta saved on the
	 * attendee post if no meta is passed in.
	 *
	 * @param array $keys          List of meta keys to look for, in order of priority.
	 * @param array $attendee_meta Attendee meta to extract the value from. Otherwise get the attendee information
	 * from the post id of the current object.
	 *
	 * @return string|null Value of the first matching key found in the meta.
	 */
	private function get_attendee_meta_value( array $keys, array $attendee_meta = array() ): string|int|float|null {
		$meta = $attendee_meta;
		if ( empty( $meta ) ) {
			$meta = get_post_meta( $this->post_id, '_tribe_tickets_meta', true );
		}

		if ( ! empty( $meta ) ) {
			foreach ( $keys as $key ) {
				if ( isset( $meta[ $key ] ) ) {
					$data = $meta[ $key ];

					if ( ! empty( $data ) ) {
						if ( is_string( $data ) ) {
							$data = trim( $data );
						}

						return $data;
					}
				}
			}
		}

		return null;
	}

	/**
	 * Get all the attendees attached to a WooCommerce order.
	 *
	 * Calls get_attendee_meta_from_order_item() for every product line item.
	 *
	 * @param \WC_Order $order
	 * @return array[] See get_attendee_meta_from_order_item() for the attendee structure.
	 */
	public static function get_attendees_from_order( \WC_Order $order ): array {
		$attendees = array();

		foreach ( $order->get_items() as $item ) {
			if ( ! $item instanceof \WC_Order_Item_Product ) {
				continue;
			}

			foreach ( self::get_attendee_meta_from_order_item( $order, $item ) as $attendee ) {
				$attendees[] = $attendee;
			}
		}

		return $attendees;
	}

	/**
	 * Comprehensive attendee lookup for an order line item.
	 *
	 * 1. Attendees attached to the order (tribe_tickets_get_attendees(), then the Tickets Plus WooCommerce provider).
	 * 2. If both are empty, the attendee meta Event Tickets Plus keeps in a transient before the order is placed.
	 *
	 * Each attendee is an array with: attendee_id, product_id, order_item_id, holder_name, holder_email, full_name,
	 * meta (field slug => value) and source ('order' or 'transient').
	 *
	 * @param \WC_Order      $order
	 * @param \WC_Order_Item $item
	 * @return array[]
	 */
	public static function get_attendee_meta_from_order_item( \WC_Order $order, \WC_Order_Item $item ): array {
		if ( ! $item instanceof \WC_Order_Item_Product ) {
			return array();
		}

		$result = self::get_order_attendees_for_item( $order, $item );

		if ( ! empty( $result ) ) {
			return $result;
		}

		foreach ( self::get_attendee_meta_from_transient( $item ) as $attendee_meta ) {
			if ( ! is_array( $attendee_meta ) ) {
				continue;
			}

			$meta = self::normalize_attendee_meta( $attendee_meta );

			$result[] = array(
				'attendee_id'   => 0,
				'product_id'    => (int) $item->get_product_id(),
				'order_item_id' => (int) $item->get_id(),
				'holder_name'   => '',
				'holder_email'  => '',
				'full_name'     => self::resolve_attendee_full_name( $order, $meta, (string) ( $meta['holder_name'] ?? '' ) ),
				'meta'          => $meta,
				'source'        => 'transient',
			);
		}

		return $result;
	}

	/**
	 * Get the attendees attached to the order for a line item.
	 *
	 * @param \WC_Order              $order
	 * @param \WC_Order_Item_Product $item
	 * @return array[]
	 */
	public static function get_order_attendees_for_item( \WC_Order $order, \WC_Order_Item_Product $item ): array {
		$result        = array();
		$attendees     = array();
		$order_id      = $order->get_id();
		$product_id    = $item->get_product_id();
		$order_item_id = $item->get_id();

		if ( function_exists( '\tribe_tickets_get_attendees' ) ) {
			$attendees = \tribe_tickets_get_attendees( $order_id );
		}

		if ( empty( $attendees ) && function_exists( '\tribe' ) && class_exists( '\Tribe__Tickets_Plus__Commerce__WooCommerce__Main' ) ) {
			$provider = \tribe( 'tickets-plus.commerce.woo' );
			if ( $provider instanceof \Tribe__Tickets_Plus__Commerce__WooCommerce__Main ) {
				$attendees = $provider->get_attendees_by_order_id( $order_id );
			}
		}

		if ( empty( $attendees ) || ! is_array( $attendees ) ) {
			return $result;
		}

		foreach ( $attendees as $attendee ) {
			$attendee_product_id = $attendee['product_id'] ?? null;
			$attendee_item_id    = $attendee['order_item_id'] ?? null;

			if ( ! empty( $attendee_item_id ) && (int) $attendee_item_id !== (int) $order_item_id ) {
				continue;
			}

			if ( empty( $attendee_item_id ) && (int) $attendee_product_id !== (int) $product_id ) {
				continue;
			}

			$attendee_id = (int) ( $attendee['attendee_id'] ?? 0 );
			$meta        = ! empty( $attendee['attendee_meta'] ) && is_array( $attendee['attendee_meta'] )
				? self::normalize_attendee_meta( $attendee['attendee_meta'] )
				: array();
			$full_name   = $attendee_id ? trim( (string) get_post_meta( $attendee_id, '_tribe_tickets_full_name', true ) ) : '';
			$holder_name = trim( (string) ( $attendee['holder_name'] ?? '' ) );

			if ( '' === $full_name ) {
				$full_name = self::resolve_attendee_full_name( $order, $meta, $holder_name );
			}

			$result[] = array(
				'attendee_id'   => $attendee_id,
				'product_id'    => (int) ( $attendee_product_id ?? $product_id ),
				'order_item_id' => (int) ( $attendee_item_id ?? $order_item_id ),
				'holder_name'   => $holder_name,
				'holder_email'  => trim( (string) ( $attendee['holder_email'] ?? '' ) ),
				'full_name'     => $full_name,
				'meta'          => $meta,
				'source'        => 'order',
			);
		}

		return $result;
	}

	/**
	 * Flatten attendee meta ( slug => value or slug => array( 'value' => ... ) ) to slug => value.
	 *
	 * @param array $attendee_meta
	 * @return array
	 */
	public static function normalize_attendee_meta( array $attendee_meta ): array {
		$meta = array();

		foreach ( $attendee_meta as $key => $value ) {
			if ( is_array( $value ) && array_key_exists( 'value', $value ) ) {
				$value = $value['value'];
			}

			$meta[ $key ] = $value;
		}

		return $meta;
	}

	/**
	 * Build the full name of an attendee from its meta, falling back to the holder name and then the billing name.
	 *
	 * @param \WC_Order $order
	 * @param array     $meta        Normalized attendee meta.
	 * @param string    $holder_name
	 * @return string
	 */
	public static function resolve_attendee_full_name( \WC_Order $order, array $meta, string $holder_name = '' ): string {
		$parts = array();

		// Fields created in the UI use dashes instead of underscores.
		foreach ( array( 'first_name', 'middle_name', 'last_name', 'suffix' ) as $key ) {
			$value = $meta[ $key ] ?? ( $meta[ str_replace( '_', '-', $key ) ] ?? '' );

			if ( is_scalar( $value ) && '' !== trim( (string) $value ) ) {
				$parts[] = trim( (string) $value );
			}
		}

		$name = implode( ' ', $parts );

		if ( '' === $name ) {
			$name = trim( (string) ( $meta['full_name'] ?? $meta['full-name'] ?? '' ) );
		}

		if ( '' === $name ) {
			$name = trim( $holder_name );
		}

		if ( '' === $name ) {
			$name = trim( (string) $order->get_formatted_billing_full_name() );
		}

		return $name;
	}

	/**
	 * Retrieves attendee meta stored by Event Tickets Plus in a transient before the order is placed.
	 *
	 * Events Tickets plus stores the draft attendee information in a transient instead of being attached to the order.
	 * The attendee information only gets attached to the order after the order is placed/purchased.
	 *
	 * @param \WC_Order_Item $item The order item.
	 * @return array
	 */
	public static function get_attendee_meta_from_transient( \WC_Order_Item $item ): array {
		if ( ! $item instanceof \WC_Order_Item_Product ) {
			return array();
		}

		$item_product_id = $item->get_product_id();
		$hash            = '';

		if ( class_exists( '\Tribe__Tickets_Plus__Meta__Storage' ) && method_exists( '\Tribe__Tickets_Plus__Meta__Storage', 'get_hash_cookie_key' ) && isset( $_COOKIE[ \Tribe__Tickets_Plus__Meta__Storage::get_hash_cookie_key() ] ) ) {
			$hash = (string) $_COOKIE[ \Tribe__Tickets_Plus__Meta__Storage::get_hash_cookie_key() ];
		} elseif ( isset( $_COOKIE['tribe-event-tickets-plus-meta-hash'] ) ) {
			$hash = (string) $_COOKIE['tribe-event-tickets-plus-meta-hash'];
		}

		if ( '' === $hash ) {
			return array();
		}

		$attendee_meta = get_transient( sprintf( 'tribe_tickets_meta_%s', sanitize_text_field( wp_unslash( $hash ) ) ) );

		if ( ! empty( $attendee_meta ) && is_array( $attendee_meta ) && isset( $attendee_meta[ $item_product_id ] ) && is_array( $attendee_meta[ $item_product_id ] ) ) {
			return $attendee_meta[ $item_product_id ];
		}

		return array();
	}
}
