<?php if ($user): ?>
    <section class="hero">
        <h1>Hello, <?= esc($user['full_name']) ?></h1>
        <p>What would you like to do?</p>
    </section>

    <section class="cards">
        <a class="card card-primary" href="<?= site_url('sales/new') ?>">
            <h2>Record Sale</h2>
            <p>Sell a product to a customer and update the stock.</p>
        </a>
        <a class="card" href="<?= site_url('sales') ?>">
            <h2>Sales History</h2>
            <p>See past transactions and who recorded them.</p>
        </a>
        <a class="card" href="<?= site_url('products') ?>">
            <h2>Products</h2>
            <p>Manage products, prices, stock, and images.</p>
        </a>
        <a class="card" href="<?= site_url('customers') ?>">
            <h2>Customers</h2>
            <p>Manage the customers registered in the system.</p>
        </a>
        <a class="card" href="<?= site_url('users') ?>">
            <h2>Staff</h2>
            <p>Manage staff accounts, passwords, and profile pictures.</p>
        </a>
    </section>
<?php else: ?>
    <section class="hero">
        <h1>Point of Sale System</h1>
        <p>
            Record sales, track inventory, and manage products, customers, and staff in one place.
            All data is stored in a MySQL database.
        </p>
        <a class="button" href="<?= site_url('login') ?>">Staff Login</a>
    </section>
<?php endif ?>
