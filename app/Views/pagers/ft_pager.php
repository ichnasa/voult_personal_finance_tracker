<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<?php if ($pager->hasPreviousPage()): ?>
  <a href="<?= $pager->getPreviousPage() ?>"><i class="bi bi-chevron-left"></i></a>
<?php endif; ?>

<?php foreach ($pager->links() as $link): ?>
  <?php if ($link['active']): ?>
    <span class="active"><?= $link['title'] ?></span>
  <?php else: ?>
    <a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
  <?php endif; ?>
<?php endforeach; ?>

<?php if ($pager->hasNextPage()): ?>
  <a href="<?= $pager->getNextPage() ?>"><i class="bi bi-chevron-right"></i></a>
<?php endif; ?>
