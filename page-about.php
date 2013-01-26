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
?>
<? get_header(); ?>
  <!-- Content -->
  <div class="content-container about">
    <div class="row">

      <div class="two mobile-one columns">
        <ul class="side-nav" id="about-menu" data-spy="affix" data-offset-top="205">
          <li><a href="#mission">Mission</a></li>
          <li><a href="#board">Board</a></li>
          <li><a href="#staff">Staff</a></li>
          <li><a href="#jobs">Jobs</a></li>
          <li><a href="#inthenews">In The News</a></li>
          <li><a href="#press">Press Releases</a></li>
        </ul>
      </div>

      <div class="ten mobile-three columns" id="about-container">
        <?php while (have_posts()) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; // end of the loop. ?>
      </div>
    </div>
  </div>
<?php get_footer(); ?>