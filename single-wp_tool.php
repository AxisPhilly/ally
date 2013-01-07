<?php get_header(); ?>  
<?php
  $my_query = $wp_query;
  if ($my_query->have_posts()):
    while ($my_query->have_posts()):
      $my_query->the_post();
?>
<div class="content-container">
  <div id="news-container">
    <div class="row article-container view">
      <?php
        // Checks to see if meta_info Fullscreen is selected
        if (!in_array('full-screen', $meta_tags))
          get_sidebar('sidebar.php');
      ?>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>' ?>
      <article class="single-story <?php if (!in_array('full-screen', $meta_tags)) echo "seven"; else echo "twelve"; ?> columns">
        <div class="single-article view">
          <header class="article-header">
            <h2 class="headline"><?php the_title(); ?></h2>
            <div class="publish-container">
              <div class="byline">by <a href="#"><? coauthors_posts_links(); ?></a></div>
              <div class="datetime"><?php the_time('F j, Y'); ?></div>
            </div>
          </header>
          <div class="article-text">
          <?php
            setup_postdata($post);
            the_content(); 
          ?>
          <?php
            the_tags('<span class="round label">','</span> <span class="round label">','</span>');
          ?>
          </div>
          <nav class="article-navigation row">
            <ul>
              <li class="previous six columns">
                <?php previous_post_link('%link', '<strong><i class="general-foundicon-left-arrow"></i> Previous</strong><span>%title</span>'); ?>
              </li>
              <li class="next six columns">
                <?php next_post_link('%link', '<strong>Next <i class="general-foundicon-right-arrow"></i></strong><span>%title</span>'); ?>
              </li>
            </ul>
          </nav>
        </div>
      </article>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>' ?>
    </div>
  </div><!-- End News Container -->
  <?php
    //loop content
    endwhile;
    endif;
  ?>
</div><!-- End Content Container -->
<!-- Init Foundation Components -->
<script type="text/javascript">
  $(document).ready(function(){
    window.addEventListener('load', function() {
      new FastClick(document.body);
    }, false);
  });
</script>
<?php get_footer(); ?>