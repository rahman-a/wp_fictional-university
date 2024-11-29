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
                <div class="two-thirds" style="display:flex; align-items: start; gap:2rem">
                    <div>
                        <?= the_content() ?>
                    </div>
                    <?php
                    $likes = new WP_Query([
                        "post_type" => "like",
                        "meta_query" => [
                            [
                                "key" => "liked_professors",
                                "compare" => "=",
                                "value" => get_the_ID()
                            ]
                        ]
                    ]);
                    $dataLiked = 'false';
                    if (is_user_logged_in()) {
                        $relatedLikes = new WP_Query([
                            "author" => get_current_user_id(),
                            "post_type" => "like",
                            "meta_query" => [
                                [
                                    "key" => "liked_professors",
                                    "compare" => "=",
                                    "value" => get_the_ID()
                                ]
                            ]
                        ]);

                        $dataLiked = $relatedLikes->found_posts ? 'true' : 'false';
                    }
                    ?>
                    <span class="like-box" data-liked="<?= $dataLiked ?>" data-professor="<?= get_the_ID() ?>"
                        data-like="<?= isset($relatedLikes->posts[0]) ? $relatedLikes->posts[0]->ID : '' ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count">
                            <?= $likes->found_posts ?>
                        </span>
                    </span>

                </div>
            </div>

        </div>
    </div>

<?php endwhile;
wp_reset_postdata();
get_footer() ?>