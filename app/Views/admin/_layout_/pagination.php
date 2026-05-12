<?php $pager->setSurroundCount(2) ?>

<ul class="pagination no-margin justify-content-center" style="padding-right: 0px;">
    <?php if ($pager->hasPreviousPage()) : ?>
        <li class="page-item">
            <a href="<?= $pager->getFirst() ?>" class="page-link" onclick="showPage(this.href); return false;">
                <?= lang('Pager.first') ?>
            </a>
        </li>
        <li class="page-item">
            <a href="<?= $pager->getPreviousPage() ?>" class="page-link" onclick="showPage(this.href); return false;">
                <?= lang('Pager.previous') ?>
            </a>
        </li>
    <?php else: ?>
        <li class="page-item disabled">
            <span class="page-link"><?= lang('Pager.first') ?></span>
        </li>
        <li class="page-item disabled">
            <span class="page-link"><?= lang('Pager.previous') ?></span>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
            <a href="<?= $link['uri'] ?>" class="page-link" onclick="showPage(this.href); return false;">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNextPage()) : ?>
        <li class="page-item">
            <a href="<?= $pager->getNextPage() ?>" class="page-link" onclick="showPage(this.href); return false;">
                <?= lang('Pager.next') ?>
            </a>
        </li>
        <li class="page-item">
            <a href="<?= $pager->getLast() ?>" class="page-link" onclick="showPage(this.href); return false;">
                <?= lang('Pager.last') ?>
            </a>
        </li>
    <?php else: ?>
        <li class="page-item disabled">
            <span class="page-link"><?= lang('Pager.next') ?></span>
        </li>
        <li class="page-item disabled">
            <span class="page-link"><?= lang('Pager.last') ?></span>
        </li>
    <?php endif ?>
</ul>