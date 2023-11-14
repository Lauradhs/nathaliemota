<?php get_header(); ?>

<?php
$args = array(
    'post_type' => 'photo',
    'posts_per_page' => 12,  
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => 1,
);

$the_query = new WP_Query($args);

if($the_query->have_posts()): ?>
        <ul class="publication-list">
      <?php 
        while ($the_query->have_posts()): $the_query->the_post();
          get_template_part('templates_part/photo-block', 'photo');
        endwhile;
      ?>
    </ul>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>

<div class="button">
        <a href="#!" id="load-more">
        <button class="btn-charger">Charger plus</button>
        </a>
    </div>

<?php get_footer(); ?>