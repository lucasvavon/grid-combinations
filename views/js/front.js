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
	const form = document.querySelector("#grid_combinations");
	console.log(form);

	form.addEventListener("submit", (event) => {
		event.preventDefault();

		const formData = new FormData(form);
		console.log(formData);
		const quantities = {};

		formData.forEach(function (value, key) {
			if (key.startsWith("quantities")) {
				const keys = key.match(/quantities\[(\d+)\]\[(\d+)\]/);
				if (keys) {
					const sizeId = keys[1];
					const colorId = keys[2];
					if (!quantities[sizeId]) {
						quantities[sizeId] = {};
					}
					quantities[sizeId][colorId] = value;
				}
			}
		});

		console.log(quantities);

		fetch(form.action, {
			method: "POST",
			body: quantities,
		})
			.then((response) => response.json())
			.then((data) => {
				// handle the response data here
			})
			.catch((error) => {
				console.error("Error:", error);
			});

		// Submit the form after logging
		/* form.submit(); */
	});
});
