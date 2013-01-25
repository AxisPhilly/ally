<?php get_header(); ?>
  <section class="row" id="homepage-content">
    <div class="six columns" id="homepage-featured">
      <?php
        $featured_args = array(
          'orderby' => 'date', 
          'order' => 'DESC',
          'posts_per_page' => 1,
          'meta_info' => 'featured-homepage',
          'meta_key' => '_thumbnail_id',
          'post_status' => 'publish',
          'post_type' => array('post', 'external_tool', 'wp_tool')
        );

        $featured = new WP_Query($featured_args);

        if ($featured->have_posts()):
          while ($featured->have_posts()):
            $featured->the_post();
      ?>
      <article>
        <?php if (has_post_thumbnail($post->ID)):  
          $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
          <img src="<?php echo $image[0]; ?>">
        <?php endif; ?>
        <div class="column-padding">
          <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
              <?php list_categories(); ?>
          <?php } ?>          
            <h2 class="frontpage"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>


            <?php the_excerpt(); ?>
          <?php endwhile; endif; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </article>
    </div>
    <div class="three columns">
  <?php
    $latest_args = array(
      'orderby' => 'date', 
      'order' => 'DESC',
      'posts_per_page' => 2,
      'post_status' => 'publish',
      'post_type' => array('post')
    );

    $latest = new WP_Query($latest_args);

    if ($latest->have_posts()):
      while ($latest->have_posts()):
        $latest->the_post();
  ?>
  <article>
    <?php if (has_post_thumbnail($post->ID)):  
      $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail'); ?>
      <img src="<?php echo $image[0]; ?>">
    <?php endif; ?>        
    <div class="column-padding">
        <?php list_categories(); ?>
      <h3 class="frontpage"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
      <?php the_excerpt(); ?>
    </div>
  </article>

  <?php endwhile; endif; ?>
  <?php wp_reset_postdata(); ?>
    </div>
    <div class="three columns">
      <?php
        $latest_args = array(
          'orderby' => 'date', 
          'order' => 'DESC',
          'posts_per_page' => 5,
          'post_status' => 'publish',
          'post_type' => array('post')
        );

        $latest = new WP_Query($latest_args);

        if ($latest->have_posts()):
          while ($latest->have_posts()):
            $latest->the_post();
      ?>
      <article>
        <div class="column-padding">
            <?php list_categories(); ?>
          <h4 class="frontpage"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
          <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
        </div>
      </article>

      <?php endwhile; endif; ?>
      <?php wp_reset_postdata(); ?>
      <div class="archive-link">
        <h4><a href="/archive">Story Archive &#8594;</a></h4>
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
    ?>
    <article class="three columns tool">
      <?php if (has_post_thumbnail($post->ID)):  
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail'); ?>
        <img src="<?php echo $image[0]; ?>">
      <?php endif; ?> 
      <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
      <div class="byline">by <?php coauthors_posts_links(); ?>—<?php the_time('M. j'); ?></div>
      <div><?php the_excerpt(); ?></div>
    </article>
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
      <article class="project-summary three columns">
        <h5><?php echo $project->name ?></h5>
        <?php 
          $latest_args = array(
            'posts_per_page' => 1,
            'category_name' => $project->slug
          );

          $latest = new WP_Query($latest_args);

          if($latest->have_posts()) {
            while($latest->have_posts()):
              $latest->the_post();
        ?>

          <?php if (has_post_thumbnail($post->ID)):  
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail'); ?>
            <img src="<?php echo $image[0]; ?>">
          <?php endif; ?>        

          <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
          <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
          <?php the_excerpt(); ?>

        <?php endwhile; } ?>
        <?php wp_reset_postdata(); ?>
        <a href="/project/<?php echo $project->slug; ?>">Go to project page &#8594;</a>
      </article>
    <?php } ?>
    <article class="project-summary three columns">
      <p><strong>More projects coming soon</strong></p>
    </article>
  </section>
<?php get_footer(); ?>