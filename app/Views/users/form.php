<?php $isEdit = isset($user['id']); ?>

<h1><?= esc($title) ?></h1>

<?= form_open_multipart($action, ['class' => 'form', 'novalidate' => true]) ?>
    <div class="field">
        <label for="full_name">Full Name <span class="required">*</span></label>
        <input type="text" id="full_name" name="full_name" maxlength="100"
               value="<?= set_value('full_name', $user['full_name'] ?? '') ?>">
        <?= validation_show_error('full_name') ?>
    </div>

    <div class="field">
        <label for="username">Username <span class="required">*</span></label>
        <input type="text" id="username" name="username" maxlength="50" autocomplete="off"
               value="<?= set_value('username', $user['username'] ?? '') ?>">
        <small class="hint">Letters, numbers, underscores, and dashes only.</small>
        <?= validation_show_error('username') ?>
    </div>

    <div class="field-row">
        <div class="field">
            <label for="password">
                <?= $isEdit ? 'New Password' : 'Password' ?>
                <?php if (! $isEdit): ?><span class="required">*</span><?php endif ?>
            </label>
            <input type="password" id="password" name="password" autocomplete="new-password">
            <?= validation_show_error('password') ?>
        </div>

        <div class="field">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
            <?= validation_show_error('password_confirm') ?>
        </div>
    </div>
    <small class="hint field-hint">
        At least 8 characters.
        <?= $isEdit ? 'Leave both fields blank to keep the current password.' : '' ?>
    </small>

    <div class="field">
        <label for="avatar">Profile Picture</label>
        <div class="image-field">
            <img class="avatar avatar-lg" src="<?= esc(avatar_url($user['avatar'] ?? null), 'attr') ?>"
                 alt="Current profile picture">
            <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                   data-max-bytes="2097152" data-too-large="The Profile Picture must not be larger than 2MB.">
        </div>
        <small class="hint">
            JPG or PNG, up to 2MB. It is cropped to a square thumbnail.
            <?= $isEdit ? 'Leave empty to keep the current picture.' : '' ?>
        </small>
        <?= validation_show_error('avatar') ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="button">Save Staff Member</button>
        <a href="<?= site_url('users') ?>" class="button button-secondary">Cancel</a>
    </div>
<?= form_close() ?>
