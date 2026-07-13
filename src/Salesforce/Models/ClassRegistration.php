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
	public const string FIELD_EXTERNAL_ATTENDEE_ID        = 'ExternalAttendeeID__c';
	public const string FIELD_EXTERNAL_REGISTRATION_ID    = 'ExternalRegistrationID__c';
	public const string FIELD_EXTERNAL_ORDER_ID           = 'ExternalOrderID__c';
	public const string FIELD_EXTERNAL_ORDER_URL          = 'ExternalOrderURL__c';
	public const string STATUS_REGISTERED                 = 'Registered';
	public const string STATUS_CANCELLED                  = 'Cancelled';
	public const string STATUS_INCOMPLETE                 = 'Incomplete';
	public const string STATUS_WAITLISTED                 = 'Waitlisted';
	public const string STATUS_WITHDRAWN                  = 'Withdrawn';
	public const string PAYMENT_METHOD_CREDIT_CARD        = 'Credit Card';
	public const string PAYMENT_METHOD_INVOICE            = 'Invoice';
	public const string PAYMENT_METHOD_ONLINE_CREDIT_CARD = 'Online CC';
	public const string PAYMENT_METHOD_UNPAID             = 'Unpaid';
	public const string PAYMENT_METHOD_PAID               = 'Paid';
	public const string PAYMENT_METHOD_FREE               = 'Free';
	public const string PAYMENT_METHOD_PREVIOUSLY_PAID    = 'Previously Paid';
	public const string PAYMENT_METHOD_REFUNDED           = 'Refunded';
}
