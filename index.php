<?php 
  get_header(); 
  // Get Slug from URL

?>
<section class="row" id="featured-content BLANG">
      <div class="six columns">
        <?php
          $featured_args = array(
            'orderby' => 'date', 
            'order' => 'DESC',
            'posts_per_page' => 1,
            'meta_info' => 'featured-homepage',
            'meta_key' => '_thumbnail_id',
            'post_status' => 'publish',
            'post_type' => array('post', 'external_tool', 'wp_tool')
          );

          $featured = new WP_Query($featured_args);

          if ($featured->have_posts()):
            while ($featured->have_posts()):
              $featured->the_post();
        ?>
        <article>
          <?php if (has_post_thumbnail($post->ID)):  
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium'); ?>
            <img src="<?php echo $image[0]; ?>">
          <?php endif; ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
            <?php the_excerpt(); ?>
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
        </article>
      </div>
      <div class="three columns">
        <?php
          $latest_args = array(
            'orderby' => 'date', 
            'order' => 'DESC',
            'posts_per_page' => 2,
            'post_status' => 'publish',
            'post_type' => array('post')
          );

          $latest = new WP_Query($latest_args);

          if ($latest->have_posts()):
            while ($latest->have_posts()):
              $latest->the_post();
        ?>
        <article>
          <h3><a href=""><?php the_title(); ?></a></h3>
          <?php the_excerpt(); ?>
        </article>

        <?php endwhile; endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
      <div class="three columns">
        <?php
          $latest_args = array(
            'orderby' => 'date', 
            'order' => 'DESC',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'post_type' => array('external_tool', 'wp_tool')
          );

          $latest = new WP_Query($latest_args);

          if ($latest->have_posts()):
            while ($latest->have_posts()):
              $latest->the_post();
        ?>
        <article>
          <h3><a href=""><?php the_title(); ?></a></h3>
        </article>

        <?php endwhile; endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
  </section>
<?php get_footer(); ?>