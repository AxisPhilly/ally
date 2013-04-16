<?php get_header(); ?>
<div class="content-container">
  <div id="news-container">
    <div class="row article-container view">
      <div class="leftbar">
        <?php
          // Checks to see if meta_info Fullscreen is selected
          if (!in_array('full-screen', $meta_tags))
            get_sidebar('sidebar.php');
            $sidebar = 1;
        ?>
      </div>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>' ?>
      <article class="single-story <?php if (!in_array('full-screen', $meta_tags)) echo "seven"; else echo "twelve"; ?> columns">
        <div class="single-article view">
          <header class="article-header">
            <h1 class="headline"><?php the_title(); ?></h1>
            <div class="publish-container">
              <div class="byline"><span class="byline-date"><?php the_time('M. j, Y'); ?></span></div>
            </div>
          </header>
          <div class="small-share">
            <input type="text" name="shorturl" class="shorturl" value="<?php echo wp_get_shortlink(); ?>"/>
            <a title="tweet" href="https://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(wp_get_shortlink());?>&amp;via=AxisPhilly" target="_blank"><i class="social-foundicon-twitter"></i></a>
            <a title="share on Facebook" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo urlencode(get_the_title()); ?>&amp;p[url]=<?php echo urlencode(wp_get_shortlink()); ?>&amp;p[summary]=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="social-foundicon-facebook"></i></a>
            <a title="email" href="mailto:?subject=AxisPhilly: <?php echo urlencode(get_the_title()); ?>&amp;body=<?php echo urlencode(wp_get_shortlink());?> <?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="general-foundicon-mail"></i></a>
          </div>
          <div class="article-text">
            <?php
              setup_postdata($post);
              the_content();
              $post_url = get_post_meta($post->ID, '_url_name', true);
              $post_source = get_post_meta($post->ID, '_source_name', true);              
            ?>
            <p><? the_excerpt(); ?> Read it on <? echo "<a href='" . $post_url . "'>" . $post_source ?> &#8594;</a></p>
            <p>
              <?php the_tags('<span class="radius label">','</span> <span class="radius label">','</span>'); ?>
            </p>
          </div>
          <div class="row">
            <div class="bottombar">
              <?php
                // Checks to see if meta_info Fullscreen is selected
                if (!in_array('full-screen', $meta_tags))
                  include('sidebar.php');
              ?>
            </div>
          </div>
          <nav class="article-navigation row">
            <ul>
              <li class="previous six columns mobile-two">
                <?php previous_post_link('%link', '<strong>&#8592; Previous</strong><span>%title</span>'); ?>
              </li>
              <li class="next six columns mobile-two">
                <?php next_post_link('%link', '<strong>Next &#8594;</strong><span>%title</span>'); ?>
              </li>
            </ul>
          </nav>
        </div>
      </article>
      <?php if (!in_array('full-screen', $meta_tags)) echo '<div class="gutter one columns"></div>'; ?>
    </div>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<?php get_footer(); ?>