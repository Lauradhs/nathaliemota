<html <?php language_attributes(); ?> class="html">


<?php

add_filter('show_admin_bar', '__return_true');


function theme_enqueue_style()
{
    wp_enqueue_style('mon-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');


function enregistrer_mon_menu() {
    register_nav_menu('menu-principal', __('Menu Principal'));
}
add_action('after_setup_theme', 'enregistrer_mon_menu');


?>