<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * MaterialOrder Salesforce Model
 *
 * Represents the MaterialOrder__c object in Salesforce.
 */
class MaterialOrder extends ModelsSalesforce {

	public const string OBJECT_NAME = 'MaterialOrder__c';
	// Field API Names
	public const string FIELD_BILLING_STREET            = 'BillingAddress__Street__s';
	public const string FIELD_BILLING_CITY              = 'BillingAddress__City__s';
	public const string FIELD_BILLING_STATE_CODE        = 'BillingAddress__StateCode__s';
	public const string FIELD_BILLING_POSTAL_CODE       = 'BillingAddress__PostalCode__s';
	public const string FIELD_BILLING_COUNTRY_CODE      = 'BillingAddress__CountryCode__s';
	public const string FIELD_SHIPPING_STREET           = 'ShippingAddress__Street__s';
	public const string FIELD_SHIPPING_CITY             = 'ShippingAddress__City__s';
	public const string FIELD_SHIPPING_STATE_CODE       = 'ShippingAddress__StateCode__s';
	public const string FIELD_SHIPPING_POSTAL_CODE      = 'ShippingAddress__PostalCode__s';
	public const string FIELD_SHIPPING_COUNTRY_CODE     = 'ShippingAddress__CountryCode__s';
	public const string FIELD_CUSTOMER                  = 'Customer__c';
	public const string FIELD_COMPANY_NAME              = 'CompanyName__c';
	public const string FIELD_BILLING_FIRST_NAME        = 'BillingFirstName__c';
	public const string FIELD_BILLING_LAST_NAME         = 'BillingLastName__c';
	public const string FIELD_SHIPPING_FIRST_NAME       = 'ShippingFirstName__c';
	public const string FIELD_SHIPPING_LAST_NAME        = 'ShippingLastName__c';
	public const string FIELD_EMAIL                     = 'Email__c';
	public const string FIELD_PHONE                     = 'Phone__c';
	public const string FIELD_EXTERNAL_ORDER_ID         = 'ExternalOrderID__c';
	public const string FIELD_EXTERNAL_ORDER_URL        = 'ExternalOrderURL__c';
	public const string FIELD_ORDER_TOTAL               = 'OrderTotal__c';
	public const string FIELD_SUBTOTAL                  = 'Subtotal__c';
	public const string FIELD_SHIPPING_TOTAL            = 'ShippingTotal__c';
	public const string FIELD_TAX_TOTAL                 = 'TaxTotal__c';
	public const string FIELD_DISCOUNT_TOTAL            = 'DiscountTotal__c';
	public const string FIELD_FULFILLMENT_METHOD        = 'FulfillmentMethod__c';
	public const string FIELD_PAYMENT_STATUS            = 'PaymentStatus__c';
	public const string FIELD_PAYMENT_METHOD            = 'PaymentMethod__c';
	public const string FIELD_INVOICE_NUMBER            = 'InvoiceNumber__c';
	public const string FIELD_INVOICE_URL               = 'InvoiceURL__c';
	public const string FIELD_ORDER_DATE                = 'OrderDate__c';
	public const string FIELD_TAX_EXEMPT                = 'TaxExempt__c';
	public const string FIELD_TAX_PERCENT               = 'TaxPercent__c';
	public const string FIELD_NOTES                     = 'Notes__c';
	public const string FIELD_PACKING_SLIP_URL          = 'PackingSlipURL__c';
	public const string FIELD_NSC_MEMBERSHIP_NUMBER     = 'NSCMembershipNumber__c';
	public const string FIELD_INSTRUCTOR_NUMBER         = 'InstructorNumber__c';
	public const string FIELD_INSTRUCTOR_REVIEW_STATUS  = 'InstructorPurchaseReviewStatus__c';
	public const string FIELD_ORDER_STATUS              = 'OrderStatus__c';
	public const string FIELD_PAYMENT_GATEWAY_ID        = 'PaymentGatewayTransactionID__c';
	public const string FIELD_CC_LAST_DIGITS            = 'CreditCardLastDigits__c';
	public const string FIELD_SHIPPING_TRACKING_NUMBER  = 'ShippingTrackingNumber__c';
	public const string FIELD_FULFILLMENT_STATUS        = 'FulfillmentStatus__c';
	public const string FIELD_INSTRUCTOR_REVIEW_MESSAGE = 'InstructorCredentialReviewMessage__c';
	public const string FIELD_INVOICE_COMPANY_ID        = 'InvoiceCompanyID__c';
	public const string FIELD_TAX_EXEMPT_COMPANY_ID     = 'TaxExemptCompanyID__c';
	public const string FIELD_TRAINING_CENTER_ID        = 'TrainingCenterID__c';

