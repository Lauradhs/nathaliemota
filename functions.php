<?php

add_filter('show_admin_bar', '__return_true');


function theme_enqueue_style()
{
    wp_enqueue_style('mon-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');



function theme_enqueue_scripts()
{
    wp_enqueue_script('lightbox-scripts', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), null, true);
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');


function load_custom_fonts()
{
    wp_enqueue_style('custom-fonts', get_template_directory_uri() . '/fonts.css');
}
add_action('wp_enqueue_scripts', 'load_custom_fonts');

function enregistrer_mon_menu()
{
    register_nav_menu('menu-principal', __('Menu Principal'));
}
add_action('after_setup_theme', 'enregistrer_mon_menu');

add_image_size('custom-thumbnail', 564, 495, true);

function weichie_ajax_handler()
{
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $_POST['paged'],
    );

    // Ajouter la condition pour filtrer par année
    if (isset($_POST['yearfilter']) && $_POST['yearfilter'] !== '') {
        $args['date_query'] = array(
            array(
                'year' => sanitize_text_field($_POST['yearfilter']),
            ),
        );
    }


    // Ajouter la catégorie aux arguments si elle est définie
    if (isset($_POST['category']) && $_POST['category'] !== '') {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field' => 'id',
            'terms' => $_POST['category'],
        );
    }

    // Ajouter le format aux arguments s'il est défini
    if (isset($_POST['format']) && $_POST['format'] !== '') {
        if (!isset($args['tax_query'])) {
            $args['tax_query'] = array();
        }

        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'id',
            'terms' => $_POST['format'],
        );
    }

    $ajaxposts = new WP_Query($args);

    if ($ajaxposts->have_posts()):
        $counter = 0;
        ob_start();


        while ($ajaxposts->have_posts()):
            $ajaxposts->the_post(); ?>
            <li class="new-images">
                <?php get_template_part('templates_part/photo-block', 'photo'); ?>
            </li>
            <?php
            $counter++;
            if ($counter % 2 == 0 && $counter < $ajaxposts->post_count) {
            }
        endwhile;


        $output = ob_get_clean();

        echo json_encode(array('content' => $output, 'max_pages' => $ajaxposts->max_num_pages));

    else:
        echo json_encode(array('content' => '<p>Aucune image correspondante</p>', 'max_pages' => 1));

    endif;

    wp_reset_postdata();
    exit;
}

add_action('wp_ajax_weichie_ajax_handler', 'weichie_ajax_handler');
add_action('wp_ajax_nopriv_weichie_ajax_handler', 'weichie_ajax_handler');

// Enregistrement du script lightbox.js
/* function enqueue_lightbox_script() {
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox_script'); */

// Enregistrement d'un script pour script.js qui dépend de lightbox-script
/* function enqueue_custom_script() {
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'lightbox-script'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script'); */

// Passer la fonction addLightboxEvent à script.js
function pass_add_lightbox_event_to_script()
{
    wp_localize_script('custom-script', 'addLightboxEvent', array(
        'function' => 'addLightboxEvent',
    )
    );
}
add_action('wp_enqueue_scripts', 'pass_add_lightbox_event_to_script');