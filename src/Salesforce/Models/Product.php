<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

use CRSC\WPUtilities\Salesforce\Salesforce as ModelsSalesforce;

/**
 * Product Salesforce Model
 */
class Product extends ModelsSalesforce {
	public const string OBJECT_NAME = 'Product2';
	// Field API Names
	public const string FIELD_PRODUCT_CODE                      = 'ProductCode';
	public const string FIELD_SKU                               = 'StockKeepingUnit';
	public const string FIELD_WEIGHT                            = 'Product_Weight__c';
	public const string FIELD_FAMILY                            = 'Family';
	public const string FIELD_LENGTH                            = 'Product_Length__c';
	public const string FIELD_WIDTH                             = 'Product_Width__c';
	public const string FIELD_HEIGHT                            = 'Product_Height__c';
	public const string FIELD_IS_VIRTUAL                        = 'IsVirtual__c';
	public const string FIELD_DESCRIPTION                       = 'Description';
	public const string FIELD_INSTRUCTOR_ONLY                   = 'InstructorOnlyProduct__c';
	public const string FIELD_ORDER_REVIEW_REQUIRED             = 'OrderReviewRequired__c';
	public const string FIELD_QUANTITY_ON_HAND                  = 'QuantityOnHand__c';
	public const string FIELD_LANGUAGE                          = 'ProductLanguage__c';
	public const string FIELD_SHIPS_AS_OWN_PACKAGE              = 'ShipsAsOwnPackage__c';
	public const string FIELD_IS_ON_SALE                        = 'IsOnSale__c';
	public const string FIELD_SALE_PRICE                        = 'SalePrice__c';
	public const string FIELD_FULFILLMENT_MESSAGE               = 'FulfillmentMessage__c';
	public const string FIELD_OVERRIDE_STORE_SEO_DESCRIPTION    = 'OverrideStoreSEODescription__c';
	public const string FIELD_GLOBAL_PRODUCT_IDENTIFIER         = 'GlobalProductIdentifier__c';
	public const string FIELD_BRAND                             = 'Brand__c';
	public const string FIELD_MANUFACTURER_PART_NUMBER          = 'ManufacturerPartNumber__c';
	public const string FIELD_EXCLUDE_FROM_STORE                = 'ExcludeFromStore__c';
	public const string FIELD_IS_ACTIVE                         = 'IsActive';
}
