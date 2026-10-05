<?php
$currentUser = current_user();

// A rejected sale re-renders the Record Sale form at POST /sales, so check the method too.
$onSaleForm = url_is('sales/new') || (url_is('sales') && service('request')->is('post'));
?>
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
                <?php if ($currentUser): ?>
                    <a href="<?= site_url('sales/new') ?>" class="<?= $onSaleForm ? 'active' : '' ?>">Record Sale</a>
                    <a href="<?= site_url('sales') ?>" class="<?= url_is('sales') && ! $onSaleForm ? 'active' : '' ?>">Sales History</a>
                    <a href="<?= site_url('products') ?>" class="<?= url_is('products*') ? 'active' : '' ?>">Products</a>
                    <a href="<?= site_url('customers') ?>" class="<?= url_is('customers*') ? 'active' : '' ?>">Customers</a>
                    <a href="<?= site_url('users') ?>" class="<?= url_is('users*') ? 'active' : '' ?>">Staff</a>
                <?php else: ?>
                    <a href="<?= site_url('/') ?>" class="<?= url_is('/') ? 'active' : '' ?>">Home</a>
                    <a href="<?= site_url('about') ?>" class="<?= url_is('about') ? 'active' : '' ?>">About</a>
                <?php endif ?>
            </nav>

            <?php if ($currentUser): ?>
                <div class="user-menu header-action">
                    <img class="avatar avatar-sm" src="<?= esc(avatar_url($currentUser['avatar']), 'attr') ?>" alt="">
                    <span class="user-name"><?= esc($currentUser['full_name']) ?></span>
                    <?= form_open('logout', ['class' => 'inline-form']) ?>
                        <button type="submit" class="link-button">Log out</button>
                    <?= form_close() ?>
                </div>
            <?php elseif (! url_is('login')): ?>
                <a class="button button-small header-action" href="<?= site_url('login') ?>">Staff Login</a>
            <?php endif ?>
        </div>
    </header>

    <main class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <p class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></p>
        <?php endif ?>
        <?php if (session()->getFlashdata('error')): ?>
            <p class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif ?>
