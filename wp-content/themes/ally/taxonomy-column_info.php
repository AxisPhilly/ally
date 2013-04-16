<?php 
  get_header();
  // Get Slug from URL
  $slug = get_slug();
  $column_details = get_term_by('slug', $slug, 'column_info');
  // Get Author ID from Slug
  ?>
<!-- Content -->
  <div class="content-container">
    <div id="news-container">
      <div id="stories">
        <div class="items">
          <div class="row">
            <div class="three columns">
              <img src="<?php echo z_taxonomy_image_url(); ?>">
              <h3><? echo $column_details->name ?></h3>
              <p><? echo $column_details->description ?></p>                    
            </div>            
            <div class="nine columns">
              <?php
                $author_args = array(
                  'orderby' => 'date', 
                  'order' => 'DESC',
                  'column_info' => $slug,
                  'posts_per_page' => -1,
                  'post_status' => 'publish',
                  'post_type' => array('post')
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
        </div>
      </div>
    </div><!-- End News Container -->
  </div><!-- End Content Container -->
<?php get_footer(); ?>