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

    protected $_pathInfo;
    protected $_uri;

    protected function _getUri() {
        if(is_null($this->_uri)) {
            $getRequest = Mage::app()->getRequest();
            $this->_uri = substr($getRequest->getRequestUri(), 1);            
        }
        return $this->_uri;
    }

    protected function _getPathInfo() {
        if(is_null($this->_pathInfo)) {
            $getRequest = Mage::app()->getRequest();
            $uri = $this->_getUri();
            $moduleName = $getRequest->getModuleName();
            $controllerName = $getRequest->getControllerName();
            $actionName = $getRequest->getActionName();
            $this->_pathInfo = $moduleName . '/' . $controllerName . '/' . $actionName;
        }
        return $this->_pathInfo;
    }

    public function getBodyMicrodata($page = null) {
        
        if($page) {
            return $this->itemType($page);
        }
        
        $pathInfo = $this->_getPathInfo();
        $uri = $this->_getUri();

        //-- Check if CollectionPage
        if($pathInfo == 'catalog/category/view') {
            return $this->itemType('CollectionPage');
        }
        
        //-- Check if ItemPage
        if($pathInfo == 'catalog/product/view') {
            return $this->itemType( array('Product', 'ItemPage') );
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
    
    public function getPageMicrodata($page = null) {
        $pathInfo = $this->_getPathInfo();
        $uri = $this->_getUri();

        //-- Check if CollectionPage
        if($pathInfo == 'catalog/category/view') {
            // TODO: add page microdata
        }
        
        //-- Check if ItemPage
        if($pathInfo == 'catalog/product/view') {
            $microdata = '';
            $product = Mage::registry('current_product');

            $microdata .= $this->microdata('name','', Mage::helper('catalog/output')->productAttribute($product, $product->getName(), 'name') );
            return $microdata;
        }
        
        //-- Check if CheckoutPage
        if($pathInfo == 'checkout/onepage/index') {
            // TODO: add page microdata
        }
        
        //-- Check if SearchResultsPage
        if($pathInfo == 'catalogsearch/result/index' || $pathInfo == 'catalogsearch/advanced/result') {
            // TODO: add page microdata
        }
        
        //-- Check if AboutPage
        if(Mage::getStoreConfig('micro/urlkeys/aboutpage',Mage::app()->getStore()) == $uri) {
            // TODO: add page microdata
        };
        
        //-- Check if ContactPage
        if(Mage::getStoreConfig('micro/urlkeys/contactpage',Mage::app()->getStore()) == $uri) {
            // TODO: add page microdata
        };
            
        //-- Check if ProfilePage
        if(Mage::getStoreConfig('micro/urlkeys/profilepage',Mage::app()->getStore()) == $uri) {
            // TODO: add page microdata
        };

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
        $itemProp = ' itemprop="';
        if(is_array($prop)) {
            $i = 0;
            foreach($prop as $item) {
                $itemProp .= $i++ ? ' ' . $item : $item;
            }
        } else {
            $itemProp .= $prop;
        }
        $itemProp .= '"';
        
        return $itemProp;
    }
    
    public function itemType($type) {
        $itemType = ' itemscope';
        if(is_array($type)) {
            $i = 0;
            foreach($type as $item) {
                $itemType .= !$i++ ? ' itemtype="' . self::MICRODATA_VOCAB_URI . $item . '"' : ' additionalType="' . self::MICRODATA_VOCAB_URI . $item . '"';
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