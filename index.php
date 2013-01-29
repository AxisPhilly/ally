<?php 
  get_header(); 
  $feature_args = array(
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => -1,
    'meta_info' => 'homepage-feature',
    'post_type' => array('post', 'external_tool', 'wp_tool', 'discussion')
  );

  $featured = new WP_Query($feature_args);
?>
  <section id="homepage-content">
    <section id="features" class="row">
      <div class="nine columns">
        <div class="row">
          <div class="eight columns" id="top-featured">
            <?php
              $top_count = 0;
              if ($featured->have_posts()):
                while ($featured->have_posts()):
                  $featured->the_post();

                  if ($top_count) { continue; }

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
              <?php get_media($post->ID, 'large') ?>
              <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
                  <?php list_categories(); ?>
              <?php } ?>          
                <h2 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                <div class="byline">
                  <span class="byline-author">by <?php coauthors_posts_links(); ?> </span>
                  <span class="byline-date"><?php the_time('M. j'); ?></span>
                </div>
                <?php 
                  the_excerpt();
                  $top_count++;
                ?>
              <?php endwhile; endif; ?>
            </article>
          </div>
          <div class="four columns" id="sub-featured">
            <?php
              $sub_count = 0;
              if ($featured->have_posts()):
                while ($featured->have_posts()):
                  $featured->the_post();

                  if($sub_count == 2) { continue; }

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
              <?php get_media($post->ID, 'thumbnail') ?>
              <?php list_categories(); ?>
              <h3 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h3>
              <div class="byline">
                <span class="byline-author">by <?php coauthors_posts_links(); ?> </span>
                <span class="byline-date timeago"><?php the_time('M. j'); ?></span>
              </div>
              <?php 
                the_excerpt();
                $sub_count++;
              ?>
            </article>
            <?php endwhile; endif; ?>
          </div>
        </div>
      </div>
      <div class="three columns">
        <div class="row">
          <div class="twelve columns" id="sidebar-featured">
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
            <article <?php if(get_post_type($post->ID) == 'discussion'){ echo 'class="discussion"'; }?>>
              <?php list_categories(); ?>
              <h4 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h4>
              <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
            </article>
            <?php $sidebar_count++; ?>  
            <?php endwhile; endif; ?>
            <?php wp_reset_postdata(); ?>
            <div class="archive-link">
              <h4><a href="/archive">Story Archive &#8594;</a></h4>
            </div>
          </div>
        </div>
      </div>

    </section>
    <section id="tools" class="row">
      <div class="three columns">
        <h4>Tools &amp; Data</h4>
        <p>We're building interactive maps, graphics, and applications to illuminate stories.</p>
        <p><a href="/tools">View more tools &#8594;</a></p>
      </div>
      <?php 
        $tools_args = array(
          'orderby' => 'date',
          'order' => 'desc',
          'posts_per_page' => 3,
          'post_type' => array('wp_tool', 'external_tool')
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
      <div class="three columns">
        <article class="tool">
          <?php get_media($post->ID, 'thumbnail') ?>
          <h4 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h4>
          <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
          <?php the_excerpt(); ?>
        </article>
      </div>
      <?php endwhile; endif; ?>
      <?php wp_reset_postdata(); ?>
    </section>
    <section id="projects" class="row">
      <div class="three columns">
        <h4>Projects</h4>
        <p>Our focus areas and investigations. Find tools, discussion, and curated stories from other media organizations on each topic.</p>
      </div>
      <?php 
        $project_id = get_category_by_slug('project')->term_id;

        $projects_args = array(
          'parent' => strval($project_id),
          'number' => 3
        );

        $projects = get_categories($projects_args);

        foreach($projects as $project) {
      ?>
        <div class="three columns">
          <article class="project-summary">
            <h5><?php echo $project->name ?></h5>
            <?php 
              $latest_args = array(
                'posts_per_page' => 1,
                'category_name' => $project->slug,
                'post_type' => array('post')
              );

              $latest = new WP_Query($latest_args);

              if($latest->have_posts()) {
                while($latest->have_posts()):
                  $latest->the_post();
            ?>

              <?php get_media($post->ID, 'thumbnail') ?>
              <h4 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
              <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
              <?php the_excerpt(); ?>

            <?php endwhile; } ?>
            <?php wp_reset_postdata(); ?>
            <a href="/project/<?php echo $project->slug; ?>">Go to project page &#8594;</a>
          </article>
        </div>
      <?php } ?>
    </section>
  </section>
<?php get_footer(); ?>