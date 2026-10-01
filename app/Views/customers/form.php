<h1><?= esc($title) ?></h1>

<?= form_open($action, ['class' => 'form', 'novalidate' => true]) ?>
    <div class="field">
        <label for="full_name">Full Name <span class="required">*</span></label>
        <input type="text" id="full_name" name="full_name" maxlength="100"
               value="<?= set_value('full_name', $customer['full_name'] ?? '') ?>">
        <?= validation_show_error('full_name') ?>
    </div>

    <div class="field">
        <label for="email">Email <span class="required">*</span></label>
        <input type="email" id="email" name="email" maxlength="100"
               value="<?= set_value('email', $customer['email'] ?? '') ?>">
        <?= validation_show_error('email') ?>
    </div>

    <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" maxlength="20"
               value="<?= set_value('phone', $customer['phone'] ?? '') ?>">
        <?= validation_show_error('phone') ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="button">Save Customer</button>
        <a href="<?= site_url('customers') ?>" class="button button-secondary">Cancel</a>
    </div>
<?= form_close() ?>
