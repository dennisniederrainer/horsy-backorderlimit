<?php

class Horsebrand_BackorderLimit_Block_MassStockEditor_Widget_Grid_Column_Renderer_MinQty extends MDN_AdvancedStock_Block_MassStockEditor_Widget_Grid_Column_Renderer_MinQty {

    public function render(Varien_Object $row) {
        $stockId = $row->getId();
        $minQty = (int) $row->getminQty();
        $useConfigMinQtyName = "use_config_min_qty_" . $stockId;

        // $onChange = 'onchange="persistantGrid.logChange(this.name, \''.$minQty.'\')"';
        $onChange = 'onchange="persistantGrid.logChange(this.name, \''.$minQty.'\');'.
        			'persistantGrid.logChange(\'' . $useConfigMinQtyName . '\', \'0\'); console.log(persistantGrid);"';
        $retour = '<input type="text" name="min_qty_' . $stockId . '" id="min_qty_' . $stockId . '" value="' . $minQty . '" size="4" '.$onChange.'>'.
        '<input style="display:none;" type="text" name="use_config_min_qty_' . $stockId . '" id="use_config_min_qty_' . $stockId . '" value="' . 0 . '">';
        return $retour;
    }

}