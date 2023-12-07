<?php

add_filter('show_admin_bar', '__return_true');


function theme_enqueue_style() {
    wp_enqueue_style('mon-style', get_stylesheet_directory_uri().'/style.css', array(), '1.0', 'all');
    wp_enqueue_style('calque-photo', get_stylesheet_directory_uri().'/css/calque-photo.css', array(), '1.0', 'all');
    wp_enqueue_style('lightbox', get_stylesheet_directory_uri().'/css/lightbox.css', array(), '1.0', 'all');
    wp_enqueue_style('accueil', get_stylesheet_directory_uri().'/css/accueil.css', array(), '1.0', 'all');
    wp_enqueue_style('single-photo', get_stylesheet_directory_uri().'/css/single-photo.css', array(), '1.0', 'all');
    wp_enqueue_style('modale-contact', get_stylesheet_directory_uri().'/css/modale-contact.css', array(), '1.0', 'all');
    wp_enqueue_style('header-footer', get_stylesheet_directory_uri().'/css/header-footer.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');



function theme_enqueue_scripts() {
    wp_enqueue_script('lightbox-scripts', get_template_directory_uri().'/js/lightbox.js', array('jquery'), null, true);
    wp_enqueue_script('script', get_template_directory_uri().'/js/script.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');


function load_custom_fonts() {
    wp_enqueue_style('custom-fonts', get_template_directory_uri().'/fonts.css');
}
add_action('wp_enqueue_scripts', 'load_custom_fonts');

function enregistrer_mon_menu() {
    register_nav_menu('menu-principal', __('Menu Principal'));
}
add_action('after_setup_theme', 'enregistrer_mon_menu');

add_image_size('custom-thumbnail', 564, 495, true);

include_once get_template_directory().'/ajax/ajax.php';

?>