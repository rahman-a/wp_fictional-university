<?php
get_header();
page_banner([
    "title" => "Search"
])
?>

<div class="container container--narrow page-section">
    <div class="generic-content">
        <?= get_search_form() ?>
    </div>
</div>


<?php get_footer() ?>