<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

/**
 * Contact Salesforce Model
 */
class Contact {
	public const string OBJECT_NAME = 'Contact';
	public const string FIELD_ACCOUNT_ID   = 'AccountId';
	public const string FIELD_FIRST_NAME   = 'FirstName';
	public const string FIELD_MIDDLE_NAME  = 'MiddleName';
	public const string FIELD_LAST_NAME    = 'LastName';
	public const string FIELD_SUFFIX       = 'Suffix';
	public const string FIELD_EMAIL        = 'Email';
	public const string FIELD_MOBILE_PHONE = 'MobilePhone';
	public const string FIELD_WORK_PHONE   = 'npe01__WorkPhone__c';
	// we are using the mailing address for the billing address
	public const string FIELD_MAILING_ADDRESS_STREET       = 'MailingStreet';
	public const string FIELD_MAILING_ADDRESS_CITY         = 'MailingCity';
	public const string FIELD_MAILING_ADDRESS_STATE_CODE   = 'MailingState';
	public const string FIELD_MAILING_ADDRESS_ZIP          = 'MailingPostalCode';
	public const string FIELD_MAILING_ADDRESS_COUNTRY_CODE = 'MailingCountry';
	// we are using the other address for the mailing address
	public const string FIELD_OTHER_ADDRESS_STREET       = 'OtherStreet';
	public const string FIELD_OTHER_ADDRESS_CITY         = 'OtherCity';
	public const string FIELD_OTHER_ADDRESS_STATE_CODE   = 'OtherState';
	public const string FIELD_OTHER_ADDRESS_ZIP          = 'OtherPostalCode';
	public const string FIELD_OTHER_ADDRESS_COUNTRY_CODE = 'OtherCountry';

	// Custom fields
	public const string FIELD_OPT_IN_SMS                     = 'SMS_Opt_in_Class_Updates__c';
	public const string FIELD_MATERIAL_SHIPPING_STREET       = 'Material_Shipping_Address__Street__s';
	public const string FIELD_MATERIAL_SHIPPING_CITY         = 'Material_Shipping_Address__City__s';
	public const string FIELD_MATERIAL_SHIPPING_STATE_CODE   = 'Material_Shipping_Address__StateCode__s';
	public const string FIELD_MATERIAL_SHIPPING_ZIP          = 'Material_Shipping_Address__PostalCode__s';
	public const string FIELD_MATERIAL_SHIPPING_COUNTRY_CODE = 'Material_Shipping_Address__CountryCode__s';
}
