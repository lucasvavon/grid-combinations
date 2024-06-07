<?php

class GridCombinationsFormModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (Tools::isSubmit('submit_grid_combinations')) {

            $id_product = Tools::getValue('id_product');
            $combinations = Tools::getValue('combinations');

            // Traitement des données
            if (!empty($combinations) && $id_product != 0) {

                /* $this->context->smarty->assign('confirmation', $combinations);
            } else {
                // Message d'erreur
                $this->context->smarty->assign('error', 'Le champ ne peut pas être vide.');
            } */
                if ($this->context->cookie->id_cart) {
                    $cart = new Cart($this->context->cookie->id_cart);
                }

                if (!isset($cart) or !$cart->id) {
                    $cart = new Cart($this->context->cookie->id_cart);
                    $cart->id_customer = (int) ($this->context->cookie->id_customer);
                    $cart->id_address_delivery = (int) (Address::getFirstCustomerAddressId($cart->id_customer));
                    $cart->id_address_invoice = $cart->id_address_delivery;
                    $cart->id_lang = (int) ($this->context->cookie->id_lang);
                    $cart->id_currency = (int) ($this->context->cookie->id_currency);
                    $cart->id_carrier = 1;
                    $cart->recyclable = 0;
                    $cart->gift = 0;
                    $cart->add();
                    $this->context->cookie->id_cart = (int) ($cart->id);
                }


                foreach ($combinations as $id_product_attribute => $quantity) {

                    if (is_numeric($quantity) && $quantity != 0) {
                        $cart->update();
                        $cart = $this->context->cart;
                        $cart->updateQty((int) ($quantity), (int) ($id_product), (int) ($id_product_attribute), false);
                        $cart->update();
                    }

                }
            }
        }
        /* $this->setTemplate('module:gridcombinations/views/templates/front/custom_form.tpl'); */

        Tools::redirect('index.php?controller=cart');
    }
}