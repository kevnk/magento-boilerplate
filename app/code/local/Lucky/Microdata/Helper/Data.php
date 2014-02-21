<?php

/**
 * Data.php - Helper file for Microdata module
 *
 * @copyright   Copyright (c) 2014 LuckyGunner
 * @license     
 * @author      Kevin Kirchner <hello@kevnk.com>
 */
 
class Lucky_Microdata_Helper_Data extends Mage_Core_Helper_Abstract
{

    const MICRODATA_VOCAB_URI = "http://schema.org/";

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
            return $this->itemType( array('CollectionPage', 'ItemList') );
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
    
    public function microdata($prop='', $type='', $content='', $tag='') {
        $microdata = '';
        if($content) {
            if($tag == 'link') {
                $microdata .= $this->linkTag($prop, $content);
            } else {
                $microdata .= $this->metaTag($prop, $content, $type);
            }
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
        $itemType = 'itemscope';
        if(is_array($type)) {
            $i = 0;
            foreach($type as $item) {
                $itemType .= !$i++ ? ' itemtype="' . self::MICRODATA_VOCAB_URI . $item . '"' : ' additionaltype="' . self::MICRODATA_VOCAB_URI . $item . '"';
            }
        } else {
            $itemType .= ' itemtype="' . self::MICRODATA_VOCAB_URI . $type . '"';
        }
        
        return $itemType;
    }
    
    public function linkTag($prop='', $content='') {
        $itemProp = $prop ? ' ' . $this->itemProp($prop) : '';
        return '<link' . $itemProp . ' href="' . self::MICRODATA_VOCAB_URI . $content . '"/>';
    }

    public function metaTag($prop='', $content='', $type='') {
        $itemProp = $prop ? ' ' . $this->itemProp($prop) : '';
        $itemType = $type ? ' ' . $this->itemType($type) : '';
        $content = $content ? ' content="' . $content . '"' : '';
        return '<meta' . $itemType . $itemProp . $content . '/>';
    }
    
}