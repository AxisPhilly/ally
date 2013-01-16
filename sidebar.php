<aside id="sidebar" class="three columns hide-for-small">
  <div class="sidebar view">
    <div class="moving-container" data-spy="affix" data-offset-top="150">
      <div class="social-container">
        <strong>Share this article</strong>
        <div class="shorturl-container">
          <input type="text" name="shorturl" id="shorturl" value="http://axs.ph/Hnh34g" readonly="true"></input>
        </div>
        <div class="sites">
          <a href="#"><i class="social-foundicon-twitter"></i></a>
          <a href="#"><i class="social-foundicon-facebook"></i></a>
          <a href="#"><i class="social-foundicon-google-plus"></i></a>
          <a href="#"><i class="general-foundicon-mail"></i></a>
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
          <strong>This article is part of our series on <a href="/project/<?php echo $c_slug; ?>"><?php echo $c_name; ?></a>.
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
            'category_name' => (isset($c_name) ? $c_name : false)
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
          if(isset($c_name) and (get_post_type(the_post()) != 'discussion')) { ?>
            <p>
              <strong>Recent discussion about <?php echo $c_name; ?></strong>
            </p>
            <?php
              $discussion_args = array(
                'post_per_page' => 3,
                'post_type' => 'discussion'
              );

              $discussion = new WP_Query($discussion_args);

              if($discussion->have_posts()):
                while($discussion->have_posts()):
                  $discussion->the_post();
            ?>
          <a href="<?php echo get_permalink($post->ID); ?>"><?php print_r($post->post_title); ?></a>
        </div>
        <?php endwhile; endif; } ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</aside>