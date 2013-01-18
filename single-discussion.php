<?php get_header(); ?>
<div class="content-container">
  <div id="news-container">
    <div class="row article-container view">
      <div class="leftbar">
        <?php
          // Checks to see if meta_info Fullscreen is selected
          if (!in_array('full-screen', $meta_tags))
            get_sidebar('sidebar.php');
            $sidebar = 1;
        ?>
      </div>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>' ?>
      <article class="single-story <?php if (!in_array('full-screen', $meta_tags)) echo "seven"; else echo "twelve"; ?> columns">
        <div class="single-article view">
          <header class="article-header">
            <h2 class="headline"><?php the_title(); ?></h2>
            <?php if((has_post_thumbnail($post->ID)) && (!in_array('hide-featured', $meta_tags))): ?>
              <div class="media-container full-page">
                <div class="media">
                  <?php 
                  $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large'); 
                  ?>
                  <img src="<?php echo $image[0]; ?>">
                </div>
                <div class="caption">
                  <p>
                    <?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?>
                  </p>
                </div>
              </div>

            <?php endif; ?>

          </header>
          <div class="article-text">
            <?php
              setup_postdata($post);
              the_content();
            ?>
          </div>
          <?php
            comments_template('comments.php', 'false');
          ?>
          <div class="row">
            <div class="bottombar">
              <?php
                // Checks to see if meta_info Fullscreen is selected
                if (!in_array('full-screen', $meta_tags))
                  include('sidebar.php');
              ?>
            </div>
          </div>
        </div>
      </article>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>'; ?>
    </div>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<?php get_footer(); ?>