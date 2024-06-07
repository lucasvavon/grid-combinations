<?php

class GridCombinationsValidationModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->setTemplate('module:mymodule/views/templates/front/mytemplate.tpl');
    }
    
    public function postProcess()
    {

        if (Tools::isSubmit('grid_combinations')) {
            $quantities = Tools::getValue('quantities');

            /* foreach ($quantities as $sizeId => $colors) {
                foreach ($colors as $colorId => $quantity) {
                    $quantity = (int) $quantity;
                    if ($quantity > 0) {
                        $id_product_attribute = $this->getProductAttributeId($this->context->cart->id_product, $sizeId, $colorId);
                        if ($id_product_attribute) {
                            $this->context->cart->updateQty($quantity, $this->context->cart->id_product, $id_product_attribute);
                        }
                    }
                }
            } */

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            die;
        }

        parent::postProcess();
    }

    private function getProductAttributeId($id_product, $sizeId, $colorId)
    {
        $sql = 'SELECT pa.id_product_attribute
                FROM ' . _DB_PREFIX_ . 'product_attribute pa
                JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac1 ON pa.id_product_attribute = pac1.id_product_attribute
                JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac2 ON pa.id_product_attribute = pac2.id_product_attribute
                WHERE pa.id_product = ' . (int) $id_product . '
                AND pac1.id_attribute = ' . (int) $sizeId . '
                AND pac2.id_attribute = ' . (int) $colorId;

        return Db::getInstance()->getValue($sql);
    }
}