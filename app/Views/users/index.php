<?php $currentUserId = (int) current_user()['id']; ?>

<div class="page-heading">
    <h1><?= esc($title) ?></h1>
    <a class="button" href="<?= site_url('users/new') ?>">+ New Staff Member</a>
</div>

<?php if (! empty($users)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <img class="avatar" src="<?= esc(avatar_url($user['avatar']), 'attr') ?>"
                                 alt="<?= esc($user['full_name'], 'attr') ?>">
                        </td>
                        <td>
                            <?= esc($user['full_name']) ?>
                            <?php if ((int) $user['id'] === $currentUserId): ?>
                                <span class="badge">You</span>
                            <?php endif ?>
                        </td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc(date('M j, Y', strtotime($user['created_at']))) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a>
                            <?php if ((int) $user['id'] !== $currentUserId): ?>
                                <?= view('partials/delete_button', [
                                    'action'  => 'users/' . $user['id'] . '/delete',
                                    'confirm' => "Remove {$user['full_name']} from staff? They will no longer be able to log in.",
                                    'label'   => 'Delete',
                                ]) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="empty">No staff accounts yet.</p>
<?php endif ?>
