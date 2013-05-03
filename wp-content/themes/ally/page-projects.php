<?php
/**
 * Project page
 * @package WordPress
 * @subpackage Ally
 */
?>
<? get_header(); ?>
  <!-- Content -->
  <div class="content-container" id="project-content">
    <div class="row">
      <h3 class="twelve columns">Projects</h3>
    </div>

    <div class="row">
      <div class="twelve columns">
        <?php while (have_posts()) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
        <?php

          $project_id = get_category_by_slug('project')->term_id;

          $project_args = array(
            'parent' => strval($project_id),
            'number' => 0
          );

          $projects = get_categories($project_args);
          $total = 0;
          $count = 1;
          foreach($projects as $project) {

            $latest_args = array(
              'posts_per_page' => 1,
              'meta_key' => '_thumbnail_id',
              'category_name' => $project->slug,
              'post_type' => array('post')
            );            

            $tools = new WP_Query($latest_args);

            if($tools->have_posts()):
              while($tools->have_posts()):
                $tools->the_post();

              if($total % 4 == 0 || $total == 0) {
                echo '<div class="row">';
                $count = 0;
              } 

              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
              $count++;
              $total++;
              ?>
              <div class="three columns<? if($total == count($projects)) echo ' end';?>">
                <article class="tool">
                  <h5><a href="/project/<?php echo $project->slug; ?>"><?php echo $project->name ?></a></h5>
                  <?php get_media($post->ID, 'thumbnail') ?>
                  <h4 class="headline"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h4>
                  <?php if (get_the_author() != 'axisphilly') { ?>
                    <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php (get_the_date('M') == "May") ? the_time('M j, Y'): the_time('M. j, Y'); ?></span></div>
                  <? } ?>  
                  <?php the_excerpt(); ?>
                </article>
              </div>
          <?php
            if($count == 4 || $total == count($projects)) { 
              echo '</div>';
            }
            endwhile; endif; }
        ?>
  </div>
<?php get_footer(); ?>