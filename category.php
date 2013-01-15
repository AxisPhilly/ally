<?php 
  get_header(); 
  // Get Slug from URL
  $slug = get_slug();
  // Get Category ID from Slug
  $slug_id = get_category_by_slug($slug)->term_id;
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
    <div class="row" id="category-top">
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
              <?php echo category_description(get_category_by_slug($slug)->term_id); ?>
            </div>
          </li>
        </ul>
      </div>
      <!-- Features -->
      <div id="feature-container" class="seven columns">
        <div id="featured">
          <!-- features go here -->
          <?php
            query_posts(array(
              'orderby' => 'date', 
              'order' => 'DESC',
              'posts_per_page' => 5,
              'meta_info' => 'featured',
              'meta_key' => '_thumbnail_id',
              'post_status' => 'publish',
              'category_name' => $slug,
              'post_type' => array('post', 'external_post', 'external_tool', 'wp_tool')
            ));

            if (have_posts()):
              while (have_posts()):
                the_post();
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
        </div>
      </div><!-- End Features -->
    </div><!-- End Header Row -->
    <div class="project-section-nav small" data-spy="affix" data-offset-top="315">
      <div class="row">
        <dl class="tabs three-up">
          <dd class="active"><a href="#feed">Stories</a></dd>
          <dd><a href="#data">Tools</a></dd>
          <dd><a href="#talk">Discuss</a></dd>
        </dl>
      </div>
    </div>
    <div class="project-section-nav large" data-spy="affix" data-offset-top="524">
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
                query_posts(array(
                  'orderby' => 'date', 
                  'order' => 'DESC',
                  'post_status' => 'publish', 
                  'category_name' => $slug, 
                  'post_type' => array('post' , 'external_post'),
                  'posts_per_page' => -1
                ));
                if (have_posts()):
                  while (have_posts()):
                    the_post();
              ?>
                <?php get_template_part('archive', 'feed'); ?> 
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
                query_posts(array(
                  'orderby' => 'date', 
                  'order' => 'DESC',
                  'post_status' => 'publish', 
                  'category_name' => $slug, 
                  'post_type' => array('wp_tool', 'external_tool')
                ));
                if (have_posts()):
                  while (have_posts()):
                    the_post();
              ?>
                <div class="tool">
                  <?php
                    echo "<a href='"; 
                    if (get_post_type() == 'external_tool') 
                      echo get_post_meta( $post->ID, '_url_name', true);
                      else the_permalink();
                    echo "'>";
                  ?>              
                  <?php the_post_thumbnail(); ?>
                    <div class="caption">
                      <h5><?php the_title(); ?></h5>
                      <span><?php the_excerpt(); ?></span>
                    </div>
                  </a>
                </div>
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
</div><!-- End Content Container -->
<!-- Included JS Files (Compressed) -->
<script type="text/javascript">
</script>
<?php get_footer(); ?>