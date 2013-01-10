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
              <span class="byline">by <?php coauthors_posts_links(); ?>, <?php the_time('F j, Y'); ?></span>
              <?php the_excerpt(); ?>
            </div>
          </div>
          <?php endwhile; endif; ?>
        </div>
      </div><!-- End Features -->
    </div><!-- End Header Row -->
    <div class="project-section-nav small" data-spy="affix" data-offset-top="305">
      <div class="row">
        <dl class="tabs three-up">
          <dd class="active"><a href="#feed">Stories</a></dd>
          <dd><a href="#data">Tools</a></dd>
          <dd><a href="#talk">Discuss</a></dd>
        </dl>
      </div>
    </div>
    <div class="project-section-nav large" data-spy="affix" data-offset-top="540">
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
                  'post_status' => 'publish', 
                  'category_name' => $slug, 
                  'post_type' => array('post' , 'external_post'),
                  'posts_per_page' => -1
                ));
                if (have_posts()):
                  while (have_posts()):
                    the_post();
              ?>
                <article class="row">
                  <?php if (has_post_thumbnail($post->ID)) { ?>  
                    <div class="six mobile-four columns">
                  <? } else { ?>
                    <div class="twelve mobile-four columns">
                  <? } ?>
                      <h4>
                        <a name="<?php the_id(); ?>" href="<?php 
                          if (get_post_type() != 'external_post') {
                            the_permalink();
                          } else {
                            echo get_post_meta($post->ID, '_url_name', true); 
                          } ?>">
                          <?php 
                            the_title();
                            if (get_post_type() == 'external_post') {
                              echo " <img class='external-link' src='" . get_bloginfo('template_directory') . "/images/external_link.svg'/>"; 
                            }
                          ?>
                        </a>
                      </h4>
                      <div class="byline <?php if(get_post_type() != 'external_post') { echo 'hide-for-small'; } ?>">
                        <?php
                          # Check to see if this is an external_post. If so, display Source and Source URL instead of Author.
                          if (get_post_type() == 'external_post') {
                            echo "<i class='external-source'>Source: <strong>" . get_post_meta($post->ID, '_source_name', true) . '</strong>' .
                                " -- 1/14/12</i>";
                          }
                          elseif (get_post_type() != 'external_post') {
                            echo "by "; coauthors_posts_links();
                          }
                        ?>
                      </div>
                      <?php if ((get_post_type() != 'external_post')) { ?> 
                        <div class="datetime"><?php the_time('F j, Y');?></div>
                      <?php } ?>
                      <div class="hide-for-small">
                        <?php the_excerpt(); ?>
                        <?php the_tags('<span class="round label">','</span> <span class="round label">','</span>'); ?> 
                      </div>
                    </div>
                  <?php if (has_post_thumbnail($post->ID)): ?>  
                    <div class="six columns hide-for-small">
                      <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); ?>
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
                query_posts(array(
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
  $(window).load(function() {
    $('#featured').orbit({ timer: 'true' });
  });
</script>
<?php get_footer(); ?>