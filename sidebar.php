<aside id="sidebar" class="three columns hide-for-small">
  <div class="sidebar view">
    <div <?php global $sidebar; if($sidebar == 0) { ?> class="moving-container" data-spy="affix" data-offset-top="220" <?php } ?>>
      <div class="social-container">
        <strong>Share this article</strong>
        <div class="shorturl-container">
          <input type="text" name="shorturl" id="shorturl" value="<?php echo wp_get_shortlink(); ?>" readonly="true"></input>
        </div>
        <div class="sites">
          <a title="tweet" href="https://twitter.com/share?text=<?php the_title(); ?>&amp;url=<?php echo wp_get_shortlink();?>&amp;via=AxisPhilly" target="_blank"><i class="social-foundicon-twitter"></i></a>
          <a title="share on Facebook" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo the_title(); ?>&amp;p[url]=<?php echo wp_get_shortlink(); ?>&amp;p[summary]=<? echo get_the_excerpt(); ?>" target="_blank"><i class="social-foundicon-facebook"></i></a>
          <a title="email" href="mailto:?subject=AxisPhilly: <?php the_title(); ?>&amp;body=<?php echo wp_get_shortlink();?> <?php echo get_the_excerpt(); ?>" target="_blank"><i class="general-foundicon-mail"></i></a>
        </div>
      </div>
      <div class="project-info-container">
      <?php
        if (in_project($post->ID)) {
          $category=get_the_category();
          $c_name = $category[0]->name;
          $c_slug = $category[0]->slug; 
      ?>
        <p>
          <strong>This <?php if(get_post_type($post) == 'wp_tool') { echo 'tool'; } else { echo 'article'; } ?> is part of our series on <a href="/project/<?php echo $c_slug; ?>"><?php echo $c_name; ?></a>.
          Read more from the series:</strong>
        </p>
      <?php } else { ?>
        <p>
          <strong>Recent Stories</strong>
        </p>
      <?php } ?>
        <div class="recent stories">
        <?php
          $recent_posts_args = array(
            'posts_per_page' => 3,
            'category_name' => (isset($c_slug) ? $c_slug : false)
          );

          $recent_posts = new WP_Query($recent_posts_args);

          if($recent_posts->have_posts()):
            while($recent_posts->have_posts()):
              $recent_posts->the_post();
        ?>
            <a href="<?php echo get_permalink($post->ID); ?>"><?php print_r($post->post_title); ?></a>
        <?php endwhile; endif; ?>
        <?php wp_reset_postdata(); ?>
        </div>
        <div class="recent discussion">
          <?php 
            if(isset($c_name) and (get_post_type($post) != 'discussion')) { ?>
              <?php
                $discussion_args = array(
                  'category_name' => $c_name,
                  'posts_per_page' => 3,
                  'post_type' => 'discussion'
                );

                $discussion = new WP_Query($discussion_args);

                if($discussion->have_posts()):
              ?>
                <p>
                  <strong>Recent discussion about <?php echo $c_name; ?></strong>
                </p>
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