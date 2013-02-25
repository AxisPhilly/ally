<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Ally
 */

get_header(); ?>

  <!-- Content -->
  <div class="content-container about">
    <div class="row">
      <div class="twelve mobile-four columns">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; // end of the loop. ?>
      </div>
    </div>
  </div>
   
<?php get_footer(); ?>