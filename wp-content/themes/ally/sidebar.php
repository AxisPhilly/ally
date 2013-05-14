<aside class="sidebar three columns hide-for-small">
  <div class="view">
    <div <?php global $sidebar; if($sidebar == 0) { ?> class="moving-container" data-spy="affix" data-offset-top="180" <?php } ?>>
      <? 
        $column = get_column();
        
        if (count($column) > 0) {
          $slug = $column[0];
        }

        if (count($column) > 0) {
          echo '<div class="column-container">';
          $column_details = get_term_by('slug', $slug, 'column_info');
          $taxonomy_image_url = get_option('z_taxonomy_image'. $column_details->term_id);
          if (!empty($taxonomy_image_url)){
            echo '<a href="/commentary/' . $slug . '"><img src="' . $taxonomy_image_url . '"></a>';
          }
          echo '<h6 class="sidebar">' . $column_details->name . '</h6>';
          echo '<div class="recent stories">' . $column_details->description . '</div>';
          echo '</div>';
        }
      ?>      
      <div class="social-container">
        <h6 class="sidebar">Share this:</h6>
        <div class="shorturl-container">
          <input type="text" name="shorturl" class="shorturl" value="<?php echo wp_get_shortlink(); ?>"/>
        </div>
        <div class="sites">
          <a title="tweet" href="https://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(wp_get_shortlink());?>&amp;via=AxisPhilly" target="_blank"><i class="social-foundicon-twitter"></i></a>
          <a title="share on Facebook" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo urlencode(get_the_title()); ?>&amp;p[url]=<?php echo urlencode(wp_get_shortlink()); ?>&amp;p[summary]=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="social-foundicon-facebook"></i></a>
          <a title="email" href="mailto:?subject=AxisPhilly: <?php echo urlencode(get_the_title()); ?>&amp;body=<?php echo urlencode(wp_get_shortlink());?> <?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="general-foundicon-mail"></i></a>
        </div>
      </div>
      <div class="project-info-container">
        <div class="recent stories">
        <?php
          $main_post = $post->ID;
          $recent_header = 'Recent Stories';
          $category = get_the_category();
          $c_name = $category[0]->name;
          $c_slug = $category[0]->slug;

          if($c_slug == 'uncategorized') { $c_slug = NULL; }

          if (get_post_type($post) == 'wp_tool') { 
            $post_type = 'tool'; 
          } elseif (get_post_type($post) == 'discussion') {
            $post_type = 'discussion';
          } else { 
            $post_type = 'article'; 
          } 

          if (in_project($main_post)) {
            $recent_header = 'This ' . $post_type . ' is part of our <a href="/project/' . $c_slug . '">' . $c_name . '</a> project. Read more:';
          }

          $recent_posts_args = array(
            'posts_per_page' => 4,
            'category_name' => (isset($c_slug) ? $c_slug : false)
          );

          if (count($column) > 0) {
            $recent_posts_args['column_info'] = $slug;
            $recent_posts_args['category_name'] = NULL;
            $recent_header = 'Recent Posts';
          }

          $recent_posts = new WP_Query($recent_posts_args);
        ?>
        <div class="description">
          <h6 class="sidebar"><?php echo $recent_header; ?></h6>
        </div>
        <?php
          $count = 0;
          if($recent_posts->have_posts()):
            while($recent_posts->have_posts()):
              $recent_posts->the_post();

              if($post->ID == $main_post) { continue; }
              if($count == 3) { continue; }
        ?>
          <a href="<?php echo get_permalink($post->ID); ?>"><?php print_r($post->post_title); ?></a>
        <?php $count++; endwhile; endif; ?>
        <?php wp_reset_postdata(); ?>
        </div>
        <div class="recent discussion">
          <?php 
            if(isset($c_name) and (get_post_type($post) != 'discussion')) {
                $discussion_args = array(
                  'category_name' => $c_name,
                  'posts_per_page' => 1,
                  'post_type' => 'discussion'
                );

                $discussion = new WP_Query($discussion_args);

                if($discussion->have_posts()):
              ?>
                <h6 class="sidebar">Related discussion:</h6>
              <?php
                  while($discussion->have_posts()):
                    $discussion->the_post();
              ?>
            <a href="<?php echo get_permalink($post->ID); ?>"><?php print_r($post->post_title); ?></a>
          <?php endwhile; endif; } ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </div>
    </div>
  </div>
</aside>