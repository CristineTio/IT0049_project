<div class="page-heading">
    <h1><?= esc($title) ?></h1>
    <a class="button" href="<?= site_url('users/new') ?>">+ New User</a>
</div>

<?php if (! empty($users)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Date Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <img class="avatar"
                                 src="<?= $user['avatar'] ? base_url('uploads/avatars/' . esc($user['avatar'], 'url')) : base_url('images/avatar-placeholder.svg') ?>"
                                 alt="<?= esc($user['full_name'], 'attr') ?>">
                        </td>
                        <td><?= esc($user['id']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['full_name']) ?></td>
                        <td><?= esc(date('M j, Y g:i A', strtotime($user['created_at']))) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="empty">No user accounts found.</p>
<?php endif ?>
