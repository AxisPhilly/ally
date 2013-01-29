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
            <h2 class="headline"><?php the_title(); ?></h2>
            <div class="publish-container">
              <div class="byline"><span class="byline-author">by <?php coauthors_posts_links(); ?> </span><span class="byline-date"><?php the_time('M. j'); ?></span></div>
            </div>
            <?php if ((has_post_thumbnail($post->ID)) && (!in_array('hide-featured', $meta_tags))):  ?>
              <div class="media-container full-page">
                <div class="media">
                  <?php get_media($post->ID, 'large'); ?>
                </div>
                <div class="caption">
                  <p>
                    <?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?>
                  </p>
                </div>
              </div>
            <?php endif; ?>
          </header>
          <div class="small-share">
            <input type="text" name="shorturl" class="shorturl" value="<?php echo wp_get_shortlink(); ?>" readonly="readonly"/>
            <a title="tweet" href="https://twitter.com/share?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(wp_get_shortlink());?>&amp;via=AxisPhilly" target="_blank"><i class="social-foundicon-twitter"></i></a>
            <a title="share on Facebook" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo urlencode(get_the_title()); ?>&amp;p[url]=<?php echo urlencode(wp_get_shortlink()); ?>&amp;p[summary]=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="social-foundicon-facebook"></i></a>
            <a title="email" href="mailto:?subject=AxisPhilly: <?php echo urlencode(get_the_title()); ?>&amp;body=<?php echo urlencode(wp_get_shortlink());?> <?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="general-foundicon-mail"></i></a>
          </div>
          <div class="article-text">
            <?php
              setup_postdata($post);
              the_content();
            ?>
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