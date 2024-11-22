<?php
$parentId = wp_get_post_parent_id(0); // 0 means get the current post or page id which is equal to get_the_ID()
$menus = require(get_template_directory() . '/menus.php');
?>

<!DOCTYPE html>
<html <?= language_attributes() ?>>

<head>
    <meta name="charset" content="<?= bloginfo("charset") ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= wp_head() ?>
</head>

<body <?= body_class() ?>>

    <header class="site-header">
        <div class="container">
            <h1 class="school-logo-text float-left">
                <a href="<?= site_url("/") ?>"><strong>Fictional</strong> University</a>
            </h1>
            <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search"
                    aria-hidden="true"></i></span>
            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
            <div class="site-header__menu group">
                <nav class="main-navigation">
                    <!-- <?php
                            wp_nav_menu([
                                "theme_location" => "headerNavMenu"
                            ])
                            ?> -->
                    <ul>
                        <?php foreach ($menus as $menu): ?>
                            <li class="<?= $menu["isActive"] ? 'current-menu-item' : '' ?>">
                                <a href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a>
                            </li>
                        <? endforeach ?>
                    </ul>
                </nav>
                <div class="site-header__util">
                    <a href="#" class="btn btn--small btn--orange float-left push-right">Login</a>
                    <a href="#" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
                    <span class="search-trigger js-search-trigger"><i class="fa fa-search"
                            aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
    </header>