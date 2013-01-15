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
  <div class="content-container">
    <div class="row">
      <h1 class="twelve columns">Tools &amp; Data</h1>
    </div>

    <div class="row">
      <div class="twelve columns">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>

    <div class="row">
      <div id="tools-and-data" class="twelve columns">
        <div class="items">
        <?php
          query_posts(array(
            'orderby' => 'date', 
            'order' => 'DESC',
            'post_status' => 'publish', 
            'post_type' => array('wp_tool', 'external_tool')
          ));
          if (have_posts()):
            while (have_posts()):
              the_post();
        ?>
          <div class="tool">
            <?php
              echo "<a href='"; 
              if (get_post_type() == 'external_tool') 
                echo get_post_meta( $post->ID, '_url_name', true);
                else the_permalink();
              echo "'>";
            ?>              
            <?php the_post_thumbnail(); ?>
              <div class="caption">
                <h5><?php the_title(); ?></h5>
                <span><?php the_excerpt(); ?></span>
              </div>
            </a>
          </div>
        <?php endwhile; endif; ?>
      </div>
    </div>
  </div>
<?php get_footer(); ?>