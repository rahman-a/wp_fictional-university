<?php

$upcomingEvents = new WP_Query([
    "post_type" => "event",
    "meta_key" => "event_date",
    "orderby" => "meta_value_num",
    "order" => "ASC",
    "meta_query" => [
        [
            "key" => "event_date",
            "compare" => ">=",
            "value" => date("Ymd"), //today
            "type" => "numeric"
        ],
        [
            "key" => "related_programs",
            "compare" => "LIKE",
            "value" => '"' . get_the_ID() . '"'
        ]
    ]
]);

$professors = new WP_Query(
    [
        "posts_per_page" => 2,
        "post_type" => "professor",
        "orderby" => "title",
        "order" => "ASC",
        "meta_query" => [
            [
                "key" => "related_programs",
                "compare" => "LIKE",
                "value" => '"' . get_the_ID() . '"'
            ]
        ]
    ]
);

$campuses = get_field("related_campuses");

get_header();

while (have_posts()) :
    the_post();
    page_banner()
?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link("program") ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Programs
                </a> <span class="metabox__main">
                    <?= the_title() ?>.
                </span>
            </p>
        </div>
        <div class="generic-content"><?= the_content() ?></div>

        <!-- //////////////////////////////// PROFESSORS /////////////////////////////// -->

        <?php if ($professors->have_posts()) : ?>
            <hr class="section-break" />
            <h2 class="headline headline--medium"><?= the_title() ?> Professors</h2>
            <ul>
                <?php while ($professors->have_posts()) : ?>
                    <?= $professors->the_post() ?>
                    <li class="professor-card__list-item">
                        <a href="<?= the_permalink() ?>" class="professor-card">
                            <img src="<?= get_the_post_thumbnail_url(null, "landscape") ?>" alt="<?= the_title() ?>"
                                class="professor-card__image" />
                            <span class="professor-card__name"><?= the_title() ?></span>

                        </a>
                    </li>
                <?php endwhile ?>
            </ul>
        <?php endif ?>

        <!-- Reset Global Post() -->
        <?php wp_reset_postdata() ?>
        <!------------------------>

        <!-- //////////////////////////////// RELATED UPCOMFING EVENTS //////////////////////// -->

        <?php if ($upcomingEvents->have_posts()) : ?>
            <hr class="section-break" />
            <h2 class="headline headline--medium">Upcoming <?= the_title() ?> Events</h2>
            <?php while ($upcomingEvents->have_posts()) :
                $upcomingEvents->the_post();
                get_template_part("template-parts/content-event");
            endwhile ?>
        <?php endif ?>

        <!-- //////////////////////////////// RELATED CAMPUSES //////////////////////// -->

        <?php if ($campuses) : ?>
            <hr class="section-break" />
            <h2 class="headline headline--medium"><?= the_title() ?> <span
                    style="font-style: italic; font-weight: bold;">Campus</span></h2>
            <?php foreach ($campuses as $campus) : ?>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny">
                        <a href="<?= get_permalink($campus) ?>"><?= get_the_title($campus) ?></a>
                    </h5>
                </div>
            <?php endforeach ?>
        <?php endif ?>

        <!-- /////////////////////////////////////////////////////////////// -->

    </div>
<?php endwhile;
wp_reset_postdata();
get_footer() ?>