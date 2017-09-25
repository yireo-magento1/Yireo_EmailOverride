<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * EmailOverride Core model
 */
class Yireo_EmailOverride_Model_Email_Template extends Yireo_EmailOverride_Model_Email_Template_Compatibility
{
    public function setDesignConfig(array $config)
    {
        if (isset($config['store'])) {
            $store = Mage::registry('emailoverride.store');
            if (empty($store)) {
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
    public function sendTransactional($templateId, $sender, $email, $name, array $vars = array(), $storeId = null)
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
        return $storeId !== null && (int)$storeId !== (int)$originalTranslatorStoreId;
    }
}
