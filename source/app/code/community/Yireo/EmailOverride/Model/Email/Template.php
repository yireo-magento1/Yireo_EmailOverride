<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * EmailOverride Core model
 */
class Yireo_EmailOverride_Model_Email_Template extends Mage_Core_Model_Email_Template
{
    public function setDesignConfig(array $config)
    {
        if(isset($config['store'])) {
            Mage::register('emailoverride.store', $config['store'], true);
        }
        return parent::setDesignConfig($config);
    }
}
