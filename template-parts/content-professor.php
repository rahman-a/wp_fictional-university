<div class="professor-card__list-item mt-8">
    <a href="<?= the_permalink() ?>" class="professor-card">
        <img src="<?= the_post_thumbnail_url("landscape") ?>" alt="<?= the_title() ?>" class="professor-card__image" />
        <span class="professor-card__name">
            <?= the_title() ?>
        </span>
    </a>
</div>`