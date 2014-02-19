<?php

/**
 * Data.php - Helper file for Microdata module
 *
 * @copyright   Copyright (c) 2011 Kevin Kirchner
 * @license     
 * @author      Kevin Kirchner <hello@kevnk.com>
 */
 
class Kevnk_Microdata_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getBodyMicrodata($page = null) {
        
        //-- Used for PopupPage and PrintPage
        if($page) {
            return $this->itemType($page);
        }
        
        $getRequest = Mage::app()->getRequest();
        $uri = substr($getRequest->getRequestUri(), 1);
        $moduleName = $getRequest->getModuleName();
        $controllerName = $getRequest->getControllerName();
        $actionName = $getRequest->getActionName();
        $pathInfo = $moduleName . '/' . $controllerName . '/' . $actionName;
        
        //-- Check if CollectionPage
        if($pathInfo == 'catalog/category/view') {
            return $this->itemType('CollectionPage');
        }
        
        //-- Check if ItemPage
        if($pathInfo == 'catalog/product/view') {
            return $this->itemType('ItemPage');
        }
        
        //-- Check if CheckoutPage
        if($pathInfo == 'checkout/onepage/index') {
            return $this->itemType('CheckoutPage');
        }
        
        //-- Check if SearchResultsPage
        if($pathInfo == 'catalogsearch/result/index' || $pathInfo == 'catalogsearch/advanced/result') {
            return $this->itemType('SearchResultsPage');
        }
        
        //-- Check if AboutPage
        if(Mage::getStoreConfig('micro/urlkeys/aboutpage',Mage::app()->getStore()) == $uri) {
            return $this->getItemType('AboutPage');
        };
        
        //-- Check if ContactPage
        if(Mage::getStoreConfig('micro/urlkeys/contactpage',Mage::app()->getStore()) == $uri) {
            return $this->getItemType('ContactPage');
        };
        
        
        //-- Check if ProfilePage
        if(Mage::getStoreConfig('micro/urlkeys/profilepage',Mage::app()->getStore()) == $uri) {
            return $this->getItemType('ProfilePage');
        };
        
        return $this->itemType('WebPage');
    }
    
    
    public function itemProp($prop) {
        $itemProp = '';
        if(is_array($prop)) {
            foreach($prop as $item) {
                $itemProp .= ' itemprop="' . $item . '"';
            }
        } else {
            $itemProp = 'itemprop="' . $prop . '"';
        }
        
        return $itemProp;
    }
    
    public function itemType($type) {
        $itemType = 'itemscope';
        if(is_array($type)) {
            foreach($type as $item) {
                $itemType .= ' itemtype="' . $item . '"';
            }
        } else {
            $itemType = ' itemtype="' . $type . '"';
        }
        
        return $itemType;
    }
    
    public function metaTag($type=null, $prop=null, $content=null) {
        $itemType = $type ? ' ' . $this->itemType($type) : '';
        $itemProp = $prop ? ' ' . $this->itemProp($prop) : '';
        $content = $content ? ' content="' . $content . '"' : '';
        return '<meta' . $itemType . $itemProp . $content . '/>';
    }
    
}