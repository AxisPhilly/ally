<?php while (have_posts()) : the_post(); ?>
  <article class="row">
    <?php if (has_post_thumbnail($post->ID)) { ?>  
      <div class="eight mobile-two columns">
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
            echo "<i>Source: <strong>" . get_post_meta($post->ID, '_source_name', true) . '</strong>' .
                " -- 1/14/12</i>";
          }
          elseif (get_post_type() != 'external_post') {
            echo "by "; coauthors_posts_links();
          }
        ?>
      </div>
      <?php if ((get_post_type() != 'external_post')) { ?> 
        <div class="datetime hide-for-small"><?php the_time('F j, Y');?></div>
      <?php } ?>
      <div class="hide-for-small">
        <?php the_excerpt(); ?>
        <?php the_tags('<span class="round label">','</span> <span class="round label">','</span>'); ?> 
      </div>
    </div>
    <?php if (has_post_thumbnail($post->ID)): ?>  
      <div class="four mobile-two columns">
        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
        <img src="<?php echo $image[0]; ?>">
      </div>
    <?php endif; ?>
  </article> 
<?php endwhile; ?>