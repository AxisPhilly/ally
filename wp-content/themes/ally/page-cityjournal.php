<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Ally
 */
?>
<?php get_header(); ?>

<div class="content-container">
  <div id="content" role="main">
    <div class="row">
      <div id="stories" class="twelve columns">
        <div class="items">

<?php if(have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
          <h2><? the_title() ?></h2>
          <p>
            <? the_content() ?>
          </p>
<?php endwhile; ?>
<?php endif; ?>




          <?php

            $tagged_args = array(
              'orderby' => 'date', 
              'order' => 'DESC',
              'post_status' => 'publish', 
              'post_type' => array('city_journal'),
            );

            $tagged = new WP_Query($tagged_args);
          ?>
          <?php if ($tagged->have_posts()): ?>          
            <?php while ($tagged->have_posts()) : $tagged->the_post(); ?>
              <?php get_template_part('archive', 'feed'); ?>
            <?php endwhile; ?>
          <?php else : ?>
            <div id="post-0" class="post no-results not-found">
              <h2 class="entry-title"><?php _e('Nothing Found', 'ally'); ?></h2>
              <div class="entry-content">
                <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'ally'); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>