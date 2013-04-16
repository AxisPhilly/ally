<?php 
  error_reporting(0);
  get_header();
  // Get Slug from URL
  global $coauthors_plus;
  $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
  ?>
<!-- Content -->
  <div class="content-container">
    <div id="news-container">
      <div class="row author">
        <div class="photo two columns">
          <p>
          <? 
            print @get_avatar(get_the_author_meta( 'ID' ), 767); 
          ?>
          </p>
        </div>
        <div class="bio six columns">
          <strong><?php printf( __( '%s' ), get_the_author() ); ?></strong>
          <? if ( get_the_author_meta( 'description' ) ) : ?>
          <p>
            <?php the_author_meta( 'description' ); ?>
          </p>
          <?php endif; ?>
        </div>
        <div class="contact four columns">
          <ul class="no-markers">
            <?php if (get_the_author_meta( 'email' )) { ?><li><i class="general-foundicon-mail"></i> <a href="mailto:<?php echo $author->user_email ?>" target="_blank"><? the_author_meta( 'email' ) ?></a></li><? } ?>
            <?php if (get_the_author_meta( 'twitter' )) { ?><li><i class="social-foundicon-twitter"></i> <a href="http://twitter.com/<? the_author_meta( 'twitter' ) ?>" target="_blank">@<? the_author_meta( 'twitter' ) ?></a></li><? } ?>
            <?php if (get_the_author_meta( 'phone' )) { ?><li><i class="general-foundicon-phone"></i> <span><? the_author_meta( 'phone' ) ?></span></li><? } ?>
          </ul>
        </div>
      </div>
      <div id="stories">
        <div class="items">
          <?php
            $author_args = array(
              'orderby' => 'date', 
              'order' => 'DESC',
              'posts_per_page' => -1,
              'author' => get_the_author_meta( 'ID' ),
              'post_status' => 'publish',
              'post_type' => array('post', 'external_tool', 'wp_tool')
            );
            $author = @new WP_Query($author_args);
            if ($author->have_posts()):
              while ($author->have_posts()):
                $author->the_post();
          ?>
            <?php get_template_part('archive', 'feed'); ?> 
          <?php endwhile; endif; ?>
        </div>
      </div>
    </div><!-- End News Container -->
  </div><!-- End Content Container -->
<?php get_footer(); ?>