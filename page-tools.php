<?php
/**
 * Tools page
 * @package WordPress
 * @subpackage Ally
 */
?>
<? get_header(); ?>
  <!-- Content -->
  <div class="content-container">
    <div class="row">
      <h3 class="twelve columns">Tools &amp; Data</h3>
    </div>

    <div class="row">
      <div class="twelve columns">
        <?php while (have_posts()) : the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>

    <div class="row">
      <div id="tools-and-data">
        <div class="items twelve columns">
        <?php
          $tool_args = array(
            'orderby' => 'date', 
            'order' => 'DESC',
            'post_status' => 'publish', 
            'post_type' => array('wp_tool', 'external_tool')
          );

          $tools = new WP_Query($tool_args);
          $total = 0;
          $count = 1;

          if($tools->have_posts()):
            while($tools->have_posts()):
              $tools->the_post();

            if($total % 3 == 0 || $total == 0) {
              echo '<div class="row">';
              $count = 0;
            } 
        ?>
          <div class="tool four columns">
            <?php
              global $post_url;
              if($post->post_type == 'external_tool') {
                $post_url = get_post_meta($post->ID, '_url_name', true);
              } else {
                $post_url = get_permalink($post->ID);
              }
            ?>
            <a href="<?php echo $post_url ?>">
              <?php the_post_thumbnail(); ?>
              <h5><?php the_title(); ?></h5>
            </a>
            <span><?php the_excerpt(); ?></span>
          </div>
        <?php
          $count++;
          $total++;
          if($count == 3 || $total == count($tools->posts)) { 
            echo '</div>';
          }
          endwhile; endif; 
        ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>