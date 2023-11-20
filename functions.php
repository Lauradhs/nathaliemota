<?php

add_filter('show_admin_bar', '__return_true');


function theme_enqueue_style()
{
    wp_enqueue_style('mon-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_style');



function theme_enqueue_scripts() {
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

function load_custom_fonts() {
    wp_enqueue_style('custom-fonts', get_template_directory_uri() . '/fonts.css');
}
add_action('wp_enqueue_scripts', 'load_custom_fonts');

function enregistrer_mon_menu() {
    register_nav_menu('menu-principal', __('Menu Principal'));
}
add_action('after_setup_theme', 'enregistrer_mon_menu');

add_image_size('custom-thumbnail', 564, 495, true);

function weichie_ajax_handler() {
    $args = array(
      'post_type' => 'photo',
      'posts_per_page' => 12,
      'orderby' => 'date',
      'order' => 'DESC',
      'paged' => $_POST['paged'],
    );
  
    // Ajouter la catégorie aux arguments si elle est définie
    if (isset($_POST['category'])) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'categorie', 
          'field'    => 'id',
          'terms'    => $_POST['category'],
        ),
      );
    }
  
    $ajaxposts = new WP_Query($args);
  
    if ($ajaxposts->have_posts()) :
      $counter = 0; // Initialisation du compteur
      ob_start(); // Commence à mettre en mémoire tampon la sortie
      echo '<ul class="publication-list">';
      echo '<div class="row">'; // Ouvre la première ligne
  
      while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
        get_template_part('templates_part/photo-block', 'photo');
  
        $counter++;
        // Ferme la ligne après chaque deuxième photo
        if ($counter % 2 == 0 && $counter < $ajaxposts->post_count) {
          echo '</div><div class="row">';
        }
      endwhile;
  
      echo '</div>'; // Ferme la dernière ligne
      echo '</ul>';
      $output = ob_get_clean(); // Récupère le contenu mis en mémoire tampon et nettoie la mémoire tampon
  
      echo json_encode(array('content' => $output, 'max_pages' => $ajaxposts->max_num_pages));
  
    endif;
  
    wp_reset_postdata();
    exit;
  }
  
  add_action('wp_ajax_weichie_ajax_handler', 'weichie_ajax_handler');
  add_action('wp_ajax_nopriv_weichie_ajax_handler', 'weichie_ajax_handler');
  
