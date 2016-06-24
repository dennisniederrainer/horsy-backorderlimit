<?php

class Horsebrands_BackorderLimit_Model_SalesOrderPlanning_ProductAvailabilityStatus
        extends MDN_SalesOrderPlanning_Model_ProductAvailabilityStatus {

  /**
  * Define if product is saleable
  *
  * @return unknown
  */
  protected function getIsSaleable() {

    //if product doesn't manage stock
    if (!$this->getProduct()->getStockItem()->getManageStock())
    {
        $this->log('Product doesnt manage stocks');
        return true;
    }

    //if product available qty is 0 and backorders set to false, return false
    if ((!$this->getpa_allow_backorders()) && ($this->getpa_available_qty() == 0)) {
        $this->log('No available qty and no back orders');
        return false;
    }

    //if product available qty is 0 and current day is in the "out of stock period", and no end date return false
    if ($this->getpa_has_outofstock_period()) {
        if (($this->getpa_available_qty() == 0) && ($this->getIsWithinOutOfStockPeriod(date('Y-m-d'))) && (!$this->dateIsSet($this->getpa_outofstock_end()))) {
            $this->log('No available qty and within outofstock period');
            return false;
        }
    }

    //if product is set as "out of stock", return false
    if (!$this->getProduct()->getStockItem()->getIsInStock()) {
        if (($this->getpa_available_qty() > 0) && mage::getStoreConfig('advancedstock/general/restore_isinstock'))
        {
            $this->log('Product is available');
            return true;
        }
        else
        {
            $this->log('Product stock status is out of stock');
            return false;
        }
    }

    if($this->getpa_allow_backorders() == Horsebrands_BackorderLimit_Model_Source_Backorders::BACKORDERS_YES_LIMIT) {
        return ($this->getpa_available_qty() > 0);
    }

    return true;
  }

}
