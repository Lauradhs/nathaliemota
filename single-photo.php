<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php while (have_posts()):
            the_post(); ?>
            <div class="post-wrapper">
                <div class="column-left">

                    <div class="post-info">
                        <!-- Afficher le titre du post -->
                        <div class="post-title">
                            <?php the_title(); ?>
                        </div>

                        <!-- Afficher la taxonomie "Format" -->
                        <?php
                        $formats = get_the_terms(get_the_ID(), 'format');
                        if ($formats && !is_wp_error($formats)) {
                            echo '<div class="post-formats">';
                            echo '<strong>Format : </strong>';
                            foreach ($formats as $format) {
                                echo '' . esc_html($format->name) . '';
                            }
                            echo '</div>';
                        }
                        ?>

                        <!-- Afficher la taxonomie "Catégorie" -->
                        <?php
                        $categories = get_the_terms(get_the_ID(), 'categorie');
                        if ($categories && !is_wp_error($categories)) {
                            echo '<div class="post-categories">';
                            echo '<strong>Catégorie : </strong>';
                            foreach ($categories as $categorie) {
                                echo '' . esc_html($categorie->name) . '';
                            }
                            echo '</div>';
                        }
                        ?>

                        <!-- Afficher le champ personnalisé "Type" -->
                        <?php $type = get_field('type');
                        if ($type) {
                            echo '<div class="post-type">';
                            echo '<strong>Type : </strong> ' . esc_html($type);
                            echo '</div>';
                        }
                        ?>

                        <!-- Afficher le champ personnalisé "Référence" -->
                        <?php $reference = get_field('référence');
                        if ($reference) {
                            echo '<div class="post-ref" id="ref">';
                            echo '<strong>Référence : </strong> <p class="num-ref"> ' . esc_html($reference) . '</p>' ;
                            echo '</div>';
                        }
                        ?>

                        <!-- Afficher l'année de création du post -->
                        <div class="post-year">
                            <strong>Année :</strong>
                            <?php the_time('Y'); ?>
                        </div>
                    </div>
                </div>
                <div class="column-right">
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large', ['class' => 'custom-thumbnail']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
    </main>
</div>

<div class="under-post">
    <div class="post-contact">
        <div class="post-interest">
            <p> Cette photo vous intéresse ?</p>
        </div>
        <button class="btn-contact">Contact</button>
    </div>
    <div class="site__navigation">
        <?php
        // Récupère l'ID du post actuel
        $current_post_id = get_the_ID();
        // Récupère le post précédent
        $previous_post = get_adjacent_post(false, '', true);
        // Récupère le post suivant
        $next_post = get_adjacent_post(false, '', false);
        ?>
        <div class="post-thumbnail">
            <div id="hover-image">

                <!-- Photo du post suivant -->
                <div class="next-thumbnail">
                    <?php echo get_the_post_thumbnail($next_post, 'thumbnail'); ?>
                </div>
            </div>
        </div>
        <div class="thumbnail-link">
            <?php
            // Flèche du post précédent s'il existe
            if ($previous_post) {
                ?>
                <div class="prev-nav">
                    <a href="<?php echo get_permalink($previous_post); ?>" class="arrow-left">
                        <p>←</p>
                    </a>
                </div>
                <?php
            }
            // Flèche du post suivant s'il existe
            if ($next_post) {
                ?>
                <div class="next-nav">
                    <a href="<?php echo get_permalink($next_post); ?>" class="arrow-right">
                        <p>→</p>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
</div>

<div class="same-content">
    <p class="same-content-title">Vous aimerez aussi</p>
    <div class="photo-container">
        <?php
        $current_categories = wp_get_post_terms(get_the_ID(), 'categorie', array('fields' => 'slugs'));
        $current_post_id = get_the_ID();

        $args = array(
            'post_type' => 'photo',
            'orderby' => 'rand',
            'posts_per_page' => 2,
            'post__not_in' => array($current_post_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'categorie',
                    'field' => 'slug',
                    'terms' => $current_categories,
                ),
            ),
        );

        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()):
                $the_query->the_post();
                if (has_post_thumbnail()):
                    ?>
                    <?php get_template_part('templates_part/photo-block', 'photo'); 
                endif;
            endwhile;
        } else {
            echo "Aucune photo trouvée dans la même catégorie.";
        }
        wp_reset_postdata(); // Réinitialise les données de la requête
        ?>

    </div>

</div>
<div class="button">
    <a href="/">
        <button class="btn-photos">Toutes les photos</button>
    </a>
</div>
</div>

<?php

get_footer(); ?>