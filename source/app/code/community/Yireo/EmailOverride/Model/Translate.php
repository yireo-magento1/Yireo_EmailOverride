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
     * Retrieve translation file for module
     *
     * @param   string $module
     * @return  string
     */
    protected function _getModuleFilePath($module, $fileName)
    {
        if (Mage::app()->getStore()->isAdmin() == true) {
            return parent::_getModuleFilePath($module, $fileName);
        }

        $localeCode = $this->getLocale();
        if (empty($localeCode)) {
            return parent::_getModuleFilePath($module, $fileName);
        }

        $filePath = $this->getLocaleOverrideFolder().DS.$localeCode.DS.$fileName;
        if (!empty($filePath) && file_exists($filePath)) {
            return $filePath;
        }

        return parent::_getModuleFilePath($module, $fileName);
    }

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

        $filePath = $this->getLocaleOverrideFolder().DS.$localeCode.DS.'template'.DS.$type.DS.$file;
        if (!empty($filePath) && !file_exists($filePath)) {
            return parent::getTemplateFile($file, $type, $localeCode);
        }

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => Mage::getBaseDir('locale')));
 
        return (string) $ioAdapter->read($filePath);
    }

    /**
     * Custom function to return override folder for locales
     *
     * @param null
     * @return string
     */
    protected function getLocaleOverrideFolder()
    {
        $store = $store = Mage::registry('emailoverride.store');
        if(!empty($this->_config['store'])) {
            $store = $this->_config['store'];
        }

        $packageName = null;
        $theme = null;

        if (Mage::app()->getStore()->isAdmin() == false) {
            $package = Mage::getSingleton('core/design_package');
            $originalArea = $package->getArea();
            $originalStore = $package->getStore();

            if(!empty($store)) $package->setStore($store);
            $package->setArea('frontend');
            $packageName = $package->getPackageName();
            $theme = $package->getTheme('default');

            $package->setArea($originalArea);
            $package->setStore($originalStore);
        }

        if(empty($packageName) || in_array($theme, array('base', 'default'))) {
            $packageName = Mage::getStoreConfig('design/package/name', $store);
        }

        if(empty($theme) || in_array($theme, array('default'))) {
            $theme = Mage::getStoreConfig('design/theme/locale', $store);
        }

        if(empty($packageName)) $packageName = 'default';
        if(empty($theme)) $theme = 'default';

        $folder = Mage::getBaseDir('design').DS.'frontend'.DS.$packageName.DS.$theme.DS.'locale';
        return $folder;
    }

    /**
     * Loading data from module translation files
     *
     * @param   string $moduleName
     * @param   string $files
     * @return  Mage_Core_Model_Translate
     */
    protected function _loadModuleTranslation($moduleName, $files, $forceReload=false)
    {
        foreach ($files as $file) {
            $file = $this->_getModuleFilePath($moduleName, $file);
            $baseFile = basename($file);
            $overrideFile = Mage::getDesign()->getLocaleFileName($baseFile);
            if(file_exists($overrideFile)) {
                $file = $overrideFile;
            }
            $this->_addData($this->_getFileData($file), $moduleName, $forceReload);
        }
        return $this;
    }
}
