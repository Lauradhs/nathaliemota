<?php get_template_part('/templates_part/modale-contact'); ?>

<?php wp_footer(); ?>


<?php
            wp_nav_menu(
                array(
                    'menu_id' => 'footer',
                )
            );
            ?>

<?php get_template_part( '/templates_part/lightbox' ); ?>
</body>
</html>