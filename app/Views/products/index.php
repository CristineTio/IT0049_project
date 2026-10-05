<div class="page-heading">
    <h1><?= esc($title) ?></h1>
    <a class="button" href="<?= site_url('products/new') ?>">+ New Product</a>
</div>

<?php if (! empty($products)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th class="num">Price</th>
                    <th class="num">Stock</th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <img class="thumb" src="<?= esc(product_image_url($product['image']), 'attr') ?>"
                                 alt="<?= esc($product['name'], 'attr') ?>">
                        </td>
                        <td><?= esc($product['name']) ?></td>
                        <td class="num"><?= peso($product['price']) ?></td>
                        <td class="num">
                            <?php if ($product['stock_quantity'] <= 0): ?>
                                <span class="badge badge-danger">Out of stock</span>
                            <?php elseif ($product['stock_quantity'] <= 5): ?>
                                <span class="badge badge-warning"><?= esc($product['stock_quantity']) ?> &middot; Low</span>
                            <?php else: ?>
                                <?= esc($product['stock_quantity']) ?>
                            <?php endif ?>
                        </td>
                        <td><?= esc(date('M j, Y', strtotime($product['created_at']))) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a>
                            <?= view('partials/delete_button', [
                                'action'  => 'products/' . $product['id'] . '/delete',
                                'confirm' => "Archive {$product['name']}? It will be hidden from the product list and Record Sale page.",
                                'label'   => 'Archive',
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="empty">No products yet.</p>
<?php endif ?>
