<?php
get_header();
page_banner([
    "title" => "Search Result",
    "subtitle" =>  "You searched for &ldquo;" . get_search_query() . "&rdquo;"
])
?>

<div class="container container--narrow page-section">
    <?php
    if (have_posts()) {
        while (have_posts()) :
            the_post();
            get_template_part("template-parts/content", get_post_type());
        endwhile;
        wp_reset_postdata();
        paginate_links();
    } else {
        echo "<h2 class='headline headline--small-plus'>No Results matchs the search</h2>";
    }
    ?>
    <div class="generic-content" style="margin-top:2rem">
        <?= get_search_form() ?>
    </div>
</div>


<?php get_footer() ?>