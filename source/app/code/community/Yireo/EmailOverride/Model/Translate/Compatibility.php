<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

if (class_exists('Fishpig_Wordpress_Model_Translate') && Mage::helper('core')->isModuleEnabled('Fishpig_Wordpress')) {
    class Yireo_EmailOverride_Model_Translate_Compatibility extends Fishpig_Wordpress_Model_Translate
    {
    }
} else {
    class Yireo_EmailOverride_Model_Translate_Compatibility extends Mage_Core_Model_Translate
    {
    }
}
