<?php

class Horsebrands_BackorderLimit_Model_Observer_Adminhtml
{

    public function addColumnsToGrid($observer)
    {
        $block = $observer->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Grid
            || $block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Config_Grid
            // || $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            $block->addColumnAfter('min_qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Backorderable'),
                    'type'  => 'number',
                    'index' => 'min_qty',
                    'width' => '50px'
            ), 'qty');
        }
        if($block instanceof MDN_AdvancedStock_Block_MassStockEditor_Grid) {
            $block->addColumnAfter('min_qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Backorderable'),
                    'index' => 'min_qty',
                    'filter_index' => 'min_qty',
                    'renderer' => 'MDN_AdvancedStock_Block_MassStockEditor_Widget_Grid_Column_Renderer_MinQty',
                    'align' => 'center',
                    'type'  => 'number',
            ), 'stock');
            $block->addColumnAfter('AvailableQty', array(
                'header' => Mage::helper('AdvancedStock')->__('Available Qty'),
                'index' => 'AvailableQty',
                // 'filter' => false,
                // 'sortable' => false,
                'align' => 'center',
                'renderer' => 'MDN_AdvancedStock_Block_MassStockEditor_Widget_Grid_Column_Renderer_AvailableQty'
            ), 'min_qty');
        }
        if($block instanceof MDN_AdvancedStock_Block_Product_Stocks) {
            $block->addColumnAfter('min_qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Backorderable'),
                    'index' => 'min_qty',
                    'type'  => 'number',
                    'width' => '50px'
            ), 'AvailableQty');
        }
    }

    public function joinFieldsToCollection($observer)
    {
        $collection = $observer->getCollection();
        $collection->joinField('min_qty',
                'cataloginventory/stock_item',
                'min_qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
    }

    public function updateProductAvailabilityStatus($observer) {
      $stock = $observer->getStock();

      $productAvailabilityStatus = mage::getModel('SalesOrderPlanning/ProductAvailabilityStatus')->load($stock->getProductId(), 'pa_product_id');
      $productAvailabilityStatus->refresh();
    }
}
