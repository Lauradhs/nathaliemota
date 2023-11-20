<?php get_header(); ?>

<?php
$args = array(
    'post_type' => 'photo',
    'posts_per_page' => 1,
    'orderby' => 'rand',
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    $query->the_post();
    $background_image_url = get_the_post_thumbnail_url();
endif;
wp_reset_postdata();
?>

<div class="hero-header" style="background-image: url('<?php echo esc_url($background_image_url); ?>');">
    <h1 class="hero-title"> Photographe Event</h1>
</div>

<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
    <?php
    if ($terms = get_terms('categorie', 'orderby=name')) :
        echo '<select name="categoryfilter"><option value="">Catégories</option>';
        foreach ($terms as $term) :
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        endforeach;
        echo '</select>';
    endif;
    ?>
    <input type="hidden" name="action" value="customfilter">
</form>




<div class="block-container" id="response">
    <?php
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => 1,
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) : ?>
        <ul class="publication-list">
            <?php
            $counter = 0; // Initialisation du compteur
            echo '<div class="row">'; // Ouvre la première ligne
            while ($the_query->have_posts()) : $the_query->the_post();
                get_template_part('templates_part/photo-block', 'photo');
                $counter++;
                // Ferme la ligne après chaque deuxième photo
                if ($counter % 2 == 0 && $counter < $the_query->post_count) {
                    echo '</div><div class="row">';
                }
            endwhile;
            echo '</div>'; // Ferme la dernière ligne
            ?>
        </ul>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>

    <div class="button">
        <a href="#!" id="load-more">
            <button class="btn-charger">Charger plus</button>
        </a>
    </div>
</div>
<?php get_footer(); ?>
