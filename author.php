<?php 
  // Load Header
  get_header();
  // Get Slug from URL
  $slug = get_slug();
  // Get Author ID from Slug
  $author = get_user_by('slug', $slug);
  $author_id = $author->id;
  ?>
<!-- Content -->
  <div class="content-container">
    <div id="news-container">
      <div class="row author">
        <div class="photo four columns">
          <? echo get_avatar( get_the_author_meta('ID'), 200); ?>
        </div>
        <div class="bio five columns">
          <h2><?php echo $author->display_name ?></h2>
          <p>
            <?php echo $author->user_description ?>
          </p>
        </div>
        <div class="contact three columns">
          <ul>
            <li><a href="mailto:<?php echo $author->user_email ?>"><?php echo $author->user_email ?></a></li>
            <li><a href="http://twitter.com/<? echo $author->twitter ?>"><?php echo $author->twitter ?></a></li>
            <li><?php echo $author->phone ?></li>
          </ul>
        </div>
      </div>
      <div id="stories">
        <div class="items">
          <?php
            query_posts(array('orderby' => 'date', 'order' => 'DESC', 'post_status' => 'publish', 'author' => $author->id));
            if (have_posts()):
              while (have_posts()):
                the_post();
          ?>
          <article class="row"> 
            <?php if (has_post_thumbnail($post->ID)) { ?>  
              <div class="eight mobile-two columns">
            <?php } else { ?>
              <div class="twelve mobile-four columns">
            <?php } ?>
                <h4><a href="<?php  the_permalink(); ?>"><?php  the_title(); ?></a></h4>
                <div class="datetime hide-for-small"><?php  the_time('F j, Y'); ?></div>
                <div class="hide-for-small"><?php  the_excerpt(); ?></div>
              </div>
              <div class="four mobile-two columns">
                <?php the_post_thumbnail(); ?>
              </div>
          </article>
          <?php 
            endwhile;
            endif;
          ?>
        </div>
      </div>
    </div><!-- End News Container -->
  </div><!-- End Content Container -->
<!-- Loads footer.php -->
<?php  get_footer(); ?>