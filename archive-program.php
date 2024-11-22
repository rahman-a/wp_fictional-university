<?php
get_header();
page_banner([
    "title" => "Programs List"
])
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while (have_posts()) : ?>
            <?= the_post() ?>

            <li><a href="<?= the_permalink() ?>"><?= the_title() ?></a></li>


        <?php endwhile ?>
    </ul>
    <?php wp_reset_postdata(); ?>
    <?= paginate_links() ?>
</div>


<?php get_footer() ?>