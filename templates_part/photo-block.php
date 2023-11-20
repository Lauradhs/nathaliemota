<div class="photo_block">
    <?php
    $thumbnail_id = get_post_thumbnail_id();
    if ($thumbnail_id) {
        $image_info = wp_get_attachment_image_src($thumbnail_id, 'full');
        $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
        $post_url = get_permalink();

        echo '<a href="' . esc_url($post_url) . '"><img class="upsellphoto" src="' . esc_url($image_info[0]) . '" alt="' . esc_attr($alt_text) . '" /></a>';
    } else {
        echo '<p>Aucune image</p>';
    }
    ?>
</div>
