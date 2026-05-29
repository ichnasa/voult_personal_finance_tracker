<?php $pager->setSurroundCount(2) ?>

<ul class="pagination m-0 mx-auto">
	<?php if ($pager->hasPreviousPage()) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getPreviousPage() ?>" tabindex="-1">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M15 6l-6 6l6 6" /></svg>
			</a>
		</li>
	<?php else : ?>
		<li class="page-item disabled">
			<a class="page-link" href="#" tabindex="-1" aria-disabled="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M15 6l-6 6l6 6" /></svg>
			</a>
		</li>
	<?php endif ?>

	<?php foreach ($pager->links() as $link) : ?>
		<li class="page-item <?= $link['active'] ? 'active' : '' ?>">
			<a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
		</li>
	<?php endforeach ?>

	<?php if ($pager->hasNextPage()) : ?>
		<li class="page-item">
			<a class="page-link" href="<?= $pager->getNextPage() ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M9 6l6 6l-6 6" /></svg>
			</a>
		</li>
	<?php else : ?>
		<li class="page-item disabled">
			<a class="page-link" href="#" tabindex="-1" aria-disabled="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M9 6l6 6l-6 6" /></svg>
			</a>
		</li>
	<?php endif ?>
</ul>
