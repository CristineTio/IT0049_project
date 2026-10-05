<?php
/**
 * A POST form button for deleting a record, with a confirmation prompt.
 *
 * @var string $action  URL the form posts to
 * @var string $confirm Question shown before deleting
 * @var string $label   Button text
 */
?>
<?= form_open($action, ['class' => 'inline-form', 'data-confirm' => $confirm]) ?>
    <button type="submit" class="link-button link-danger"><?= esc($label) ?></button>
<?= form_close() ?>
