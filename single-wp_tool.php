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


<? 
// Checks to see if meta_info Fullscreen is selected
if (!in_meta_info(18)) 
  $fullscreen = 1;

if ($fullscreen) 
  get_sidebar('sidebar.php'); ?>


        <div class="<? if ($fullscreen) echo "gutter one " ?>columns"> 
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

<?
setup_postdata($post);
 the_content(); ?>     

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
    <footer></footer>
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
    <!-- Single article template -->
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
<?php
  //loop content
  endwhile;
  endif;
  ?>
    </script>
    <!-- Story Navigation item -->  
    <script type="text/template" id="article-navigation-item-template">
      <a href="article/<%= slug %>">
        <strong><%= direction %></strong>
        <span><%= headline %></span>
      </a>
    </script>
<!-- Loads footer.php -->
<?php get_footer(); ?>