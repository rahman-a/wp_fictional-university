<?php
$today = date("Ymd");
$pastEvents = new WP_Query([
    "paged" => get_query_var("paged", 1),
    "post_type" => "event",
    "meta_key" => "event_date",
    "orderby" => "meta_value_num",
    "order" => "ASC",
    "meta_query" => [
        [
            "key" => "event_date",
            "compare" => "<",
            "value" => $today,
            "type" => "numeric"
        ]
    ]
]);

get_header();
page_banner([
    "title" => "Past Events"
])
?>

<div class="container container--narrow page-section">

    <?php while ($pastEvents->have_posts()) :
        $pastEvents->the_post();
        get_template_part("template-parts/content-event");
    endwhile ?>

    <?php wp_reset_postdata(); ?>

    <?= paginate_links([
        "total" => $pastEvents->max_num_pages
    ]) ?>

</div>


<?php get_footer() ?>