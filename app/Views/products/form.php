<h1><?= esc($title) ?></h1>

<?= form_open_multipart($action, ['class' => 'form', 'novalidate' => true]) ?>
    <div class="field">
        <label for="name">Product Name <span class="required">*</span></label>
        <input type="text" id="name" name="name" maxlength="100"
               value="<?= set_value('name', $product['name'] ?? '') ?>">
        <?= validation_show_error('name') ?>
    </div>

    <div class="field-row">
        <div class="field">
            <label for="price">Price (₱) <span class="required">*</span></label>
            <input type="text" id="price" name="price" inputmode="decimal" placeholder="0.00"
                   value="<?= set_value('price', $product['price'] ?? '') ?>">
            <?= validation_show_error('price') ?>
        </div>

        <div class="field">
            <label for="stock_quantity">Stock Quantity <span class="required">*</span></label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" step="1"
                   value="<?= set_value('stock_quantity', $product['stock_quantity'] ?? '0') ?>">
            <?= validation_show_error('stock_quantity') ?>
        </div>
    </div>

    <div class="field">
        <label for="image">Product Image</label>
        <div class="image-field">
            <img class="thumb thumb-lg" src="<?= esc(product_image_url($product['image'] ?? null), 'attr') ?>"
                 alt="Current product image">
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                   data-max-bytes="2097152" data-too-large="The Product Image must not be larger than 2MB.">
        </div>
        <small class="hint">
            JPG or PNG, up to 2MB. It is cropped to a square for display.
            <?= isset($product['id']) ? 'Leave empty to keep the current image.' : '' ?>
        </small>
        <?= validation_show_error('image') ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="button">Save Product</button>
        <a href="<?= site_url('products') ?>" class="button button-secondary">Cancel</a>
    </div>
<?= form_close() ?>
