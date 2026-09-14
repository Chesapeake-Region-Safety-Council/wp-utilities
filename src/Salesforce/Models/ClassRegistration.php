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
	public const string PAYMENT_STATUS_FREE               = 'Free';

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
}
