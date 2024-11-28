<?php

require get_theme_file_path("./inc/utils.php");
require get_theme_file_path('./inc/wp-rest-api.php');


function page_banner(array $args = null)
{
    if (!isset($args['title'])) $args['title'] = get_the_title();

    if (!isset($args['subtitle']) && get_field("page_banner_subtitle") && !is_archive() && !is_home()) {
        $args['subtitle'] = get_field("page_banner_subtitle");
    };

    if (!isset($args['image'])) {

        $args['image'] = (get_field('page_banner_background_image') && !is_archive() && !is_home())

            ? get_field('page_banner_background_image')['sizes']['banner']

            : get_theme_file_uri("images/ocean.jpg");
    }
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= $args['image'] ?>)">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?= $args['title'] ?? '' ?></h1>
            <div class="page-banner__intro">
                <p>
                    <?= $args['subtitle'] ?? '' ?>
                </p>
            </div>
        </div>
    </div>
<?php }



// university is the website name and could be any other name ex: swtle
function load_university_files()
{
    wp_enqueue_script("university_google_map", "//maps.googleapis.com/maps/api/js?key={$_ENV['API_KEY']}");
    wp_enqueue_script("university_main_script", get_theme_file_uri("build/index.js"), array("jquery"), "1.0", true);
    wp_enqueue_style("university_base_style", get_theme_file_uri("build/index.css"));
    wp_enqueue_style("university_main_style", get_theme_file_uri("build/style-index.css"));
    wp_enqueue_style("university_font_awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_font_roboto", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_localize_script("university_main_script", "universityData", [
        "root_url" => get_site_url(),
        "nonce" => wp_create_nonce('wp_rest')
    ]);
};

add_action("wp_enqueue_scripts", "load_university_files");

function load_university_features()
{
    // register_nav_menu("headerNavMenu", "Header Nav Location");
    // register_nav_menu("footerMenuOne", "Footer Location One");
    // register_nav_menu("footerMenuTwo", "Footer Location Two");
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_image_size("landscape", 400, 260, true);
    add_image_size("portrait", 480, 650, true);
    add_image_size("banner", 1500, 350, true);
}

add_action("after_setup_theme", "load_university_features");

function university_query_manipulation($query)
{
    // make sure map will show all campuses not limited to 10 (default by wordpress) 
    client_query_manipulation($query, "campus", [
        "posts_per_page" => -1
    ]);

    client_query_manipulation($query, "program", [
        "orderby" => "title",
        "order" => "ASC"
    ]);

    client_query_manipulation(
        $query,
        "event",
        [
            "orderby" => "meta_value_num",
            "meta_key" => "event_date",
            "meta_query" => [
                "key" => "event_date",
                "compare" => ">=",
                "value" => date("Ymd"), // today
                "type" => "numeric"
            ],
            "order" => "ASC"
        ],

    );
};

add_action("pre_get_posts", "university_query_manipulation");


function client_query_manipulation(WP_Query $query, string $post_type, array $meta_queries)
{
    if (is_admin()) return;
    if (!is_main_query()) return;
    if (!is_post_type_archive($post_type)) return;
    foreach ($meta_queries as $x => $y) {
        $query->set($x, $y);
    }
}

function university_google_map_key($api)
{
    $api['key'] = $_ENV['API_KEY'];
    return $api;
}

add_filter("acf/fields/google_map/api", "university_google_map_key");

/////////////// LIMITED SEARCH TO TITLE AND NOT BODY CONTENT /////////////
function __search_by_title_only($search, $wp_query)
{
    global $wpdb;

    if (empty($search))
        return $search; // skip processing - no search term in query

    $q = $wp_query->query_vars;
    $n = ! empty($q['exact']) ? '' : '%';

    $search = '';
    $searchand = '';

    foreach ((array) $q['search_terms'] as $term) {
        $term = esc_sql(like_escape($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }

    if (! empty($search)) {
        $search = " AND ({$search}) ";
        if (! is_user_logged_in())
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }

    return $search;
}

add_filter('posts_search', '__search_by_title_only', 500, 2);


function adminControlBar()
{
    // get the current user
    $currentUser = wp_get_current_user();
    // check the current user role
    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        // disable wp admin bar if user role is subscriber
        show_admin_bar(false);
    }
}

add_filter('wp_loaded', 'adminControlBar');



function redirectSubscriberToFrontpage()
{
    // get the current user
    $currentUser = wp_get_current_user();
    // check the current user role
    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        // disable wp admin bar if user role is subscriber
        wp_redirect(site_url("/"));
    }
}

add_filter('admin_init', 'redirectSubscriberToFrontpage');


function loginCssStyle()
{
    wp_enqueue_style("university_base_style", get_theme_file_uri("build/index.css"));
    wp_enqueue_style("university_main_style", get_theme_file_uri("build/style-index.css"));
    wp_enqueue_style("university_font_awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_font_roboto", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
};

add_action("login_enqueue_scripts", "loginCssStyle");


function loginHeaderTitle()
{
    return get_bloginfo('name');
}

add_filter("login_headertitle", "loginHeaderTitle");

function loginHeaderUrl()
{
    return esc_url(site_url("/"));
}

add_filter("login_headerurl", "loginHeaderUrl");

function customize_note_data($data, $post)
{
    // strip title and content from html tags 
    if ($data['post_type'] === 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 4 && !$post['ID']) {
            http_response_code(403);
            die(json_encode(["message" => "You've reached your limit of creating 5 posts"]));
        }
        $data['post_title'] = sanitize_text_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }

    // force post status to private to all notes so it can't be access by rest api
    if ($data["post_type"] === 'note' && $data['post_status'] !== 'trash') {
        $data['post_status'] = 'private';
    }

    return $data;
}

add_filter("wp_insert_post_data", "customize_note_data", 10, 2);
