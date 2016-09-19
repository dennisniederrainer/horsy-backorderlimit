<?php

class Horsebrands_BackorderLimit_Model_Stock_Item extends MDN_AdvancedStock_Model_CatalogInventory_Stock_Item
//Mage_CatalogInventory_Model_Stock_Item
{
    /**
     * Check quantity
     *
     * @param   decimal $qty
     * @exception Mage_Core_Exception
     * @return  bool
     */
    public function checkQty($qty) {

        $qtyAvailableForSales = $this->getAvailableQtyForSale();

        //try to load available qty from product availability status (to move later and use a magento event ?)
        $productAvailabilityStatus = Mage::helper('SalesOrderPlanning/ProductAvailabilityStatus')->getForOneProduct($this->getProductId());
        if ($productAvailabilityStatus->getId())
            $qtyAvailableForSales = $productAvailabilityStatus->getpa_available_qty();

        //check that qty can be purchaseed
        if ($qtyAvailableForSales - $qty < 0) {
            switch ($this->getBackorders()) {
                case Mage_CatalogInventory_Model_Stock::BACKORDERS_NO:
                case Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT:
                    return false;
                    break;
            }
        }
        return true;
    }

    /**
     * Return available qty for sale for all stocks
     */
    public function getAvailableQtyForSale() {
        $productId = $this->getProductId();
        $websiteId = 0;
        $stocks = mage::helper('AdvancedStock/Product_Base')->getStocksForWebsiteAssignment($websiteId, MDN_AdvancedStock_Model_Assignment::_assignmentSales, $productId);
        $stockLevel = 0;
        $minQty = 0;
        $pendingOrderQty = 0;
        foreach ($stocks as $stock) {
            if ($stock->getId() != $this->getId()) {
                $stockLevel += $stock->getQty();
                $pendingOrderQty += $stock->getstock_ordered_qty();
                $minQty += $stock->getMinQty();
            } else {
                $stockLevel += $this->getQty();
                $pendingOrderQty += $this->getstock_ordered_qty();
                $minQty += $this->getMinQty();
            }
        }

        if($this->getBackorders() == Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT) {
            $value = $stockLevel - $minQty - $pendingOrderQty;
        } else {
            $value = $stockLevel - $pendingOrderQty;
        }

        if ($value < 0)
            $value = 0;

        return $value;
    }

    /**
     * Return available qty
     *
     * @return unknown
     */
    public function getAvailableQty() {
      if($this->getBackorders() == Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT) {
        $value = $this->getqty() - $this->getMinQty() - $this->getstock_ordered_qty();
      } else {
        $value = $this->getqty() - $this->getstock_ordered_qty();
      }

      if ($value < 0)
        $value = 0;

      return $value;
    }

}
