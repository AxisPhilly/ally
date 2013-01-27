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
          <?php if (have_posts()): ?>
          <h3 class="page-title"><?php printf(__('Search Results for: %s', 'ally'), '<span>' . get_search_query() . '</span>' ); ?></h3>
            <?php while (have_posts()) : the_post(); ?>
              <?php get_template_part('archive' , 'feed'); ?>
            <?php endwhile; ?>
          <?php else : ?>
            <div id="post-0" class="post no-results not-found">
              <h3 class="entry-title"><?php _e('Nothing Found', 'ally'); ?></h3>
              <div class="entry-content">
                <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'Ally'); ?></p>
                <?php get_search_form(); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>