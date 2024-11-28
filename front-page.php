<?php
$today = date('Ymd');

$latestPosts = new WP_Query([
    "posts_per_page" => 2
]);

$latestEvents = new WP_Query([
    "posts_per_page" => 2,
    "post_type" => "event",
    "meta_key" => "event_date",
    "orderby" => "meta_value_num",
    "order" => "ASC",
    "meta_query" => [
        [
            "key" => "event_date",
            "compare" => ">=",
            "value" => $today,
            "type" => "numeric"
        ]
    ]
]);


get_header()


?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?= get_theme_file_uri("images/library-hero.jpg") ?>)"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">
            We think you&rsquo;ll like it here.
        </h2>
        <h3 class="headline headline--small">
            Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
            interested in?
        </h3>
        <a href="<?= get_post_type_archive_link("program") ?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

<div class="full-width-split group">
    <?php if ($latestEvents->have_posts()) : ?>
        <div class="full-width-split__one">
            <div class="full-width-split__inner">
                <h2 class="headline headline--small-plus t-center">
                    Upcoming Events
                </h2>

                <?php while ($latestEvents->have_posts()) :
                    $latestEvents->the_post();
                    get_template_part("template-parts/content-event");
                endwhile ?>
                <?php wp_reset_postdata(); ?>
                <p class="t-center no-margin">
                    <!-- will use get_post_type_archive_link instead of site_url in case of 
            change the slug in the future, this function will get the new slug -->
                    <a href="<?= get_post_type_archive_link("event") ?>" class="btn btn--blue">View All Events</a>
                </p>
            </div>
        </div>
    <?php endif ?>
    <div class="full-width-split__two">
        <div class="full-width-split__inner" style="margin:<?php echo $latestEvents->have_posts() ? '0' : 'auto' ?>;">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

            <?php while ($latestPosts->have_posts()) : ?>
                <?= $latestPosts->the_post() ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?= the_permalink() ?>">
                        <span class="event-summary__month"><?= the_time("M") ?></span>
                        <span class="event-summary__day"><?= the_time("d") ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny">
                            <a href="<?= the_permalink() ?>"><?= the_title() ?></a>
                        </h5>
                        <p>
                            <?= has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20) ?>
                            <a href="<?= the_permalink() ?>" class="nu gray">Read more</a>
                        </p>
                    </div>
                </div>
            <?php endwhile ?>
            <?php wp_reset_postdata(); ?>
            <p class="t-center no-margin">
                <a href="<?= site_url("/blog") ?>" class="btn btn--yellow">View All Blog Posts</a>
            </p>
        </div>
    </div>
</div>

<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <div class="hero-slider__slide" style="background-image: url(<?= get_theme_file_uri("images/bus.jpg") ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">
                            Free Transportation
                        </h2>
                        <p class="t-center">
                            All students have free unlimited bus fare.
                        </p>
                        <p class="t-center no-margin">
                            <a href="#" class="btn btn--blue">Learn more</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?= get_theme_file_uri("images/apples.jpg") ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">
                            An Apple a Day
                        </h2>
                        <p class="t-center">
                            Our dentistry program recommends eating apples.
                        </p>
                        <p class="t-center no-margin">
                            <a href="#" class="btn btn--blue">Learn more</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?= get_theme_file_uri("images/bread.jpg") ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">Free Food</h2>
                        <p class="t-center">
                            Fictional University offers lunch plans for those in need.
                        </p>
                        <p class="t-center no-margin">
                            <a href="#" class="btn btn--blue">Learn more</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>

<?php get_footer() ?>