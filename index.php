<?php 
  get_header(); 
  // Get Slug from URL

?>
<!-- Content -->
<div class="content-container">
  <!-- News Container -->
  <div id="news-container">
    <div class="row">
      <div class="columns eight">
        <!-- Features -->
        <div id="feature-container">
          <div id="featured">
            <!-- features go here -->
            <?php
              $featured_args = array(
                'orderby' => 'date', 
                'order' => 'DESC',
                'posts_per_page' => 5,
                'meta_info' => 'featured',
                'meta_key' => '_thumbnail_id',
                'post_status' => 'publish',
                'post_type' => array('post', 'external_tool', 'wp_tool')
              );

              $featured = new WP_Query($featured_args);

              if ($featured->have_posts()):
                while ($featured->have_posts()):
                  $featured->the_post();
            ?>
              <div>
                <?php if (has_post_thumbnail($post->ID)):  
                  $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium'); ?>
                  <img src="<?php echo $image[0]; ?>">
                <?php endif; ?>
                <div class="caption">
                  <h4 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                  <div class="details">
                    <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
                    <?php the_excerpt(); ?>
                  </div>
                </div>
              </div>
            <?php endwhile; endif; ?>
            <?php wp_reset_postdata(); ?>
          </div>
        </div><!-- End Features -->
        <div id="stories">
          <div class="items">
            <?php
              if (have_posts()):
                while (have_posts()):
                  the_post();
              ?>
              <?php get_template_part('archive', 'feed'); ?>  
            <?php endwhile; endif; ?>
          </div>
        </div>

      </div>  
      <div class="columns four">
        <h3>
          Our Projects
        </h3>
        <ul class="accordion">
          <li class="active">
            <div class="title">
              <h5>AVI</h5>
            </div>
            <div class="content">
              <p>The Actual Value Initiative (AVI) is a project to change the way property taxes are assessed in the city. Currently, properties are assessed using an archaic method. Under AVI, the assessed value should be close to the market value of the property. </p>
              <strong>Recent Stories</strong>
              <p>
                <a href="http://192.168.2.76:8888/article/avi-assessed-value-property-tax/">What The Number means</a><Br>
                <a href="http://192.168.2.76:8888/article/map-of-the-week-the-150000-home/">Map of the week: The $150,000 home</a><br>
                <a href="/project/avi">Read More >></a>
              </p>
            </div>
          </li>
          <li>
            <div class="title">
              <h5>Lobbying</h5>
            </div>
            <div class="content">
              <p>The lobbying law which took effect this year requires the quarterly reporting of all lobbying activity. Lobbyists and the firms they work for are required to register with the Board of Ethics, as well as principals---the organizations that conduct or pay for lobbying.</p>
              <strong>Recent Stories</strong>
              <p>
                <a href="http://192.168.2.76:8888/article/lobbyists-gifts-flow-to-city-council-one-city-official-violated-mayors-ban">Lobbyists’ gifts flow to City Council; one city official violated mayor’s ban.</a><br>
                <a href="http://192.168.2.76:8888/article/article/third-quarter-lobbying-data-released-lobbying-ph-updated/">Third quarter lobbying data released, Lobbying.ph updated</a><br>
                <a href="/project/lobbying">Read More >></a>
              </p>
            </div>
          </li>
          <li>
            <div class="title">
              <h5>Crime</h5>
            </div>
            <div class="content">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
          </li>
        </ul>
        <hr>
        <h3>
          Blogs
        </h3>
        <ul class="accordion">
          <li class="active">
            <div class="title">
              <h5>From the Bleacher Seats at Council</h5>
            </div>
            <div class="content">
              <i>Isaiah Thompson's take on the latest in City Council and City Hall in general</i><br>
              <br>
              <p>
                <a href="#">What's For Lunch wit' Councilman Kenney</a><Br>
                <a href="#">Yeah, Good Luck Passing That</a><br><br>
                <a href="#">Read More >></a>
              </p>
            </div>
          </li>
          <li>
            <div class="title">
              <h5>Nerd Blog</h5>
            </div>
            <div class="content">
              <i>The latest from AxisPhilly's News Applications Desk</i><br>
              <br>
              <p>
                <a href="#">Deploying on Heroku</a><Br>
                <a href="#">Our First Open-Source Library: ?????</a><br><br>
                <a href="#">Read More >></a>
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div><!-- End News Container -->
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<?php get_footer(); ?>