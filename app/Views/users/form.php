<?php $isEdit = isset($user['id']); ?>

<h1><?= esc($title) ?></h1>

<?= $isEdit
    ? form_open_multipart($action, ['class' => 'form', 'novalidate' => true])
    : form_open($action, ['class' => 'form', 'novalidate' => true]) ?>
    <div class="field">
        <label for="username">Username <span class="required">*</span></label>
        <input type="text" id="username" name="username" maxlength="50"
               value="<?= set_value('username', $user['username'] ?? '') ?>">
        <small class="hint">Letters, numbers, underscores, and dashes only.</small>
        <?= validation_show_error('username') ?>
    </div>

    <div class="field">
        <label for="full_name">Full Name <span class="required">*</span></label>
        <input type="text" id="full_name" name="full_name" maxlength="100"
               value="<?= set_value('full_name', $user['full_name'] ?? '') ?>">
        <?= validation_show_error('full_name') ?>
    </div>

    <?php if ($isEdit): ?>
        <div class="field">
            <label for="avatar">Profile Picture</label>
            <div class="avatar-field">
                <img class="avatar avatar-lg"
                     src="<?= $user['avatar'] ? base_url('uploads/avatars/' . esc($user['avatar'], 'url')) : base_url('images/avatar-placeholder.svg') ?>"
                     alt="Current profile picture">
                <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            </div>
            <small class="hint">JPG or PNG, up to 2MB. Leave empty to keep the current picture.</small>
            <?= validation_show_error('avatar') ?>
        </div>

        <script>
            // Reject files over 2MB before uploading. Very large files would exceed
            // PHP's post_max_size and never reach the server-side validation.
            document.getElementById('avatar').addEventListener('change', function () {
                if (this.files.length && this.files[0].size > 2 * 1024 * 1024) {
                    alert('The Profile Picture must not be larger than 2MB.');
                    this.value = '';
                }
            });
        </script>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="button">Save User</button>
        <a href="<?= site_url('users') ?>" class="button button-secondary">Cancel</a>
    </div>
<?= form_close() ?>
