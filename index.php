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

<div class="form-filters">

<!-- Formulaire Catégorie -->
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" class="filter-form" id="filter">
    <?php
    if ($terms = get_terms('categorie', 'orderby=name')) :
        echo '<select name="categoryfilter" class="categorie"><option value="">Catégories</option>';
        foreach ($terms as $term) :
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        endforeach;
        echo '</select>';
    endif;
    ?>
    <input type="hidden" name="action" value="customfilter">
</form>

<!-- Formulaire Format -->
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" class="filter-form" id="filterf">
    <?php
    if ($terms = get_terms('format', 'orderby=name')) :
        echo '<select name="formatfilter" class="format"><option value="">Formats</option>';
        foreach ($terms as $term) :
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        endforeach;
        echo '</select>';
    endif;
    ?>
    <input type="hidden" name="action" value="customfilter">
</form>

<!-- Formulaire Année -->
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" class="filter-form" id="yearfilter">
    <?php
    global $wpdb;
    $years = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_type = 'photo' AND post_status = 'publish' ORDER BY post_date DESC"
        )
    );

    echo '<select name="yearfilter" class="annee"><option value="">Année</option>';
    foreach ($years as $year) :
        echo '<option value="' . $year . '">' . $year . '</option>';
    endforeach;
    echo '</select>';
    ?>
    <input type="hidden" name="action" value="customfilter">
</form>
</div>



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
            while ($the_query->have_posts()) : $the_query->the_post();
                ?>
                <li>
                    <?php get_template_part('templates_part/photo-block', 'photo'); ?>
                </li>
                <?php
            endwhile;
            ?>
        </ul>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>



    <div class="button">
        <a href="#!" id="load-more">
            <button class="btn-charger">Charger plus</button>
        </a>
    </div>
</div>
<?php get_footer(); ?>
