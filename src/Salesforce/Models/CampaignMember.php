<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;
use TEC\Tickets\Flexible_Tickets\WP_Cli;

/**
 * CampaignMember Salesforce Model
 *
 * Represents the Class__c object in Salesforce.
 */
class CampaignMember extends ModelsSalesforce {

	public const string OBJECT_NAME = 'CampaignMember';
	// Salesforce record id of the Class__c object.
	public const string FIELD_CAMPAIGN                       = 'CampaignId';
	public const string FIELD_CONTACT                    = 'ContactId';
	public const string FIELD_EMAIL                = 'Email';
	public const string FIELD_FIRST_NAME              = 'FirstName';
	public const string FIELD_LAST_NAME            = 'LastName';
	public const string FIELD_STATUS                      = 'Status';
	public const string FIELD_PAYMENT_METHOD                     = 'Payment_Method__c';
	public const string FIELD_REGISTRATION_FEE       = 'Registration_Fee__c';
	public const string FIELD_EXTERNAL_ATTENDEE_ID        = 'ExternalAttendeeID__c';
	public const string FIELD_EXTERNAL_REGISTRATION_ID    = 'ExternalRegistrationID__c';
	public const string FIELD_EXTERNAL_ORDER_ID           = 'ExternalOrderID__c';
	public const string FIELD_EXTERNAL_ORDER_URL          = 'ExternalOrderURL__c';
	public const string STATUS_PLANNED                 = 'Planned';
	public const string STATUS_PAID                  = 'Paid/Completed';
	public const string STATUS_PAYMENT_OUTSTANDING                 = 'Payment Outstanding';
	public const string STATUS_WAITLISTED                 = 'Waitlisted';
	public const string STATUS_WITHDRAWN                  = 'Withdrawn';
	public const string PAYMENT_METHOD_CREDIT_CARD        = 'Credit Card';
	public const string PAYMENT_METHOD_INVOICE            = 'Invoice';
	public const string PAYMENT_METHOD_ONLINE_CREDIT_CARD = 'Online CC';
	public const string PAYMENT_METHOD_UNPAID             = 'Unpaid';
	public const string PAYMENT_METHOD_PAID               = 'Paid';
	public const string PAYMENT_METHOD_FREE               = 'Free';
	public const string PAYMENT_METHOD_CASH              = 'Cash';
	public const string PAYMENT_METHOD_CHECK              = 'Check';
	public const string PAYMENT_METHOD_ACH              = 'ACH/eCheck';
	public const string PAYMENT_METHOD_PREVIOUSLY_PAID    = 'Previously Paid';
	public const string PAYMENT_METHOD_REFUNDED           = 'Refunded';
}
