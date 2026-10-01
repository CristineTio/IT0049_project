<div class="page-heading">
    <h1><?= esc($title) ?></h1>
    <a class="button" href="<?= site_url('customers/new') ?>">+ New Customer</a>
</div>

<?php if (! empty($customers)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= esc($customer['id']) ?></td>
                        <td><?= esc($customer['full_name']) ?></td>
                        <td><?= esc($customer['email']) ?></td>
                        <td><?= esc($customer['phone'] ?? '—') ?></td>
                        <td><?= esc(date('M j, Y g:i A', strtotime($customer['created_at']))) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="empty">No customer accounts found.</p>
<?php endif ?>