	// Order Status Picklist Values (Field: OrderStatus__c)
	public const string STATUS_NEW                  = 'New';
	public const string STATUS_PROCESSING           = 'Processing';
	public const string STATUS_CANCELLED            = 'Cancelled';
	public const string STATUS_COMPLETED            = 'Completed';
	public const string STATUS_ON_HOLD_OTHER        = 'On Hold - Other';
	public const string STATUS_ON_HOLD_INSTRUCTOR   = 'On Hold – Instructor Verification';
	public const string STATUS_ON_HOLD_PAYMENT      = 'On Hold – Awaiting Payment';
	public const string STATUS_ON_HOLD_INVOICE      = 'On Hold – Awaiting Invoice';
	public const string STATUS_READY_FOR_PROCESSING = 'Ready for Processing';

	// Payment Status Picklist Values (Field: PaymentStatus__c)
	public const string PAYMENT_STATUS_PAID      = 'Paid';
	public const string PAYMENT_STATUS_UNPAID    = 'Unpaid';
	public const string PAYMENT_STATUS_CANCELLED = 'Cancelled';

	// Fulfillment Status Picklist Values (Field: FulfillmentStatus__c)
	public const string FULFILLMENT_STATUS_PROCESSING       = 'Processing';
	public const string FULFILLMENT_STATUS_READY_FOR_PICKUP = 'Ready for Pickup';
	public const string FULFILLMENT_STATUS_SHIPPED          = 'Shipped';
	public const string FULFILLMENT_STATUS_DELIVERED        = 'Delivered';

	// Instructor Purchase Review Status Picklist Values (Field: InstructorPurchaseReviewStatus__c)
	public const string REVIEW_STATUS_PENDING      = 'Pending';
	public const string REVIEW_STATUS_APPROVED     = 'Approved';
	public const string REVIEW_STATUS_DENIED       = 'Denied';
	public const string REVIEW_STATUS_NOT_REQUIRED = 'Not Required';

	// Fulfillment Method Values (Field: FulfillmentMethod__c)
	public const string FULFILLMENT_METHOD_SHIP           = 'Ship';
	public const string FULFILLMENT_METHOD_LOCAL_PICKUP   = 'Local Pickup';
	public const string FULFILLMENT_METHOD_VIRTUAL        = 'Virtual';
	public const string PAYMENT_METHOD_CREDIT_CARD        = 'Credit Card';
	public const string PAYMENT_METHOD_ONLINE_CREDIT_CARD = 'Online Credit Card';
	public const string PAYMENT_METHOD_INVOICE            = 'Invoice';
	public const string PAYMENT_METHOD_COD                = 'COD';

	/**
	 * Set the Salesforce material order number (Name).
	 *
	 * @param string $name Salesforce record Name.
	 * @return void
	 */
	public function set_salesforce_material_order_number( string $name ): void {
		$meta_key = '_salesforce_material_order_number';

		if ( $this->wc_object ) {
			// Object exists but is not yet saved to DB or just a WooCommerce object like order or product
			$this->wc_object->update_meta_data( $meta_key, $name );

			// if this object has a Post ID, update the object in WooCommerce's database since that mean it has been saved before
			if ( 0 !== $this->post_id ) {
				$this->wc_object->save();
			}
		} else {
			update_post_meta( $this->post_id, $meta_key, $name );
		}

		parent::set_salesforce_record_name( $name );
	}

	/**
	 * Get the Salesforce material order number (Name).
	 *
	 * @return string
	 */
	public function get_salesforce_material_order_number(): string {
		$meta_key = '_salesforce_material_order_number';

		if ( $this->wc_object && ! empty( $this->wc_object->get_meta( $meta_key, true ) ) ) {
			$name = (string) $this->wc_object->get_meta( $meta_key, true );
		}

		if ( empty( $name ) ) {
			$name = (string) get_post_meta( $this->post_id, $meta_key, true );
		}

		if ( empty( $name ) ) {
			$name = parent::get_salesforce_record_name();
		}

		return $name;
	}
}
