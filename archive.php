<?php
get_header();


// if you want to customize the formatting of the output of the archive
//  title you can this if statement as below
// but if you statifiy with wordpress format, you can use the_archive_title()
$title = "";

if (is_category()) {
    $title = "Category: " . get_the_category()[0]->name;
} else if (is_author()) {
    $title = "Author: " . get_the_author();
} else if (is_date()) {
    $title = "Year: " . get_the_date("Y");
}

page_banner([
    "title" => get_the_archive_title(),
    "subtitle" => get_the_archive_description()
])

?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) : ?>
        <?= the_post() ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?= site_url("/blog") ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Blog
                </a>
            </p>
        </div>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title">
                <a href="<?= the_permalink() ?>"><?= the_title() ?></a>
            </h2>
            <div class="metabox">
                <p>Posted by <?= get_the_author_posts_link() ?> on <?= the_time("d.M.y") ?> in
                    <?= get_the_category_list(", ") ?>.</p>
            </div>
            <div class="generic-content">
                <?= the_excerpt() ?>
                <p>
                    <a class="btn btn--blue" href="<?= the_permalink() ?>">Continue reading &raquo;</a>
                </p>
            </div>
        </div>


    <?php endwhile ?>
    <?php wp_reset_postdata(); ?>
    <?= paginate_links() ?>


</div>


<?php get_footer() ?>