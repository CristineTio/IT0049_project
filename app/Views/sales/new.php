<h1><?= esc($title) ?></h1>

<?php if (! empty($error)): ?>
    <p class="alert alert-error"><?= esc($error) ?></p>
<?php endif ?>

<?php if (empty($products)): ?>
    <p class="empty">
        There are no products to sell yet. <a href="<?= site_url('products/new') ?>">Add a product</a> first.
    </p>
<?php else: ?>
    <?= form_open('sales', ['class' => 'form', 'novalidate' => true]) ?>
        <div class="field">
            <label for="product_id">Product <span class="required">*</span></label>
            <select id="product_id" name="product_id">
                <option value="">Select a product…</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= esc($product['id'], 'attr') ?>"
                            data-price="<?= esc($product['price'], 'attr') ?>"
                            data-stock="<?= esc($product['stock_quantity'], 'attr') ?>"
                            <?= set_select('product_id', (string) $product['id']) ?>
                            <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                        <?= esc($product['name']) ?> — <?= peso($product['price']) ?>
                        (<?= $product['stock_quantity'] <= 0 ? 'out of stock' : esc($product['stock_quantity']) . ' in stock' ?>)
                    </option>
                <?php endforeach ?>
            </select>
            <?= validation_show_error('product_id') ?>
        </div>

        <div class="field">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id">
                <option value="">Walk-in customer (none)</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= esc($customer['id'], 'attr') ?>" <?= set_select('customer_id', (string) $customer['id']) ?>>
                        <?= esc($customer['full_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
            <?= validation_show_error('customer_id') ?>
        </div>

        <div class="field">
            <label for="quantity">Quantity <span class="required">*</span></label>
            <input type="number" id="quantity" name="quantity" min="1" step="1"
                   value="<?= set_value('quantity', '1') ?>">
            <small id="stock-hint" class="hint"></small>
            <?= validation_show_error('quantity') ?>
        </div>

        <p class="sale-total">Total: <strong id="sale-total">₱0.00</strong></p>

        <div class="form-actions">
            <button type="submit" class="button">Record Sale</button>
            <a href="<?= site_url('sales') ?>" class="button button-secondary">View Sales History</a>
        </div>
    <?= form_close() ?>
<?php endif ?>
