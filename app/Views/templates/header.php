<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | POS System</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?= site_url('/') ?>">POS System</a>
            <nav class="nav">
                <a href="<?= site_url('/') ?>" class="<?= url_is('/') ? 'active' : '' ?>">Home</a>
                <a href="<?= site_url('about') ?>" class="<?= url_is('about') ? 'active' : '' ?>">About</a>
                <a href="<?= site_url('customers') ?>" class="<?= url_is('customers*') ? 'active' : '' ?>">Customer Accounts</a>
                <a href="<?= site_url('users') ?>" class="<?= url_is('users*') ? 'active' : '' ?>">User Accounts</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <p class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></p>
        <?php endif ?>
