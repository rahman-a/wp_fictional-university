<?php

add_action("rest_api_init", "register_new_rest_field");
add_action("rest_api_init", 'register_new_rest_route');


function register_new_rest_field()
{
    register_rest_field("post", "author_name", [
        "get_callback" => function () {
            return get_the_author_meta("display_name");
        }
    ]);
};

function register_new_rest_route()
{
    register_rest_route("university/v1", "search", [
        "methods" => WP_REST_Server::READABLE,
        "callback" => "register_search_route"
    ]);
};


function register_search_route($query)
{
    $value = sanitize_text_field($query['keyword']);
    $mainQuery = new WP_Query([
        "post_type" => ["post", 'page', 'program', 'professor', 'event', 'campus'],
        "s" => $value
    ]);

    $results = [
        "generalInfo" => [],
        "professors" => [],
        "programs" => [],
        "campuses" => [],
        "events" => []
    ];

    $programsIDs = [];

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() === 'post' || get_post_type() === 'page') {
            array_push($results['generalInfo'], [
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "type" => get_post_type(),
                "author_name" => get_the_author()
            ]);
        }

        if (get_post_type() === 'professor') {
            array_push($results['professors'], [
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "image" => ["url" => get_the_post_thumbnail_url(null, "landscape")],
                "type" => get_post_type()
            ]);
        }


        if (get_post_type() === 'program') {
            $campuses = get_field("related_campuses");
            if ($campuses) {
                foreach ($campuses as $campus) {
                    array_push($results['campuses'], [
                        "title" => get_the_title($campus),
                        "permalink" => get_the_permalink($campus),
                        "type" => get_post_type($campus)
                    ]);
                }
            }
            array_push($results['programs'], [
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "type" => get_post_type(),
            ]);
            array_push($programsIDs, get_the_ID());
        }

        if (get_post_type() === 'campus') {
            array_push($results['campuses'], [
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "type" => get_post_type()
            ]);
        }

        if (get_post_type() === 'event') {
            array_push($results['events'], [
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "type" => get_post_type(),
                "description" => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20),
                "month" => format_date(get_field("event_date"), 'M'),
                "day" => format_date(get_field("event_date"), 'd')
            ]);
        }
    }

    if ($programsIDs) {
        $relationProgramsMetaQuery = ["relation" => "OR"];
        foreach ($programsIDs as $id) {
            array_push($relationProgramsMetaQuery, [
                "key" => "related_programs",
                "compare" => "LIKE",
                "value" => '"' . $id . '"'
            ]);
        }
        $relationPrograms = new WP_Query([
            "post_type" => ["professor", "event"],
            "meta_query" => $relationProgramsMetaQuery
        ]);

        while ($relationPrograms->have_posts()) {
            $relationPrograms->the_post();
            if (get_post_type() === 'professor') {
                array_push($results['professors'], [
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "image" => ["url" => get_the_post_thumbnail_url(null, "landscape")],
                    "type" => get_post_type()
                ]);
            }
            if (get_post_type() === 'event') {
                array_push($results['events'], [
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "type" => get_post_type(),
                    "description" => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20),
                    "month" => format_date(get_field("event_date"), 'M'),
                    "day" => format_date(get_field("event_date"), 'd')
                ]);
            }
        }
    }

    $results['professors'] = array_unique($results['professors'], SORT_REGULAR);
    $results['events'] = array_unique($results['events'], SORT_REGULAR);
    $results['campuses'] = array_unique($results['campuses'], SORT_REGULAR);

    return $results;
}
