<div class="photo-item">
    <?php
    $thumbnail_id = get_post_thumbnail_id();

    if ($thumbnail_id) {
        $image_info = wp_get_attachment_image_src($thumbnail_id, 'full');
        $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
        $post_url = get_permalink();
        $reference = get_field('référence');
        echo '<a href="' . esc_url($post_url) . '" class="photo-interest lightbox-trigger"';
        echo ' data-reference="' . esc_attr($reference) . '"';
        echo ' data-category="' . esc_attr(get_the_terms(get_the_ID(), 'categorie')[0]->name) . '">';
        echo '<img class="upsellphoto lightbox-trigger" src="' . esc_url($image_info[0]) . '" alt="' . esc_attr($alt_text) . '" />';
        echo '</a>';
    } else {
        echo '<p>Aucune image</p>';
    }
    ?>

    <div class="calque-photo">
        <div class="contener-fullscreen">
            <a href="#lightbox">
                <img class="icon-fullscreen" data-photo="<?php echo get_the_id(); ?>"
                    src="<?php echo get_template_directory_uri() . '/images/icon_fullscreen.png'; ?>"
                    alt="Icône Fullscreen">
            </a>
        </div>
        <div class="contener-eye">
            <a href="<?php echo esc_url($post_url); ?>">
                <img src="<?php echo get_template_directory_uri() . '/images/icon_eye.png'; ?>" alt="Icône oeil">
            </a>
        </div>
        <div class="contener-infos">
            <?php
            echo '<p class="photo-info"><span id="ref-photo">' . esc_html($reference) . '</span></p>';

            $categories = get_the_terms(get_the_ID(), 'categorie');
            if ($categories && !is_wp_error($categories)) {
                echo '<div class="post-categories"><span id="categorie-photo">';
                foreach ($categories as $categorie) {
                    echo '' . esc_html($categorie->name) . '';
                }
                echo '</div></span>';
            }
            ?>
        </div>

    </div>
</div>