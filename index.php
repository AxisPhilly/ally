<?php 
  get_header(); 
  // Get Slug from URL

?>
<section class="row" id="homepage-content">
      <div class="six columns" id="homepage-featured">
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
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
            <img src="<?php echo $image[0]; ?>">
          <?php endif; ?>
          <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
            <div class="category-symbology">
              <?php
                list_categories();
              ?>
            </div>
          <?php } ?>          
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="byline">by <?php coauthors_posts_links(); ?> — <?php the_time('M. j'); ?></div>


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
          <?php if (has_post_thumbnail($post->ID)):  
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail'); ?>
            <img src="<?php echo $image[0]; ?>">
          <?php endif; ?>        

          <div class="category-symbology">
            <?php
              list_categories();
            ?>
          </div>  
          <h3><a href=""><?php the_title(); ?></a></h3>
          <div class="byline">by <?php coauthors_posts_links(); ?> — <?php the_time('M. j'); ?></div>
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
            'post_type' => array('post')
          );

          $latest = new WP_Query($latest_args);

          if ($latest->have_posts()):
            while ($latest->have_posts()):
              $latest->the_post();
        ?>
        <article>
          <h3><a href=""><?php the_title(); ?></a></h3>
          <div class="byline">by <?php coauthors_posts_links(); ?> — <?php the_time('M. j'); ?></div>
        </article>

        <?php endwhile; endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
  </section>
<?php get_footer(); ?>