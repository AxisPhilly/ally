<article class="row">
  <?
    $a_column = false;
    if (count(get_column())>0) {
      $a_column = true;
    }
  ?>
  <?php if (has_post_thumbnail($post->ID)&&(!$a_column)) { ?>  
    <div class="six mobile-two columns">
  <? } else { ?>
    <div class="twelve mobile-four columns">
  <? } ?>
      <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
        <div class="project">
          <?php list_categories(); ?>
        </div>
      <?php } ?>
      <h3 class="headline">
        <a id="<?php the_id(); ?>" href="<?php 
          if(get_post_type() == 'external_post' || get_post_type() == 'external_tool') {
            echo get_post_meta($post->ID, '_url_name', true);
          } else {
            the_permalink();
          } ?>">
          <?php 
            the_title();
            if (get_post_type() == 'external_post') {
              echo " <img class='external-link' src='" . get_bloginfo('template_directory') . "/images/external_link.svg'/>"; 
            }
          ?>
        </a>
      </h3>
        <?php
          # Check to see if this is an external_post. If so, display Source and Source URL instead of Author.
          if (get_post_type() == 'external_post') { ?>
            <div class="byline">
              <span class="byline-author"> Source: <strong> <?php echo get_post_meta($post->ID, '_source_name', true); ?></strong></span><span class="byline-date"> on
              <?php (get_the_date('M') == "May") ? the_time('M j, Y'): the_time('M. j, Y'); ?></span>
            </div>
         <?php } elseif (get_post_type() != 'external_post') { ?>
            <?php if (get_the_author() != 'axisphilly') { ?>
            <div class="byline">
              <span class="byline-author">by <?php coauthors_posts_links(); ?></span><span class="byline-date"> on <?php (get_the_date('M') == "May") ? the_time('M j, Y'): the_time('M. j, Y'); ?></span>
            </div>
            <?php } ?>
        <?php } ?>
      <?php if ((get_post_type() != 'external_post')) { ?> 
      <?php } ?>
      <div class="hide-for-small stream-excerpt">
        <?php the_excerpt(); ?>
        <?php the_tags('<span class="radius label">','</span> <span class="radius label">','</span>'); ?> 
      </div>
    </div>
  <?php if (has_post_thumbnail($post->ID)&&(!$a_column)): ?>  
    <div class="six mobile-two columns">
      <? get_media($post->ID, 'medium'); ?>
    </div>
  <?php endif; ?>
</article> 
