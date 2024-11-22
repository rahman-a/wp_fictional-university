<?php
return [
    [
        "id" => 13,
        "name" => "About us",
        "link" => site_url('/about-us'),
        "isActive" => is_page("About us") || $parentId === 13
    ],
    [
        "id" => 1384,
        "name" => "Programs",
        "link" => get_post_type_archive_link("program"),
        "isActive" => get_post_type() === 'program'
    ],
    [
        "id" => 1348,
        "name" => "Events",
        "link" => get_post_type_archive_link("event"),
        "isActive" => get_post_type() === 'event' || is_page("past-events")
    ],
    [
        "id" => 1351,
        "name" => "Campuses",
        "link" => get_post_type_archive_link("campus"),
        "isActive" => get_post_type() === 'campus'
    ],
    [
        "id" => 1336,
        "name" => "Blog",
        "link" => site_url('/blog'),
        "isActive" => get_post_type() === 'post'
    ],
];
