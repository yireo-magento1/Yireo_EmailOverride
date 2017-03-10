<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

// Allow for an override of Aschroder_SMTPPro_Model_Email_Template
if (Mage::helper('core')->isModuleEnabled('Aschroder_SMTPPro') && class_exists('Aschroder_SMTPPro_Model_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends Aschroder_SMTPPro_Model_Email_Template {}
}elseif (Mage::helper('core')->isModuleEnabled('Aschroder_Email') && class_exists('Aschroder_Email_Model_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends Aschroder_Email_Model_Email_Template {}
}elseif (Mage::helper('core')->isModuleEnabled('Ebizmarts_Mandrill') && class_exists('Ebizmarts_Mandrill_Model_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends Ebizmarts_Mandrill_Model_Email_Template {}
}elseif (Mage::helper('core')->isModuleEnabled('Ebizmarts_MailChimp') && class_exists('Ebizmarts_MailChimp_Model_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends Ebizmarts_MailChimp_Model_Email_Template {}
}elseif (Mage::helper('core')->isModuleEnabled('FreeLunchLabs_MailGun') && class_exists('FreeLunchLabs_MailGun_Model_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends FreeLunchLabs_MailGun_Model_Email_Template {}
}elseif (Mage::helper('core')->isModuleEnabled('SUMOHeavy_Postmark') && class_exists('SUMOHeavy_Postmark_Model_Core_Email_Template')) {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends SUMOHeavy_Postmark_Model_Core_Email_Template {}
} else {
    class Yireo_EmailOverride_Model_Email_Template_Wrapper extends Mage_Core_Model_Email_Template {}
}

/**
 * EmailOverride Core model
 */
class Yireo_EmailOverride_Model_Email_Template extends Yireo_EmailOverride_Model_Email_Template_Wrapper
{
    public function setDesignConfig(array $config)
    {
        if(isset($config['store'])) {
            $store = Mage::registry('emailoverride.store');
            if(empty($store)) {
                Mage::register('emailoverride.store', $config['store'], true);
            }
        }
        return parent::setDesignConfig($config);
    }

    /**
     * Send transactional email to recipient
     *
     * @param   int $templateId
     * @param   string|array $sender sender information, can be declared as part of config path
     * @param   string $email recipient email
     * @param   string $name recipient name
     * @param   array $vars variables which can be used in template
     * @param   int|null $storeId
     *
     * @throws Mage_Core_Exception
     *
     * @return  Mage_Core_Model_Email_Template
     */
    public function sendTransactional($templateId, $sender, $email, $name, $vars=array(), $storeId=null)
    {
        /** @var Yireo_EmailOverride_Model_Translate $translator */
        $translator = Mage::app()->getTranslator();
        $originalTranslatorStoreId = $translator->getConfig(Mage_Core_Model_Translate::CONFIG_KEY_STORE);
        if ($this->isDifferentStore($storeId, $originalTranslatorStoreId)) {
            $translator->setStoreId($storeId);
        }

        parent::sendTransactional($templateId, $sender, $email, $name, $vars, $storeId);

        if ($this->isDifferentStore($storeId, $originalTranslatorStoreId)) {
            $translator->setStoreId($originalTranslatorStoreId);
        }
        return $this;
    }

    /**
     * @param $storeId
     * @param $originalTranslatorStoreId
     * @return bool
     */
    public function isDifferentStore($storeId, $originalTranslatorStoreId)
    {
        return !is_null($storeId) && (int)$storeId !== (int)$originalTranslatorStoreId;
    }
}
