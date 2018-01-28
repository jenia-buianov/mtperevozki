DELETE FROM `#__rsform_component_type_fields` WHERE `ComponentTypeId` = 21 ;
DELETE FROM `#__rsform_component_type_fields` WHERE `ComponentTypeId` = 22 ;
DELETE FROM `#__rsform_component_type_fields` WHERE `ComponentTypeId` = 23 ;
DELETE FROM `#__rsform_component_types` WHERE `ComponentTypeId` = 21 ;
DELETE FROM `#__rsform_component_types` WHERE `ComponentTypeId` = 22 ;
DELETE FROM `#__rsform_component_types` WHERE `ComponentTypeId` = 23 ;

DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.email' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.test' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.currency' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.thousands' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.decimal' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.nodecimals' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.return' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.cancel' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.language' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.tax.type' ;
DELETE FROM `#__rsform_config` WHERE `SettingName` = 'paypal.tax.value' ;


INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.email', '');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.test', '');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.currency', '');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.thousands', ',');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.decimal', '.');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.nodecimals', '2');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.return', '');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.cancel', '');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.language', 'US');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.tax.type', '1');
INSERT IGNORE INTO `#__rsform_config` (`ConfigId`, `SettingName`, `SettingValue`) VALUES ('', 'paypal.tax.value', '');


INSERT IGNORE INTO `#__rsform_component_types` (`ComponentTypeId`, `ComponentTypeName`) VALUES (21, 'paypalSingleProduct');
INSERT IGNORE INTO `#__rsform_component_types` (`ComponentTypeId`, `ComponentTypeName`) VALUES (22, 'paypalMultipleProducts');
INSERT IGNORE INTO `#__rsform_component_types` (`ComponentTypeId`, `ComponentTypeName`) VALUES (23, 'paypalTotal');

INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'NAME', 'hiddenparam', 'rsfp_Product', 0);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'CAPTION', 'textbox', '', 1);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'PRICE', 'textbox', '', 4);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'SHOW', 'select', 'YES\r\nNO', 3);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'DESCRIPTION', 'textarea', '', 2);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 21, 'COMPONENTTYPE', 'hidden', '21', 0);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 23, 'NAME', 'textbox', '', 0);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 23, 'CAPTION', 'textbox', '', 1);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 23, 'COMPONENTTYPE', 'hidden', '23', 2);

INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'NAME', 'textbox', '', 0);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'CAPTION', 'textbox', '', 1);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'COMPONENTTYPE', 'hidden', '22', 9);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'SIZE', 'textbox', '', 2);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'MULTIPLE', 'select', 'NO\r\nYES', 3);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'ITEMS', 'textarea', '', 5);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'REQUIRED', 'select', 'NO\r\nYES', 6);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'ADDITIONALATTRIBUTES', 'textarea', '', 7);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'DESCRIPTION', 'textarea', '', 8);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'VALIDATIONMESSAGE', 'textarea', 'INVALIDINPUT', 9);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'VIEW_TYPE', 'select', 'DROPDOWN\r\nCHECKBOX', 4);
INSERT IGNORE INTO `#__rsform_component_type_fields` (`ComponentTypeFieldId`, `ComponentTypeId`, `FieldName`, `FieldType`, `FieldValues`, `Ordering`) VALUES('', 22, 'FLOW', 'select', 'HORIZONTAL\r\nVERTICAL', 3);