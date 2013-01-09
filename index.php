<?php 
  get_header(); 
  // Get Slug from URL

?>
<!-- Content -->
<div class="content-container">
  <!-- News Container -->
  <div id="news-container">
    <div class="row">
      <header class="project-header twelve columns">
        <div class="row">
          <img src="/wp-content/themes/ally/images/logo.jpg" id="logo">
        </div>
      </header>
    </div>
    <!-- Header Row -->
    <div class="row">
      <div class="columns eight">
        <!-- Features -->
        <div id="feature-container">
          <div id="featured">
            <!-- features go here -->
            <?php
              query_posts(array(
                'orderby' => 'title', 
                'order' => 'ASC',
                'posts_per_page' => 5,
                'meta_info' => 'featured',
                'post_status' => 'publish',
                'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool')
              ));

              if (have_posts()):
                while (have_posts()):
                  the_post();
            ?>
            <div>
              <?php if (has_post_thumbnail($post->ID)):  
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium'); ?>
                <img src="<?php echo $image[0]; ?>">
              <?php endif; ?>
              <div class="caption">
                <h4 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
                <?php the_excerpt(); ?>
              </div>
            </div>
            <?php endwhile; endif; ?>
          </div>
        </div><!-- End Features -->

   


        <div id="stories">
          <div class="items">
            <?php
              query_posts(array(
                'post_status' => 'publish', 
                'post_type' => array('post' , 'external_post'),
                'posts_per_page' => -1
              ));
              if (have_posts()):
                while (have_posts()):
                  the_post();
            ?>
              <article class="row">
                <?php if (has_post_thumbnail($post->ID)) { ?>  
                  <div class="six mobile-two columns">
                <? } else { ?>
                  <div class="twelve mobile-four columns">
                <? } ?>
                    <h4>
                      <a name="<?php the_id(); ?>" href="<?php 
                        if (get_post_type() != 'external_post') {
                          the_permalink();
                        } else {
                          echo get_post_meta($post->ID, '_url_name', true); 
                        } ?>">
                        <?php 
                          the_title();
                          if (get_post_type() == 'external_post') {
                            echo " <img class='external-link' src='" . get_bloginfo('template_directory') . "/images/external_link.svg'/>"; 
                          }
                        ?>
                      </a>
                    </h4>
                    <div class="byline <?php if(get_post_type() != 'external_post') { echo 'hide-for-small'; } ?>">
                      <?php
                        # Check to see if this is an external_post. If so, display Source and Source URL instead of Author.
                        if (get_post_type() == 'external_post') {
                          echo "<i>Source: <strong>" . get_post_meta($post->ID, '_source_name', true) . '</strong>' .
                              " -- 1/14/12</i>";
                        }
                        elseif (get_post_type() != 'external_post') {
                          echo "by "; coauthors_posts_links();
                        }
                      ?>
                    </div>
                    <?php if ((get_post_type() != 'external_post')) { ?> 
                      <div class="datetime hide-for-small"><?php the_time('F j, Y');?></div>
                    <?php } ?>
                    <div class="hide-for-small">
                      <?php the_excerpt(); ?>
                      <?php the_tags('<span class="round label">','</span> <span class="round label">','</span>'); ?> 
                    </div>
                  </div>
                <?php if (has_post_thumbnail($post->ID)): ?>  
                  <div class="six mobile-two columns">
                    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); ?>
                    <img src="<?php echo $image[0]; ?>">
                  </div>
                <?php endif; ?>
              </article>
          <?php endwhile; endif; ?>
          </div>
        </div>

      </div>  
      <div class="columns four">
        <h2>
          Projects
        </h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur.
        </p>
        <hr>
        <h2>
          Blogs
        </h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur.
        </p>
      </div>
    </div>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<script type="text/javascript">
  $(window).load(function() {
    $('#featured').orbit({ timer: 'true' });
  });
</script>
<?php get_footer(); ?>