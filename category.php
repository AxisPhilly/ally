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
              'meta_info' => 'featured-project',
              'meta_key' => '_thumbnail_id',
              'post_status' => 'publish',
              'category_name' => $slug,
              'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool')
            );

            $featured = new WP_Query($featured_args);

            if ($featured->have_posts()):
              while ($featured->have_posts()):
                $featured->the_post();
          ?>
          <div>
            <?php if (has_post_thumbnail($post->ID)):  
              $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium'); ?>
              <img src="<?php echo $image[0]; ?>">
            <?php endif; ?>
            <div class="caption">
              <h4 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
              <div class="details">
                <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
                <?php the_excerpt(); ?>
              </div>
            </div>
          </div>
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </div><!-- End Features -->
    </div><!-- End Header Row -->
    <div class="project-section-nav small" data-spy="affix" data-offset-top="315">
      <div class="row">
        <dl class="tabs two-up">
          <dd class="active"><a href="#feed">Stories &amp; Tools</a></dd>
          <dd><a href="#talk">Discuss</a></dd>
        </dl>
      </div>
    </div>
    <div class="project-section-nav large" data-spy="affix" data-offset-top="524">
      <div class="row">
        <dl class="tabs two-up">
          <dd class="active"><a href="#feed">Stories &amp; Tools</a></dd>
          <dd><a href="#talk">Discussion</a></dd>
        </dl>
      </div>
    </div>
    <ul class="tabs-content">
      <li class="active" id="feedTab">
        <a name="stories"></a>
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
      <li id="talkTab">
        <a name="discussion"></a>
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
      </li>
    </ul>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<script type="text/javascript">
</script>
<?php get_footer(); ?>