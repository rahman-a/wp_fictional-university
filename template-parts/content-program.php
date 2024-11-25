<div class="post-item mt-8">
    <h2 class="headline headline--medium headline--post-title">
        <a href="<?= the_permalink() ?>"><?= the_title() ?></a>
    </h2>
    <div class="generic-content">
        <?= the_excerpt() ?>
        <p>
            <a class="btn btn--blue" href="<?= the_permalink() ?>">View Program &raquo;</a>
        </p>
    </div>
</div>