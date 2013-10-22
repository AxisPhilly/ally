<?php 
  get_header(); 
  $feature_args = array(
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => -1,
    'meta_info' => 'homepage-feature',
    'post_type' => array('post')
  );

  $featured = new WP_Query($feature_args);
?>
  <section id="homepage-content">
    <section id="features" class="row">
      <div class="five columns" id="top-featured">
        <?php
          $top_count = 0;
          if ($featured->have_posts()):
            while ($featured->have_posts()):
              $featured->the_post();

              if ($top_count){ continue; }

              // If post is top-featured proceed as normal, else move on to the next post
              $top_featured = 0;
              foreach(wp_get_object_terms($post->ID, 'meta_info', '') as $term) {
                if($term->slug == 'homepage-top-feature') {
                  $top_featured = 1;
                }
              }
              if(!$top_featured) { continue; }

              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
        ?>
        <article>
          <?php get_media($post->ID, 'medium') ?>
          <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
          <?php } ?>          
            <div class="byline"><?php coauthors_posts_links(); ?> / <?php (get_the_date('M') == "May") ? the_time('M j'): the_time('M. j'); ?></div>
            <h3><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h3>
            <?php 
              the_excerpt();
              $top_count++;
            ?>
          <?php endwhile; endif; ?>
        </article>
        <article id="tools">
          <h4><img src="<? bloginfo('template_directory'); ?>/images/tools.png">Featured Tool</h4>
          <?php 
            $tools_args = array(
              'orderby' => 'date',
              'order' => 'desc',
              'posts_per_page' => 1,
              'post_type' => array('wp_tool', 'external_tool'),
              'meta_info' => 'homepage-top-feature',
              'meta_key' => '_thumbnail_id'
            );

            $tools = new WP_Query($tools_args);

            if ($tools->have_posts()):
              while ($tools->have_posts()):
                $tools->the_post();

                global $post_url;
                if($post->post_type == 'external_tool') {
                  $post_url = get_post_meta($post->ID, '_url_name', true);
                } else {
                  $post_url = get_permalink($post->ID);
                }
          ?>
          <div class="row">
            <div class="six columns">
              <?php get_media($post->ID, 'medium') ?>
            </div>
            <div class="six columns">
