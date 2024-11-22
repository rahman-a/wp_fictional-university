<?php
get_header();
$parentId = wp_get_post_parent_id(get_the_ID()); // if page is children? => will have a parent id
$haveChildren = get_pages([
    "child_of" => get_the_ID()
]); // if page is parent? => will have a children pages

while (have_posts()) :
    the_post();
    page_banner()
?>

    <div class="container container--narrow page-section">
        <?php if ($parentId) : ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?= get_the_permalink($parentId) ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($parentId) ?>
                    </a> <span class="metabox__main"><?= the_title() ?></span>
                </p>
            </div>
        <?php endif ?>

        <?php if ($haveChildren or $parentId) : ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="#">About Us</a></h2>
                <ul class="min-list">
                    <?= wp_list_pages([
                        "title_li" => NULL,
                        "child_of" => $parentId ? $parentId : get_the_ID(),
                        "sort_column" => "menu_order"
                    ]) ?>
                </ul>
            </div>
        <?php endif ?>
        <div class="generic-content">
            <?= the_content() ?>
        </div>
    </div>
<?php endwhile;
wp_reset_postdata();
get_footer() ?>