<?php

$bannerBg = get_field('page_banner_background_image');

get_header();

while (have_posts()) :
    the_post();
    page_banner()
?>

<div class="container container--narrow page-section">

    <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?= the_post_thumbnail('portrait') ?>
            </div>
            <div class="two-thirds"><?= the_content() ?></div>
        </div>

    </div>
</div>

<?php endwhile;
wp_reset_postdata();
get_footer() ?>