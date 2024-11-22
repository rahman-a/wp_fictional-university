<?php
get_header();
page_banner([
    "title" => "Campuses List"
]);
?>

<div class="container container--narrow page-section">

    <div class="acf-map">
        <?php while (have_posts()) : ?>
        <?=
            the_post();
            $mapLocation = get_field("campus_location");
            ?>
        <div class="marker" data-lat="<?= $mapLocation['lat'] ?>" data-lng="<?= $mapLocation['lng'] ?>">
            <h3><a href="<?= the_permalink() ?>"><?= the_title() ?></a></h3>
            <?= $mapLocation['address'] ?>
        </div>
        <?php endwhile ?>
    </div>

    <?php wp_reset_postdata() ?>

</div>

<?php get_footer() ?>