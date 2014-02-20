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
            return $this->itemType(array('CollectionPage','ItemList'));
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
            return $this->itemType('AboutPage');
        };
        
        //-- Check if ContactPage
        if(Mage::getStoreConfig('micro/urlkeys/contactpage',Mage::app()->getStore()) == $uri) {
            return $this->itemType('ContactPage');
        };
        
        
        //-- Check if ProfilePage
        if(Mage::getStoreConfig('micro/urlkeys/profilepage',Mage::app()->getStore()) == $uri) {
            return $this->itemType('ProfilePage');
        };
        
        return $this->itemType('WebPage');
    }
    
    public function microdata($prop='', $type='', $tag='', $content='') {
        $microdata = '';
        if($tag) {
            if($tag=='link') $microdata .= $this->linkTag($prop, $content);
            if($tag=='meta') $microdata .= $this->metaTag($type, $prop, $content);
        } else {
            if($prop) $microdata .= $this->itemProp($prop);            
            if($type) $microdata .= $this->itemType($type);
        }
        return $microdata;
    }
    
    public function itemProp($prop) {
        $itemProp = '';
        if(is_array($prop)) {
            foreach($prop as $item) {
                $itemProp .= ' itemprop="' . $item . '"';
            }
        } else {
            $itemProp = ' itemprop="' . $prop . '"';
        }
        
        return $itemProp;
    }
    
    public function itemType($type) {
        $itemType = ' itemscope';
        if(is_array($type)) {
            $i = 0;
            foreach($type as $item) {
                $attrName = !$i++ ? 'itemtype' : 'additionalType';
                $itemType .= ' ' . $attrName . '="http://schema.org/' . $item . '"';
            }
        } else {
            $itemType .= ' itemtype="http://schema.org/' . $type . '"';
        }
        
        return $itemType;
    }
    
    public function metaTag($prop='', $type='', $content='') {
        $itemType = $type ? ' ' . $this->itemType($type) : '';
        $itemProp = $prop ? ' ' . $this->itemProp($prop) : '';
        $content = $content ? ' content="' . $content . '"' : '';
        return '<meta' . $itemType . $itemProp . $content . '/>';
    }
    
    public function linkTag($prop='', $href='') {
        $itemProp = $prop ? ' ' . $this->itemProp($prop) : '';
        $href = $href ? ' href="http://schema.org/' . $href . '"' : '';
        return '<link' . $itemProp . $href . '/>';
    }
    
}