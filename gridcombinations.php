<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2024 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Gridcombinations extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'gridcombinations';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Piment Bleu';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Grid combinations');
        $this->description = $this->l('Grid combinations at the bottom of the product page');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayFooterProduct');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function hookDisplayFooterProduct($params)
    {
        $product = new Product((int) $params['product']['id'], false, Context::getContext()->language->id);
        if ($product->hasAttributes()) {

            $ids_product_attribute = Db::getInstance()->executeS('SELECT pa.id_product_attribute FROM ' . _DB_PREFIX_ . 'product_attribute pa WHERE pa.id_product = ' . $product->id);
            foreach ($ids_product_attribute as $value) {
                $current_prices[$value['id_product_attribute']] = Product::getPriceStatic($product->id, true, $value['id_product_attribute']);
            }

            $groups = Db::getInstance()->executeS('SELECT DISTINCT ag.id_attribute_group, ag.is_color_group
            FROM ' . _DB_PREFIX_ . 'product_attribute_combination pac
            JOIN ' . _DB_PREFIX_ . 'attribute a ON pac.id_attribute = a.id_attribute
            JOIN ' . _DB_PREFIX_ . 'attribute_group ag ON a.id_attribute_group = ag.id_attribute_group
            WHERE pac.id_product_attribute IN (
                SELECT id_product_attribute
                FROM ' . _DB_PREFIX_ . 'product_attribute
                WHERE id_product = ' . $product->id . '
            );');

            if (sizeof($groups) == 2) {
                if ($group[0]['is_color_group'] || $group[1]['is_color_group']) {
                    foreach ($groups as $group) {
                        if ($group['is_color_group']) {
                            $colors = Db::getInstance()->executeS('SELECT a.id_attribute, al.name, a.color as hex, pa.id_product_attribute
                        FROM ' . _DB_PREFIX_ . 'attribute a 
                        JOIN ' . _DB_PREFIX_ . 'attribute_lang al ON a.id_attribute = al.id_attribute 
                        JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac ON a.id_attribute = pac.id_attribute
                        JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON pac.id_product_attribute = pa.id_product_attribute
                        JOIN ' . _DB_PREFIX_ . 'product_attribute_image pai ON pa.id_product_attribute = pai.id_product_attribute
                        WHERE a.id_attribute_group = ' . $group['id_attribute_group'] . '
                        AND pa.id_product = ' . $product->id . '
                        GROUP BY a.position;'
                            );

                            foreach ($colors as &$color) {
                                $id_image = Db::getInstance()->getValue('SELECT a.id_image as id FROM ' . _DB_PREFIX_ . 'product_attribute_image a JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON a.id_product_attribute = pa.id_product_attribute JOIN ' . _DB_PREFIX_ . 'image i ON a.id_image = i.id_image WHERE a.`id_product_attribute` = ' . $color['id_product_attribute'] . ' ORDER BY i.position');

                                $link = new Link();
                                $url = $link->getImageLink($product->link_rewrite, $id_image, 'small_default');
                                $color['image_link'] = str_replace(Tools::getHttpHost(), '', $url);
                            }
                        } else {
                            $sizes = Db::getInstance()->executeS('SELECT a.id_attribute, al.name
                        FROM ' . _DB_PREFIX_ . 'attribute a 
                        JOIN ' . _DB_PREFIX_ . 'attribute_lang al ON a.id_attribute = al.id_attribute 
                        JOIN ' . _DB_PREFIX_ . 'product_attribute_combination pac ON a.id_attribute = pac.id_attribute
                        JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON pac.id_product_attribute = pa.id_product_attribute
                        WHERE a.id_attribute_group = ' . $group['id_attribute_group'] . '
                        AND pa.id_product = ' . $product->id . '
                        GROUP BY a.id_attribute;');
                        }
                    }
                }


                $this->context->smarty->assign('colors', $colors);
                $this->context->smarty->assign('sizes', $sizes);
                $this->context->smarty->assign('current_prices', $current_prices);

                return $this->display(__FILE__, 'views/templates/hook/grid-combinations.tpl');
            }
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }
}
