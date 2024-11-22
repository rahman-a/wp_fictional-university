<?php

$programs = new WP_Query([
    "posts_per_page" => -1,
    "post_type" => "program",
    "orderby" => "title",
    "order" => "ASC",
    "meta_query" => [
        [
            "key" => "related_campuses",
            "compare" => "LIKE",
            "value" => '"' . get_the_ID() . '"'
        ]
    ]
]);

get_header();

while (have_posts()) :
    the_post();
    page_banner()
?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link("campus") ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Campuses
                </a> <span class="metabox__main">
                    <?= the_title() ?>.
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?
            the_content();
            $mapLocation = get_field("campus_location");
            ?>
            <div class="acf-map">
                <div class="marker" data-lat="<?= $mapLocation["lat"] ?>" data-lng="<?= $mapLocation['lng'] ?>">
                    <p><?= the_title() ?></p>
                    <?= $mapLocation['address'] ?>
                </div>
            </div>
        </div>


        <!-- Reset Global Post() -->
        <?php wp_reset_postdata() ?>
        <!------------------------>

        <?php if ($programs->have_posts()) : ?>
            <hr class="section-break" />
            <h2 class="headline headline--medium"><?= the_title() ?> Programs</h2>
            <ul class="link-list min-list">
                <?php while ($programs->have_posts()) :
                    $programs->the_post(); ?>
                    <li><a href="<?= the_permalink() ?>"><?= the_title() ?></a></li>
                <?php endwhile ?>
            </ul>
        <?php endif ?>

    </div>
<?php endwhile;
wp_reset_postdata();
get_footer() ?>