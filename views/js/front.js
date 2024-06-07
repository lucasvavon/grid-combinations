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
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

document.addEventListener("DOMContentLoaded", function () {
    updateTotalAmount();
});

function updateTotalAmount() {
    const form = document.querySelector("#grid_combinations");
    const formData = new FormData(form);
    const quantities = [];

    formData.forEach(function (value, key) {
        if (value > 0) {
            if (key.startsWith("combinations")) {
                const id_product_attribute = key.match(
                    /combinations\[(\d+)\]/,
                )[1];
                if (id_product_attribute) {
                    const combination = document.querySelector(
                        `#product_attribute_${id_product_attribute}`,
                    );
                    quantities.push({
                        id_product_attribute: id_product_attribute,
                        price: combination.dataset.price,
                        quantity: value,
                    });
                }
            }
        }
    });
    totalPrice = 0;

    quantities.forEach(function (item) {
        // Calculez le prix total pour cet élément (prix * quantité)
        var itemTotalPrice = item.price * item.quantity;

        // Ajoutez le prix total au prix total général
        totalPrice += itemTotalPrice;
    });

    document.querySelector("#total").innerHTML = totalPrice.toFixed(2);
}
