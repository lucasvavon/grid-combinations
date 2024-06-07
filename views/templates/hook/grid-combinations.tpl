{block name='product_combinations_grid'}

<div class="product-combinations-grid">
    <form action="{$link->getModuleLink('gridcombinations', 'form')}" method="post" id="grid_combinations"
        name="grid_combinations">
        <div
            style="display: flex; justify-content: space-between; align-items: center; background: #ebebeb; padding: 20px;">
            <span>{$product.name}</span>
            <b style="color: #133370;">
                MONTANT TOTAL : <span id="total">{0|string_format:"%.2f"}</span> €
            </b>
            <button class="btn btn-primary add-to-cart" id="submit_grid_combinations" name="submit_grid_combinations"
                type="submit">Ajouter au panier</button>

        </div>
        <input id="id_product" name="id_product" value="{$product.id}" hidden>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="header-item"></th>
                    <th class="header-item"></th>
                    {foreach from=$sizes item=size}
                    <th class="header-item">{$size.name}</th>
                    {/foreach}
                </tr>
            </thead>
            <tbody>
                {foreach from=$colors item=color}
                <tr>
                    <th class="header-item">

                        <p>{$color.name}</p>

                        <img src="{$color.image_link}">

                    </th>
                    <td>
                        <div style="display: flex; flex-direction: column; align-items: flex-end;">
                            <div><b style="color: #133370;">En stock</b></div>
                            <div style="display: flex; align-items: center;height: 40px; margin: 10px 0;">Quantité</div>
                            <div><b style="color: #2c698d;">Prix</b></div>
                        </div>
                    </td>
                    {foreach from=$sizes item=size}
                    <td>
                        {foreach from=$combinations key=key item=combination}

                        {$tmp = ksort($combination.attributes_values)}

                        {assign var="sorted_attributes" value=$combination.attributes_values}

                        {if reset($sorted_attributes) == $size.name && end($sorted_attributes) ==
                        $color.name}
                        <div><b
                                style="color: {if $combination.quantity == 0} #de6736; {else} #133370;{/if}">{$combination.quantity}</b>
                        </div>
                        <div style="display: flex; justify-content: center;">
                            <input
                                class="input-group {if $combination.quantity == 0} input-grid-disabled {else} input-grid {/if}"
                                type="number" id="product_attribute_{$key}" name="combinations[{$key}]"
                                data-price="{$current_prices[$key]}" placeholder="{$size.name}" min="0" {if
                                $combination.quantity==0} disabled {/if} onchange="updateTotalAmount()">
                        </div>

                        <div><b style="color: #2c698d;">
                                {$current_prices[$key]|string_format:"%.2f"} €
                            </b></div>
                        {/if}
                        {/foreach}
                    </td>
                    {/foreach}
                </tr>
                {/foreach}
            </tbody>
        </table>
    </form>
</div>

<style>
    .no-stock-bg {
        width: 100%;
        position: absolute;
        background-color: #f8f8f8;
        background-image: repeating-linear-gradient(-45deg, transparent, transparent 25px, #fff 25px, #fff 50px);
    }

    .header-item {
        background: #fff;
        border: .3125rem solid #ebebeb !important;
        text-align: center;
        vertical-align: middle;
        width: fit-content;
    }

    td {
        padding: 8px !important;
        background-color: #F6F6F6 !important;
        vertical-align: middle !important;
        text-align: center;
    }

    .input-grid {
        height: 40px;
        border: 1px solid #979797;
        border-radius: 0;
        color: #54585d;
        text-align: center;
        width: 70px;
        font-size: 16px;
        background: #fff;
        -moz-appearance: textfield;
        -webkit-appearance: textfield;
        margin: 10px 0;
    }

    .input-grid-disabled {
        height: 40px;
        border: 1px solid #979797 !important;
        border-radius: 0;
        text-align: center;
        width: 70px;
        font-size: 16px;
        -moz-appearance: textfield;
        -webkit-appearance: textfield;
        margin: 10px 0;
    }
</style>

{/block}