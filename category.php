<!-- Loads header.php -->
<?php 
  get_header(); 
  $url = $_SERVER["REQUEST_URI"];
  $url_explode= explode('/', $url);
  $slug = $url_explode[sizeof($url_explode)-2];
  $slug_id = get_category_by_slug($slug)->term_id
?>
<!-- Content -->
<div class="content-container">
  <!-- News Container -->
  <div id="news-container">
    <div class="row">
      <header class="project-header twelve columns">
        <h1><?php  echo get_category_by_slug($slug)->name ?></h1>
      </header>
    </div>
    <!-- Header Row -->
    <div class="row">
      <!-- Background -->
      <div id="background" class="five columns">
        <?php echo category_description( get_category_by_slug($slug)->term_id ); ?>
      </div>
      <div id="background-compressed">
        <ul class="accordion">
          <li>
            <div class="title">
              <span><?php echo get_category_by_slug($slug)->name ?></span>
            </div>
            <div class="content">
              <?php echo category_description( get_category_by_slug($slug)->term_id ); ?>             
            </div>
          </li>
        </ul>
      </div>
      <!-- Features -->
      <div id="feature-container" class="seven columns">
        <div id="featured">
          <!-- features go here -->
          <?php
            // "'category__and' => array(12,10)" selects posts that meet two category requirements: featured (12) and avi (10)
            query_posts(array('orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => 1, 'post_status' => 'publish', 'category__and' => array(12, $slug_id), 'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool')));

            if (have_posts()):
              while (have_posts()):
                the_post();
          ?>
          <div>
            <?php if (has_post_thumbnail($post->ID)): ?>  
              <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
              <img src="<?php echo $image[0]; ?>">
            <?php endif; ?>
            <div class="caption">
              <h4 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
              <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
              <?php the_excerpt(); ?>
            </div>
          </div>
          <?php endwhile; endif; ?>
        </div>
      </div><!-- End Features -->
    </div><!-- End Header Row -->
    <div class="project-section-nav small" data-spy="affix" data-offset-top="360">
      <div class="row">
        <dl class="tabs three-up">
          <dd class="active"><a href="#feed">Stories</a></dd>
          <dd><a href="#data">Tools</a></dd>
          <dd><a href="#talk">Discuss</a></dd>
        </dl>
      </div>
    </div>
    <div class="project-section-nav large" data-spy="affix" data-offset-top="570">
      <div class="row">
        <dl class="tabs three-up">
          <dd class="active"><a href="#feed">Stories</a></dd>
          <dd><a href="#data">Tools &amp; Data</a></dd>
          <dd><a href="#talk">Discussion</a></dd>
        </dl>
      </div>
    </div>
    <ul class="tabs-content">
      <li class="active" id="feedTab">
        <a name="stories"></a>
        <div class="row">
          <div id="stories" class="twelve columns">
            <div class="items">
              <?php
                query_posts(array('post_status' => 'publish', 'category_name' => $slug, 'post_type' => array('post' , 'external_post')));
                if (have_posts()):
                  while (have_posts()):
                    the_post();
              ?>
                <article class="row">
                  <?php if (has_post_thumbnail($post->ID)) { ?>  
                    <div class="eight mobile-two columns">
                  <? } else { ?>
                    <div class="twelve mobile-four columns">
                  <? } ?>
                      <h4><a name="<?php the_id(); ?>" href="<?php if ((get_post_type() != 'external_post' )) the_permalink(); else echo get_post_meta( $post->ID, '_url_name', true); ?>"><?php the_title(); ?></a></h4>
                      <div class="byline hide-for-small">
                      <!-- Check to see if this is an external_post. If so, display Source and Source URL instead of Author. -->
                        <?php
                          if (get_post_type() == 'external_post') echo "Source: <a href='".get_post_meta($post->ID, '_url_name', true)."'>".get_post_meta($post->ID, '_source_name', true)."</a>"; if ((get_post_type() != 'external_post')) { echo "by "; coauthors_posts_links(); } 
                        ?>
                      </div>
                      <?php if ((get_post_type() != 'external_post')) { ?> 
                        <div class="datetime hide-for-small"><?php the_time('F j, Y');?></div>
                      <?php } ?>
                      <div class="hide-for-small"><?php the_excerpt(); ?></div>
                    </div>
                  <?php if (has_post_thumbnail($post->ID)): ?>  
                    <div class="four mobile-two columns">
                      <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                      <img src="<?php echo $image[0]; ?>">
                    </div>
                  <?php endif; ?>
              </article>
            <?php endwhile; endif; ?>
            </div>
          </div>
        </div>
      </li>
      <li id="dataTab">
        <a name="tools-and-data"></a>
        <div class="row">
          <div id="tools-and-data" class="twelve columns">
            <div class="items">
              <?php
                query_posts(array('post_status' => 'publish', 'category_name' => $slug, 'post_type' => array('wp_tool', 'external_tool')));
                if (have_posts()):
                  while (have_posts()):
                    the_post();
              ?>
              <a href="<?php the_permalink() ?>">
                <?php if (has_post_thumbnail($post->ID)): ?>  
                  <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                  <img src="<?php echo $image[0]; ?>">
                <?php endif; ?>
                <div class="caption">
                  <h5><?php the_title(); ?></h5>
                  <span><?php the_excerpt(); ?></span>
                </div>
              </a>
              <?php endwhile; endif; ?>
            </div>
          </div>
        </div>
      </li>
      <li id="talkTab">
        <a name="discussion"></a>
        <div class="row">
          <div id="discussion" class="twelve columns">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
          </div>
        </div>
      </li>
    </ul>
  </div><!-- End News Container -->
  <footer></footer>
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/javascripts/libraries.0.0.1.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/javascripts/site.0.0.1.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/javascripts/foundation.min.js" type="text/javascript"></script>
<!-- Templates -->
<!-- Feature story -->
<script type="text/template" id="feature-story-carousel-template">
  <img src="<%= photo %>">
  <div class="caption">
    <h4 class="headline"><a href="/article/<%= slug %>"><%= headline %></a></h4>
    <span class="byline">by <%= author %>, <%= datetime %></span>
    <p> <%= text %> </p>
  </div>
</script>
<!-- News feed story -->
<script type="text/template" id="news-feed-story-template">
  <div class="eight mobile-two columns">
    <h4><a name="id<%= id %>" href="/article/<%= slug %>"><%= headline %></a></h4>
    <div class="byline hide-for-small">by <a href="#"><%= author %></a></div>
    <div class="datetime hide-for-small"><%= datetime %></div>
    <p class="hide-for-small"><%= text %></p>
  </div>
  <div class="four mobile-two columns">
    <img src="<%= thumbnail %>">
  </div>
</script>
<!-- Tool -->
<script type="text/template" id="tool-and-data-template">
  <a href="<%= url %>">
    <img src="<%= photo %>">
    <div class="caption">
      <h5><%= headline %></h5>
      <span><%= text %></span>
    </div>
  </a>
</script>
<!-- External News feed story -->
<script type="text/template" id="external-news-feed-story-template">
  <div class="twelve columns">
    <h4><a name="id<%= id %>" class="external" href="<%= slug %>" target="_blank"><%= headline %></a></h4>
    <div class="datetime hide-for-small"><%= datetime %></div>
    <p class="hide-for-small"><%= text %></p>
  </div>
</script>
<!-- Article container -->
<script type="text/template" id="single-article-container-template">
  <aside id="sidebar" class="three columns view">
  </aside>
  <div class="gutter one columns"></div>
  <article id="story" class="seven columns view">
  </article>
  <div class="gutter one columns"></div>
</script>
<!-- Single article sidebar template-->
<script type="text/template" id="single-article-sidebar-template">
  <div class="moving-container" data-spy="affix" data-offset-top="70">
    <div class="social-container">
      <strong>Share this article</strong>
      <div class="shorturl-container">
        <input type="text" name="shorturl" id="shorturl" value="http://axs.ph/Hnh34g" readonly="true"></input>
      </div>
      <div class="sites">
        <a href="https://twitter.com/intent/tweet?text=<%= headline %>&url=http://test.com"
                target="_blank"><i class="social-foundicon-twitter"></i></a>
        <a href="https://www.facebook.com/sharer.php?s=100&[title]=<%= headline %>&[url]=<%= slug %>&[summary]=<%= text %>&[images][0]=<%= thumbnail %>"
                target="_blank"><i class="social-foundicon-facebook"></i></a>
        <a href="https://plus.google.com/share?url=<%= slug %>" 
                target="_blank"><i class="social-foundicon-google-plus"></i></a>
        <a href="mailto:?subject=AxisPhilly.com: <%= headline %>&body=<%= slug %><%= text %>"
                target="_blank"><i class="general-foundicon-mail"></i></a>
      </div>
    </div>
    <div class="project-info-container">
      <p>
        <strong>This article is part of our series on <a href="#">AVI</a>.
        Read more from the series:</strong>
      </p>
      <div class="recent-stories">
      </div>
    </div>
  </div>
</script>
<!-- Recent story template -->
<script type="text/template" id="single-article-recent-story-template">
  <a href="/article/<%= slug %>"><%= headline %></a>
</script>
<!-- Single article sidebar -->
<script type="text/template" id="single-article-template">
  <header>
    <h2 class="headline"><%= headline %></h2>
    <div class="publish-container">
      <div class="byline">by <a href="#"><%= author %></a></div> 
      <div class="datetime"><%= datetime %></div>
    </div>
    <div class="media-container full-page">
      <div class="media">
        <img src="<%= photo %>"></img>
      </div>
      <div class="caption">
        <p>
          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod. <i>John Doe for PPIIN</i>
        </p>
      </div>
    </div>
  </header>
  <div class="article-text">
    <%= fullText %>
  </div>
  <nav class="article-navigation row">
  </nav>
</script>
<!-- Story Navigation item -->  
<script type="text/template" id="article-navigation-item-template">
  <a href="/article/<%= slug %>">
    <strong><%= direction %></strong>
    <span><%= headline %>bild</span>
  </a>
</script>
<!-- Loads footer.php -->
<?php get_footer(); ?>