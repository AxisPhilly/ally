<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="content-container">
  <div id="content" role="main">
    <?php the_post(); ?>
    
    <div class="row">
      <div id="stories" class="twelve columns">
        <div class="items">
          <?php if (have_posts()): 
            while (have_posts()) : the_post();?>
              <?php get_template_part('archive' , 'feed'); ?>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="twelve columns">
        <?php get_search_form(); ?>
      </div>
    </div>
  
    <div class="row">
      <div class="twelve columns">
        <h3>Archives by Month:</h3>
        <ul>
          <?php wp_get_archives('type=monthly'); ?>
        </ul>
        
        <h3>Archives by Subject:</h3>
        <ul>
           <?php wp_list_categories(); ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>