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
 * EmailOverride helper
 */
class Yireo_EmailOverride_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $code
     * @param string $fileName
     * @param string|integer|Mage_Core_Model_Store $store (optional)
     *
     * @return string|null
     */
    public function getLocaleOverrideFile($code, $fileName, $store = null)
    {
        $paths = $this->getLocalePaths($store);
        $localeCodes = $this->getLocaleCodesAsArray($code);

        foreach ($localeCodes as $localeCode) {
            foreach ($paths as $path) {
                $filePath = $path . DS . $localeCode . DS . $fileName;
                if (!empty($filePath) && file_exists($filePath)) {
                    return $filePath;
                }
            }
        }

        return null;
    }

    /**
     * @param $localeCode
     *
     * @return array
     */
    protected function getLocaleCodesAsArray($localeCode)
    {
        if ($localeCode === 'en_US') {
            return array($localeCode);
        }

        return array($localeCode, 'en_US');
    }

    /**
     * @param string|integer|Mage_Core_Model_Store $store (optional)
     *
     * @return array
     * @throws \Mage_Core_Model_Store_Exception
     */
    public function getLocalePaths($store = null)
    {
        $paths = array();

        $design = $this->getDesign($store);
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $design['package'] . DS . $design['theme'] . DS . 'locale';

        // Check for fallback support
        $paths = array_merge($paths, $this->getLocalePathsForFallbackScheme($design));

        // Custom paths
        $paths[] = Mage::getBaseDir('app') . DS . 'email' . DS . $design['package'] . DS . $design['theme'];
        $paths[] = Mage::getBaseDir('app') . DS . 'email' . DS . $design['package'] . DS . 'default';
        $paths[] = Mage::getBaseDir('app') . DS . 'email' . DS . 'base' . DS . 'default';

        // Default paths
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $design['package'] . DS . $design['theme'] . DS . 'locale';
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $design['package'] . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . 'default' . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'locale';
        $paths[] = Mage::getBaseDir('locale');

        return $paths;
    }

    /**
     * @param $design
     *
     * @return array
     */
    public function getLocalePathsForFallbackScheme($design)
    {
        if (!$this->supportsDesignFallback()) {
            return array();
        }

        $paths = array();

        /** @var Mage_Core_Model_Design_Fallback $fallbackModel */
        $fallbackModel = Mage::getModel('core/design_fallback');

        if (empty($fallbackModel)) {
            return array();
        }

        $fallbackSchemes = $fallbackModel->getFallbackScheme('frontend', $design['package'], $design['theme']);
        if (empty($fallbackSchemes)) {
            return array();
        }

        foreach ($fallbackSchemes as $scheme) {
            if (!isset($scheme['_package'], $scheme['_theme'])) {
                continue;
            }

            $paths[] = Mage::getBaseDir('design') . DS . 'frontend' . DS . $scheme['_package'] . DS . $scheme['_theme'] . DS . 'locale';
        }

        return $paths;
    }

    /**
     * @param string|integer|Mage_Core_Model_Store $store (optional)
     *
     * @return array
     * @throws \Mage_Core_Model_Store_Exception
     */
    public function getDesign($store = null)
    {
        if (empty($store)) {
            $store = Mage::registry('emailoverride.store');
        }

        if (empty($store)) {
            $store = $this->getDefaultStore();
        }

        $packageName = null;
        $theme = null;

        if (Mage::app()->getStore()->isAdmin() === false) {
            /** @var Mage_Core_Model_Design_Package $package */
            $package = Mage::getSingleton('core/design_package');
            $originalArea = $package->getArea();
            $originalStore = $package->getStore();

            if (!empty($store)) {
                $package->setStore($store);
            }

            $package->setArea('frontend');
            $packageName = $package->getPackageName();
            $theme = $package->getTheme('default');

            $package->setArea($originalArea);
            $package->setStore($originalStore);
        }

        if (empty($packageName) || in_array($theme, array('base', 'default'), true)) {
            $packageName = Mage::getStoreConfig('design/package/name', $store);
        }

        if (empty($theme) || 'default' === $theme) {
            $theme = Mage::getStoreConfig('design/theme/locale', $store);
        }

        if (empty($theme)) {
            $theme = Mage::getStoreConfig('design/theme/default', $store);
        }

        if (empty($packageName)) {
            $packageName = 'default';
        }

        if (empty($theme)) {
            $theme = 'default';
        }

        return array(
            'package' => $packageName,
            'theme' => $theme
        );
    }

    /**
     * @return boolean
     */
    public function supportsDesignFallback()
    {
        return file_exists(BP . '/app/code/core/Mage/Core/Model/Design/Fallback.php');
    }

    /**
     * @return int|Mage_Core_Model_Store
     */
    protected function getDefaultStore()
    {
        $websites = Mage::app()->getWebsites(true);
        if (empty($websites[1]) || !is_object($websites[1])) {
            return 0;
        }

        /** @var Mage_Core_Model_Website $website */
        $website = $websites[1];
        $defaultStore = $website->getDefaultStore();

        if (empty($defaultStore)) {
            return false;
        }

        return $defaultStore;
    }
}
