<?php
function theme_enqueue_style()
{
    wp_enqueue_style('parent-style', get_stylesheet_uri(), array(), '1.0');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');


