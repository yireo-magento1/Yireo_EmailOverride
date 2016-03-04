<?php
/**
 * Yireo EmailOverride for Magento
 *
 * @package     Yireo_EmailOverride
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
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
     * @param string $module
     * @param string $fileName
     *
     * @return string
     */
    protected function _getModuleFilePath($module, $fileName)
    {
        // If this is the backend, we return to the default
        if ($this->isAdmin() == true) {
            return parent::_getModuleFilePath($module, $fileName);
        }

        // If no locale is set, we return to the default
        $localeCode = $this->getLocale();
        if (empty($localeCode)) {
            return parent::_getModuleFilePath($module, $fileName);
        }

        $filePath = $this->getLocaleOverrideFile($localeCode, $fileName);
        if (empty($filePath) || file_exists($filePath) == false) {
            return parent::_getModuleFilePath($module, $fileName);
        }

        return $filePath;
    }

    /**
     * Retrieve translated template file
     * Try current design package first
     *
     * @param string $file
     * @param string $type
     * @param string $localeCode
     *
     * @return string
     */
    public function getTemplateFile($file, $type, $localeCode = null)
    {
        if (is_null($localeCode) || preg_match('/[^a-zA-Z_]/', $localeCode)) {
            $localeCode = $this->getLocale();
        }

        $filePath = $this->getLocaleOverrideFile($localeCode, 'template' . DS . $type . DS . $file);
        if (empty($filePath) || !file_exists($filePath)) {
            return parent::getTemplateFile($file, $type, $localeCode);
        }

        return $this->readFileContents($filePath, Mage::getBaseDir('locale'));
    }

    /**
     * @param $filePath
     * @param $path
     *
     * @return string
     */
    protected function readFileContents($filePath, $path)
    {
        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => $path));

        return (string)$ioAdapter->read($filePath);
    }

    /**
     * Custom function to return override folder for locales
     *
     * @param string $localeCode
     * @param string $fileName
     *
     * @return string
     */
    protected function getLocaleOverrideFile($localeCode, $fileName)
    {
        $store = null;
        if (!empty($this->_config['store'])) {
            $store = $this->_config['store'];
        }

        return $this->getModuleHelper()->getLocaleOverrideFile($localeCode, $fileName, $store);
    }

    /**
     * Loading data from module translation files
     *
     * @param string $moduleName
     * @param array $files
     * @param bool $forceReload (optional)
     *
     * @return Mage_Core_Model_Translate
     */
    protected function _loadModuleTranslation($moduleName, $files, $forceReload = false)
    {
        foreach ($files as $file) {
            $file = $this->_getModuleFilePath($moduleName, $file);
            $baseFile = basename($file);
            $overrideFile = Mage::getDesign()->getLocaleFileName($baseFile);

            if (file_exists($overrideFile)) {
                $file = $overrideFile;
            }
            $this->_addData($this->_getFileData($file), $moduleName, $forceReload);
        }

        return $this;
    }

    /**
     * Loading current theme translation
     *
     * @param bool $forceReload (optional)
     *
     * @return Mage_Core_Model_Translate
     */
    protected function _loadThemeTranslation($forceReload = false)
    {
        // Check for fallback support
        if ($this->getModuleHelper()->supportsDesignFallback() == false) {
            return parent::_loadThemeTranslation($forceReload);
        }

        /** @var Mage_Core_Model_Design_Fallback $fallbackModel */
        $fallbackModel = Mage::getModel('core/design_fallback');

        /** @var Mage_Core_Model_Design_Package $designPackage */
        $designPackage = Mage::getSingleton('core/design_package');

        // First add fallback package translate.csv files
        $fallbacks = $fallbackModel->getFallbackScheme($designPackage->getArea(), $designPackage->getPackageName(), $designPackage->getTheme('layout'));

        foreach ($fallbacks as $fallback) {
            if (!isset($fallback['_package']) || !isset($fallback['_theme'])) {
                continue;
            }

            $fallbackFile = $designPackage->getLocaleFileName('translate.csv', array('_package' => $fallback['_package']));
            $this->_addData($this->_getFileData($fallbackFile), false, $forceReload);
        }

        // Now add current package translate.csv
        $file = Mage::getDesign()->getLocaleFileName('translate.csv');
        $this->_addData($this->_getFileData($file), false, $forceReload);

        return $this;
    }

    /**
     * @return bool
     */
    protected function isAdmin()
    {
        return (bool) Mage::app()->getStore()->isAdmin();
    }

    /**
     * @return Yireo_EmailOverride_Helper_Data
     */
    protected function getModuleHelper()
    {
        return Mage::helper('emailoverride');
    }
}
