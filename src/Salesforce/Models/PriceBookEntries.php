<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * PriceBookEntries Salesforce Model
 *
 * Represents the PricebookEntry object in Salesforce.
 */
class PriceBookEntries extends ModelsSalesforce {
	public const string OBJECT_NAME = 'PricebookEntry';
	// Field API Names
	public const string FIELD_PRICEBOOK_ID      = 'Pricebook2Id';
	public const string FIELD_UNIT_PRICE        = 'UnitPrice';
	public const string FIELD_IS_ACTIVE          = 'IsActive';
	public const string FIELD_NAME              = 'Name';
	public const string FIELD_BULK_DISCOUNT_MIN = 'BulkDiscountMinimum__c';
	public const string FIELD_BULK_DISCOUNT_MAX = 'BulkDiscountMaximum__c';
	public const string FIELD_NSC_MEMBER_PRICE  = 'NSCMemberPrice__c';
}