<!--               <?php if (get_the_author() != 'axisphilly') { ?>
                <div class="byline"><?php coauthors_posts_links(); ?> / <?php (get_the_date('M') == "May") ? the_time('M j'): the_time('M. j'); ?></div>
              <? } ?>      -->      
              <h5><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h5>
              <?php the_excerpt(); ?>                
            </div>
          </div>
        </article>
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
      </div>
      <div class="four columns" id="sub-featured">
        <?php
          $sub_count = 0;
          $sub_thumbnail_count = 0;          
          if ($featured->have_posts()):
            while ($featured->have_posts()):
              $featured->the_post();

              if($sub_count == 4) { continue; }

              // If post is sub-featured proceed as normal, else move on to the next post
              $sub_featured = 0;
              foreach(wp_get_object_terms($post->ID, 'meta_info', '') as $term) {
                if($term->slug == 'homepage-sub-feature') {
                  $sub_featured = 1;
                }
              }
              if(!$sub_featured) { continue; }

              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
        ?>
        <article>
          <? if (has_post_thumbnail()) { $sub_thumbnail_count++; ?>
            <div class="thumbnail<? if ($sub_thumbnail_count > 2) echo " thumbnail-secondary" ?>">
              <?php get_media($post->ID, 'thumbnail') ?>
            </div>
          <? } ?>            
          <div class="byline"><?php coauthors_posts_links(); ?> / <?php (get_the_date('M') == "May") ? the_time('M j'): the_time('M. j'); ?></div>
          <h4><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h4>
          <?php 
            the_excerpt();
            $sub_count++;
          ?>
        </article>
        <?php endwhile; endif; ?>
      </div>
      <div class="three columns" id="homepage-sidebar-feature">
        <h5 id="most-popular">Most Popular</h5>
        <ul>
          <?php
            $sidebar_count = 0;
            if ($featured->have_posts()):
              while ($featured->have_posts()):
                $featured->the_post();

                if($sidebar_count == 5) { continue; }

              // If post is sub-featured proceed as normal, else move on to the next post
              $sidebar_featured = 0;
              foreach(wp_get_object_terms($post->ID, 'meta_info', '') as $term) {
                if($term->slug == 'homepage-sidebar-feature') {
                  $sidebar_featured = 1;
                }
              }
              if(!$sidebar_featured) { continue; }

              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
          ?>
            <li><h5><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h5></li>
          <?php $sidebar_count++; ?>  
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
        </ul>
        <div id="email-subscription">
          <div id="email-subscription-header">
            <img src="<? bloginfo('template_directory'); ?>/images/envelope.png">
            <h5>Subscribe</h5>
          </div>
          <form action="http://visitor.r20.constantcontact.com/manage/optin?v=001xOeZxHk9_74v3tJ0vKZ015fg_AXzZHuaPKjOsQWMOwjJ5rG_UmxwHsPLhkZ27v-L2RqgOYnEihStAW5_Y-13ffO_81ZamI6hyq5OnoxdaB4%3D" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
            <input type="email" value="" name="subscriberProfile.visitorProps[0].value" class="email" id="mce-EMAIL" id="field_80" required>
          </form>
        </div>
      </div>
    </section>
    <section class="row">
      <div class="twelve columns">
        <hr>
      </div>
    </section>    
    <section class="row">
      <div id="people-projects" class="six columns">
        <h2>People &amp; Projects</h2>
          <section class="row">
            <?php 
              $sticky_args = array(
                'posts_per_page' => 6,
                'meta_key' => '_thumbnail_id',
                'meta_info' => 'homepage-top-feature',
                'post_type' => array('people_project')
              );

              $sticky = new WP_Query($sticky_args);

              $featured_post_count = $sticky->post_count;
              if ($featured_post_count < 6) {
                $non_sticky_posts = 6 - $featured_post_count;
                $regular_args = array(
                  'posts_per_page' => $non_sticky_posts,
                  'meta_key' => '_thumbnail_id',
                  'meta_info' => 'homepage-sub-feature',
                  'post_type' => array('people_project'),
                  'orderby' => 'rand' 
                );
                
                $regular = new WP_Query($regular_args);                
              }

              if($sticky->have_posts()) {
                while($sticky->have_posts()):
                  $sticky->the_post();
            ?>
              <div class="four columns people-project">
                <h6><? print_r(get_post_meta($post->ID, '_companion_name', true)) ?></h6>
                <h5>
                  <a href="<?php echo strip_tags(get_the_excerpt()); ?>">                  
                    <?php the_title(); ?>
                  </a>
                <h5>
                <a href="<?php echo strip_tags(get_the_excerpt()); ?>">
                  <div class="people-project-thumbnail">
                    <?php get_media($post->ID, 'thumbnail') ?>
                  </div>
                </a>
              </div>
            <?php endwhile; } ?>
            <?php wp_reset_postdata(); ?>

            <?
            if (isset($regular)) {
              if($regular->have_posts()) {
                while($regular->have_posts()):
                  $regular->the_post();
            ?>
              <div class="four columns people-project">
                <h6><? print_r(get_post_meta($post->ID, '_companion_name', true)) ?></h6>
                <a href="<?php echo strip_tags(get_the_excerpt()); ?>">                  
                  <h5><?php the_title(); ?></h5>
                  <div class="people-project-thumbnail">
                    <?php get_media($post->ID, 'thumbnail') ?>
                  </div>
                </a>
              </div>
            <?php endwhile; }} ?>
            <?php wp_reset_postdata(); ?>

          </section>
      </div>
      <div id="cityjournal" class="six columns">
        <h3><a href="/cityjournal">CityJournal</a></h3>
          <?php 
            $latest_args = array(
              'posts_per_page' => 3,
              'post_type' => array('city_journal')
            );

            $latest = new WP_Query($latest_args);
            $latest_count = 0;
            if($latest->have_posts()) {
              while($latest->have_posts()):
                $latest->the_post();
                  if ($latest_count == 0) {
          ?>
            <h4>
              <a href="<? the_permalink() ?>"><? the_title() ?></a>
            </h4>
            <h5>
              by <? the_author() ?>
            </h5>
            <p>
              <? echo get_the_excerpt() ?> <a href="<? the_permalink() ?>">Continued...</a>
            </p>
            <? } else { ?>
            <? if ($latest_count == 1) { ?>
              <div id="sub-cityjournal" class="row">
                <div class="six columns">
            <? } ?>  
            <? if ($latest_count == 2) { ?>
              <div class="six columns">
            <? } ?>                   
              <h5>
                <a href="<? the_permalink() ?>"><? the_title() ?></a>
              </h5>
              <h6>
                by <? the_author() ?>
              </h6>     
            <? if ($latest_count == 1) { ?>
              </div>
            <? } ?>                    
              <? if ($latest_count == 2) { ?>
                  </div>
                </div>
              <? } ?>                  
            <? } $latest_count++; ?>
          <?php endwhile; } ?>
          <?php wp_reset_postdata(); ?>
      </div>      
    </section>
  </section>
<?php get_footer(); ?>