<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

// Allow for an override of Aschroder_SMTPPro_Model_Email_Template
if (class_exists('Aschroder_SMTPPro_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('Aschroder_SMTPPro')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Aschroder_SMTPPro_Model_Email_Template
    {
    }
} elseif (class_exists('Aschroder_Email_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('Aschroder_Email')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Aschroder_Email_Model_Email_Template
    {
    }
} elseif (class_exists('Ebizmarts_Mandrill_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('Ebizmarts_Mandrill')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Ebizmarts_Mandrill_Model_Email_Template
    {
    }
} elseif (class_exists('Ebizmarts_MailChimp_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('Ebizmarts_MailChimp')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Ebizmarts_MailChimp_Model_Email_Template
    {
    }
} elseif (class_exists('FreeLunchLabs_MailGun_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('FreeLunchLabs_MailGun')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends FreeLunchLabs_MailGun_Model_Email_Template
    {
    }
} elseif (class_exists('Mirasvit_EmailSmtp_Model_Email_Template') && Mage::helper('core')->isModuleEnabled('Mirasvit_EmailSmtp')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Mirasvit_EmailSmtp_Model_Email_Template
    {
    }
} elseif (class_exists('SUMOHeavy_Postmark_Model_Core_Email_Template') && Mage::helper('core')->isModuleEnabled('SUMOHeavy_Postmark')) {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends SUMOHeavy_Postmark_Model_Core_Email_Template
    {
    }
} else {
    class Yireo_EmailOverride_Model_Email_Template_Compatibility extends Mage_Core_Model_Email_Template
    {
    }
}
