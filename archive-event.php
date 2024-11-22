<?php
get_header();
page_banner([
    "title" => "Events List"
]);
?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) :
        the_post();
        get_template_part("template-parts/content", get_post_type());
    endwhile ?>
    <?php wp_reset_postdata(); ?>
    <?= paginate_links() ?>
    <hr class="section-break" />
    <p>Looking for a recap of past events <a href="<?= site_url("/past-events") ?>">check out our past events
            archive</a></p>
</div>


<?php get_footer() ?>