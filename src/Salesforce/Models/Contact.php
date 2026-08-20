<?php
declare(strict_types=1);

namespace CRSC\WPUtilities\Salesforce\Models;

/**
 * Contact Salesforce Model
 */
class Contact {
	public const string OBJECT_NAME           = 'Contact';
	public const string FIELD_ACCOUNT_ID      = 'AccountId';
	public const string FIELD_FIRST_NAME      = 'FirstName';
	public const string FIELD_MIDDLE_NAME     = 'MiddleName';
	public const string FIELD_LAST_NAME       = 'LastName';
	public const string FIELD_SUFFIX          = 'Suffix';
	public const string FIELD_EMAIL           = 'Email';
	public const string FIELD_PERSONAL_EMAIL  = 'npe01__HomeEmail__c';
	public const string FIELD_WORK_EMAIL      = 'npe01__WorkEmail__c';
	public const string FIELD_ALTERNATE_EMAIL = 'npe01__AlternateEmail__c';
	public const string FIELD_HOME_PHONE      = 'HomePhone';
	public const string FIELD_MOBILE_PHONE    = 'MobilePhone';
	public const string FIELD_WORK_PHONE      = 'npe01__WorkPhone__c';
	public const string FIELD_PREFERRED_PHONE = 'npe01__PreferredPhone__c';
	public const string FIELD_PREFERRED_EMAIL = 'npe01__Preferred_Email__c';
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
	public const string PREFERRED_PHONE_MOBILE               = 'Mobile';
	public const string PREFERRED_PHONE_WORK                 = 'Work';
	public const string PREFERRED_PHONE_HOME                 = 'Home';
	public const string PREFERRED_EMAIL_PERSONAL             = 'Personal';
	public const string PREFERRED_EMAIL_WORK                 = 'Work';
	public const string PREFERRED_EMAIL_ALTERNATE            = 'Home';
}
