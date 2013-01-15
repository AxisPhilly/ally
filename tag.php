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
          <h1 class="page-title"><?php printf(__('Tag Archives: %s', 'ally'), '<span>' . single_tag_title('', false) . '</span>'); ?></h1>
            <?php while (have_posts()) : the_post(); ?>
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