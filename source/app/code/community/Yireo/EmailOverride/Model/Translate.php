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
class Yireo_EmailOverride_Model_Translate extends Mage_Core_Model_Translate
{
    /**
     * Retrieve translated template file
     * Try current design package first
     *
     * @param string $file
     * @param string $type
     * @param string $localeCode
     * @return string
     */
    public function getTemplateFile($file, $type, $localeCode=null)
    {
        if (is_null($localeCode) || preg_match('/[^a-zA-Z_]/', $localeCode)) {
            $localeCode = $this->getLocale();
        }
 
        $store = null;
        $package = Mage::getSingleton('core/design_package');
        if(!empty($store)) $package->setStore($store);
        $packageName = $package->getPackageName();
        $theme = $package->getTheme('default');

        if(empty($packageName)) $packageName = 'default';
        if(empty($theme)) $theme = 'default';

        $filePath = Mage::getBaseDir('design').DS.'frontend'.DS
            .$packageName.DS.$theme.DS.'locale'.DS
            .$localeCode.DS.'template'.DS.$type.DS.$file;
 
        if (!file_exists($filePath)) {
            return parent::getTemplateFile($file, $type, $localeCode);
        }
 
        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => Mage::getBaseDir('locale')));
 
        return (string) $ioAdapter->read($filePath);
    }
}
