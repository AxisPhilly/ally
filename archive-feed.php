<article class="row">
  <?php if (has_post_thumbnail($post->ID)) { ?>  
    <div class="six mobile-two columns">
  <? } else { ?>
    <div class="twelve mobile-four columns">
  <? } ?>
      <?php if(stripos($_SERVER["REQUEST_URI"], 'project/') == FALSE) { ?>
        <div class="project">
          <?php list_categories(); ?>
        </div>
      <?php } ?>
      <h4>
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
      </h4>
        <?php
          # Check to see if this is an external_post. If so, display Source and Source URL instead of Author.
          if (get_post_type() == 'external_post') { ?>
            <div class="byline-author">
              Source: <strong> <?php echo get_post_meta($post->ID, '_source_name', true); ?></strong> 
              <?php echo get_the_time('F j, Y'); ?>
            </div>
         <?php } elseif (get_post_type() != 'external_post') { ?>
          <div class="byline">
            <span class="byline-author">by <?php coauthors_posts_links(); ?> </span>
            <span class="byline-date"><?php the_time('M. j'); ?></span>
          </div>
        <?php } ?>
      <?php if ((get_post_type() != 'external_post')) { ?> 
      <?php } ?>
      <div class="hide-for-small">
        <?php the_excerpt(); ?>
        <?php the_tags('<span class="radius label">','</span> <span class="radius label">','</span>'); ?> 
      </div>
    </div>
  <?php if (has_post_thumbnail($post->ID)): ?>  
    <div class="six mobile-two columns">
      <? get_media($post->ID, 'medium'); ?>
    </div>
  <?php endif; ?>
</article> 
