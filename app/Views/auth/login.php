<div class="login-card">
    <h1>Staff Login</h1>
    <p class="muted">Log in to manage the POS.</p>

    <?php if (! empty($error)): ?>
        <p class="alert alert-error"><?= esc($error) ?></p>
    <?php endif ?>

    <?= form_open('login', ['class' => 'form form-plain', 'novalidate' => true]) ?>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" autofocus
                   value="<?= set_value('username') ?>">
            <?= validation_show_error('username') ?>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
            <?= validation_show_error('password') ?>
        </div>

        <button type="submit" class="button button-block">Log In</button>
    <?= form_close() ?>
</div>
