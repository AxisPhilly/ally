<?php 
  // Load Header
  get_header();
  // Get Slug from URL
  $slug = get_slug();
  // Get Author ID from Slug
  $author = get_user_by('slug', $slug);
  $author_id = $author->id;
  ?>
<!-- Content -->
  <div class="content-container">
    <div id="news-container">
      <div class="row author">
        <div class="photo two columns">
          <? echo get_avatar(get_the_author_meta('ID'), 200); ?>
        </div>
        <div class="bio seven columns">
          <strong><?php echo $author->display_name ?></strong>
          <p>
            <?php echo $author->user_description ?>
          </p>
        </div>
        <div class="contact three columns">
          <ul class="no-markers">
            <li><i class="general-foundicon-mail"></i> <a href="mailto:<?php echo $author->user_email ?>" target="_blank"><?php echo $author->user_email ?></a></li>
            <li><i class="social-foundicon-twitter"></i> <a href="http://twitter.com/<? echo $author->twitter ?>" target="_blank">@<?php echo $author->twitter ?></a></li>
            <li><i class="general-foundicon-phone"></i> <span><?php echo $author->phone ?></span></li>
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
              'author' => $author_id,
              'post_status' => 'publish',
              'post_type' => array('post', 'external_tool', 'wp_tool')
            );
            $author = new WP_Query($author_args);
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
<?php  get_footer(); ?>