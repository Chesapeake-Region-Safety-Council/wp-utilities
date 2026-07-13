<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * MaterialOrderLineItemJunction Salesforce Model
 *
 * Represents the MaterialOrderLineItemJunction__c object in Salesforce.
 */
class MaterialOrderLineItemJunction extends ModelsSalesforce {
	public const string OBJECT_NAME = 'MaterialOrderLineItemJunction__c';
	// Field API Names
	public const string FIELD_MATERIAL_ORDER        = 'MaterialOrder__c';
	public const string FIELD_PRODUCT               = 'Product__c';
	public const string FIELD_SKU_SNAPSHOT          = 'SKUSnapshot__c';
	public const string FIELD_QUANTITY              = 'Quantity__c';
	public const string FIELD_PICKED_QUANTITY       = 'PickedQuantity__c';
	public const string FIELD_UNIT_PRICE            = 'UnitPrice__c';
	public const string FIELD_PRODUCT_NAME_SNAPSHOT = 'ProductNameSnapshot__c';
}
