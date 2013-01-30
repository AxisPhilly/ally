<?php 
  get_header(); 
  // Get Slug from URL
  $slug = get_slug();
  // Get Category ID from Slug
  $slug_id = get_category_by_slug($slug)->term_id;
?>
<!-- Content -->
<div class="content-container">
  <!-- News Container -->
  <div id="news-container">
    <div class="row">
      <header class="project-header twelve columns">
        <h3><?php  echo get_category_by_slug($slug)->name ?></h3>
      </header>
    </div>
    <!-- Header Row -->
    <div class="row" id="category-top">
      <!-- Background -->
      <div id="background" class="five columns">
        <?php echo category_description( get_category_by_slug($slug)->term_id ); ?>
      </div>
      <div id="background-compressed">
        <ul class="accordion">
          <li>
            <div class="title">
              <span><?php echo get_category_by_slug($slug)->name ?></span>
            </div>
            <div class="content">
              <?php echo category_description(get_category_by_slug($slug)->term_id); ?>
            </div>
          </li>
        </ul>
      </div>
      <!-- Features -->
      <div id="feature-container" class="seven columns">
        <div id="featured">
          <!-- features go here -->
          <?php
            $featured_args = array(
              'orderby' => 'date', 
              'order' => 'DESC',
              'posts_per_page' => 5,
              'meta_info' => 'project-feature',
              'meta_key' => '_thumbnail_id',
              'post_status' => 'publish',
              'category_name' => $slug,
              'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool')
            );

            $featured = new WP_Query($featured_args);

            if ($featured->have_posts()):
              while ($featured->have_posts()):
                $featured->the_post();

              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
          ?>
          <div>
            <?php get_media($post->ID, 'medium'); ?>
            <div class="caption">
              <h4 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h4>
              <div class="details">
                <?php if (get_the_author() != 'axisphilly') { ?>
                  <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
                <?php } ?>
                <?php the_excerpt(); ?>
              </div>
            </div>
          </div>
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </div><!-- End Features -->
    </div><!-- End Header Row -->
    <div class="project-section-nav small">
      <div class="row">
        <dl class="tabs two-up">
          <dd class="active"><a href="#feed">Stories</a></dd>
          <dd><a href="#discuss">Discuss</a></dd>
        </dl>
      </div>
    </div>
    <div class="project-section-nav large">
      <div class="row">
        <dl class="tabs two-up">
          <dd class="active"><a href="#feed">Stories &amp; Tools</a></dd>
          <dd><a href="#discuss">Discussion</a></dd>
        </dl>
      </div>
    </div>
    <ul class="tabs-content">
      <li class="active" id="feedTab">
        <div class="row">
          <div id="stories" class="twelve columns">
            <div class="items">
              <?php
                $stories_args = array(
                  'orderby' => 'date', 
                  'order' => 'DESC',
                  'post_status' => 'publish', 
                  'category_name' => $slug, 
                  'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool'),
                  'posts_per_page' => -1
                );

                $stories = new WP_Query($stories_args);

                if ($stories->have_posts()):
                  while ($stories->have_posts()):
                    $stories->the_post();
              ?>
                <?php get_template_part('archive', 'feed'); ?> 
              <?php endwhile; endif; ?>
              <?php wp_reset_postdata(); ?>
            </div>
          </div>
        </div>
      </li>
      <li id="discussTab">
        <div class="row">
          <div id="discussion" class="twelve columns">
            <div class="items">
              <?php
                $discussion_args = array(
                  'orderby' => 'date', 
                  'order' => 'DESC',
                  'post_status' => 'publish', 
                  'category_name' => $slug, 
                  'post_type' => array('discussion'),
                  'posts_per_page' => -1
                );

                $discussions = new WP_Query($discussion_args);
                if ($discussions->have_posts()):
                  while ($discussions->have_posts()):
                    $discussions->the_post();
              ?>  
              <div class="row">
                <div class="four columns">
                    <h4 style="font-family: proxima-nova, helvetica, sans-serif;">
                      <?php
                        echo "<a href='"; 
                        the_permalink();
                        echo "'>";
                      the_title();
                      ?>
                    </a>
                  </h4>                    
                </div>
                <div class="two columns" style="margin: 1em 0">
                  <?php 
                    comments_template();
                    $comment_number = get_comments_number();
                    if ($comment_number > 0):
                      $defaults = array(
                        'post_id' => $post->ID,
                        'number' => 1,                        
                        'count' => false
                      );
                      echo "<div>".$comment_number." Replies</div>";
                    else:
                    echo "0 Replies";
                  endif;
                  ?>
                </div>
                <div class="six columns" style="margin: 1em 0">
                  <div style="font-family: proxima-nova, helvetica, sans-serif; font-size: 70%; text-transform: uppercase;">Featured Comment</div>
                  <? 
                    echo get_post_meta($post->ID, '_pull_quote_name', true); 
                  ?><br>
                  <a href="<? echo get_post_meta($post->ID, '_pull_quote_url_name', true); ?>">Read this thread &#8594;</a>
                </div>
              </div>
              <?php endwhile; endif; ?>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<script type="text/javascript">
</script>
<?php get_footer(); ?>