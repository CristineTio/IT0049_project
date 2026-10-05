<div class="page-heading">
    <h1><?= esc($title) ?></h1>
    <a class="button" href="<?= site_url('sales/new') ?>">+ Record Sale</a>
</div>

<?php if (! empty($sales)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Sale #</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Sold By</th>
                    <th class="num">Qty</th>
                    <th class="num">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= esc($sale['id']) ?></td>
                        <td><?= esc(date('M j, Y g:i A', strtotime($sale['created_at']))) ?></td>
                        <td><?= esc($sale['product_name']) ?></td>
                        <td><?= $sale['customer_name'] !== null ? esc($sale['customer_name']) : '<span class="muted">Walk-in</span>' ?></td>
                        <td><?= esc($sale['staff_name']) ?></td>
                        <td class="num"><?= esc($sale['quantity']) ?></td>
                        <td class="num"><?= peso($sale['total_price']) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager->getPageCount() > 1): ?>
        <?= $pager->links() ?>
    <?php endif ?>
<?php else: ?>
    <p class="empty">No sales recorded yet.</p>
<?php endif ?>
