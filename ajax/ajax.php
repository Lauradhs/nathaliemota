<?php
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

?>