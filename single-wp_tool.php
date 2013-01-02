<!-- Loads header.php -->
<?php get_header(); ?>  
<?php
  $my_query = $wp_query;
  if ($my_query->have_posts()) :
  while ($my_query->have_posts()) :
  $my_query->the_post();
?>
  <div class="content-container">
    <div id="news-container">
      <div class="row article-container view">
        <?php
          // Checks to see if meta_info Fullscreen is selected
          if (!in_meta_info(18)) 
            $fullscreen = 1;
        
          if ($fullscreen) 
          get_sidebar('sidebar.php'); 
        ?>
        <div class="<? if ($fullscreen) echo "gutter one " ?> columns"> 
      </div>
        <article class="single-story <? if ($fullscreen) echo "seven"; else "twelve" ?> columns">
          <div class="single-article view">
            <header class="article-header">
              <h2 class="headline"><? the_title(); ?></h2>
              <div class="publish-container">
                <div class="byline">by <a href="#"><? coauthors_posts_links(); ?></a></div>
                <div class="datetime"><? the_time('F j, Y'); ?></div>
              </div>
            </header>
            <div class="article-text">
            <?php
              setup_postdata($post);
              the_content(); 
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
        <div class="gutter one columns"> 
        </div>
      </div>
    </div><!-- End News Container -->
    <!-- Loads footer.php -->
    <?php get_footer(); ?>
    <?php
      //loop content
      endwhile;
      endif;
    ?>
  </div><!-- End Content Container -->
  <!-- Included JS Files (Compressed) -->
  <script src="<?php bloginfo( 'template_directory' ); ?>/javascripts/site.0.0.1.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo( 'template_directory' ); ?>/javascripts/foundation.min.js" type="text/javascript"></script>
  <!-- Init Foundation Components -->
  <script src="../../../javascripts/foundation/app.js"></script>
  <script src="http://localhost:8080/target/target-script-min.js#anonymous"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      window.addEventListener('load', function() {
        new FastClick(document.body);
      }, false);
    });
  </script>