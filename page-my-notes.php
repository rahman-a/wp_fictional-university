<?php

if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url("/")));
}

get_header();
page_banner([
    "title" => "Notes"
]);

$notes = new WP_Query([
    "post_type" => "note",
    "posts_per_page" => -1,
    "author" => get_current_user_id()
]);

?>

<div class="container container--narrow page-section">

    <div class="create-note">
        <h2 class="headline headline--medium ">Create New Note</h2>
        <input type="text" name="title" class="new-note-title" placeholder="Title">
        <textarea name="content" class="new-note-body" placeholder="Your note here...."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">You've reached your limits of creating new notes</span>
    </div>
    <ul id="notes-list">

        <?php
        if ($notes->have_posts()) :
            while ($notes->have_posts()) :
                $notes->the_post()
        ?>
                <li style="list-style: none;" data-id="<?= get_the_ID() ?>">
                    <input readonly type="text" name="title" class="note-title-field" id="note-title"
                        value="<?= str_replace('Private: ', '', esc_attr(get_the_title())) ?>">
                    <span class="edit-note" aria-hidden="true" name="edit"><i class="fa fa-pencil"></i></span>
                    <span class="delete-note" aria-hidden="true" name="delete"> <i class="fa fa-trash-o"></i></span>
                    <textarea readonly class="note-body-field"
                        name="content"><?= esc_attr(wp_strip_all_tags(get_the_content())) ?></textarea>
                    <span class="update-note btn btn--blue btn--small" aria-hidden="true" name="save"> <i
                            class="fa fa-arrow-right"></i></span>
                </li>

        <?php
            endwhile;
        else :
            echo "<h3>No Notes Found</h3>";
        endif; ?>
    </ul>
    <?php
    wp_reset_postdata();
    paginate_links();
    ?>
</div>


<?php get_footer() ?>