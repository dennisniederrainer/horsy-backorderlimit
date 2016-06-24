<?php

class Horsebrands_BackorderLimit_Helper_Base extends MDN_AdvancedStock_Helper_Product_Base {


    public function getAvailableQty($productId, $websiteId) {
        $storeId = mage::app()->getStore()->getStoreId();
        $defaultStockId = mage::getStoreConfig('advancedstock/router/default_warehouse', $storeId);

        $stocks = $this->getStocksForWebsiteAssignment($websiteId, MDN_AdvancedStock_Model_Assignment::_assignmentSales, $productId);
        $value = 0;
        $stockLevel = 0;
        $orderedQty = 0;
        $minQty = 0;
        foreach ($stocks as $stock) {
            $stockLevel += $stock->getqty();
            $orderedQty += $stock->getstock_ordered_qty();

            if($stock->getBackorders() == Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT
                    && $stock->getstock_id() == $defaultStockId) {
                $minQty += $stock->getMinQty();
            }
        }

        $value = $stockLevel - $minQty - $orderedQty;
        if ($value < 0)
            $value = 0;

        return $value;
    }
}
