<?php
$relatedPrograms = get_field('related_programs');

get_header()

?>
<?php while (have_posts()) : ?>
    <?php
    the_post();
    page_banner()
    ?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('event') ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Events
                </a>
            </p>
        </div>
        <div class="generic-content"><?= the_content() ?></div>
        <?php if ($relatedPrograms) : ?>
            <hr class="section-break" />
            <h2 class="headline headline--medium">Related Programs</h2>
            <ul class="link-list min-list">
                <?php foreach ($relatedPrograms as $program): ?>
                    <li><a href="<?= get_the_permalink($program) ?>"><?= get_the_title($program) ?></a></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </div>
<?php endwhile;
wp_reset_postdata();
get_footer() ?>