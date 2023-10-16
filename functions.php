<?php
function theme_enqueue_style()
{
    wp_enqueue_style('mon-style', get_stylesheet_directory_uri() . '/styles.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');


?>