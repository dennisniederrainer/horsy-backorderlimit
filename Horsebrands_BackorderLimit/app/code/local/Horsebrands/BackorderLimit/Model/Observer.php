<?php

class Horsebrands_BackorderLimit_Model_Observer {

  public function catalogProductIsSalableAfter($observer) {
      $salable = $observer->getSalable();
      $product = $observer->getProduct();

      switch ($product->getTypeId()) {
          case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE:
          case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
          case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
              break;
          default:
              $stock = Mage::getModel('cataloginventory/stock_item')
                  ->loadByProduct($product);

              if($stock->getManageStock()
                  && $stock->getBackorders() == Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT) {
                  $_qty = (int)$stock->getQty();
                  $_minQty = (int)$stock->getMinQty();

                  if ($_qty <= $_minQty) {
                      $salable->setIsSalable(false);
                  }
              }
      }
  }
}
