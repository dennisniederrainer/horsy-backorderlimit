<?php

class Horsebrands_BackorderLimit_Model_Source_Backorders extends Mage_CatalogInventory_Model_Source_Backorders
{
	const BACKORDERS_YES_LIMIT = 10;

    public function toOptionArray()
    {
        return array(
            array('value' => Mage_CatalogInventory_Model_Stock::BACKORDERS_NO, 'label'=>Mage::helper('cataloginventory')->__('No Backorders')),
            array('value' => Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NONOTIFY, 'label'=>Mage::helper('cataloginventory')->__('Allow Qty Below 0')),
            array('value' => Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY , 'label'=>Mage::helper('cataloginventory')->__('Allow Qty Below 0 and Notify Customer')),
            array('value' => self::BACKORDERS_YES_LIMIT , 'label'=>Mage::helper('cataloginventory')->__('Minusbestand mit Limitation')),
        );
    }
}