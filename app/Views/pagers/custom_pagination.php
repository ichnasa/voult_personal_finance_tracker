<?php
$pager->setSurroundCount(2);
$currentLimit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
?>
<div class="d-flex align-items-center justify-content-center flex-wrap gap-3 my-3">
  <ul class="pagination m-0">
    <?php if ($pager->hasPreviousPage()): ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPreviousPage() ?>">Previous</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled">
        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
      </li>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
      <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
        <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
      </li>
    <?php endforeach; ?>

    <?php if ($pager->hasNextPage()): ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getNextPage() ?>">Next</a>
      </li>
    <?php else: ?>
      <li class="page-item disabled">
        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
      </li>
    <?php endif; ?>
  </ul>

  <div>
    <select class="form-select form-select-sm" style="min-width: 110px;" onchange="
      const url = new URL(window.location.href);
      url.searchParams.set('limit', this.value);
      url.searchParams.delete('page');
      window.location.href = url.toString();
    ">
      <option value="8" <?= $currentLimit === 8 ? 'selected' : '' ?>>8 / page</option>
      <option value="16" <?= $currentLimit === 16 ? 'selected' : '' ?>>16 / page</option>
      <option value="32" <?= $currentLimit === 32 ? 'selected' : '' ?>>32 / page</option>
      <option value="64" <?= $currentLimit === 64 ? 'selected' : '' ?>>64 / page</option>
    </select>
  </div>
</div>
