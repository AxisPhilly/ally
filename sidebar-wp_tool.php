 <aside id="sidebar" class="three columns hide-for-small">
        <div class="sidebar view">
          <div class="moving-container" data-spy="affix" data-offset-top="70">
            
            <div class="social-container">
              <strong>Share this article</strong>
              <div class="shorturl-container">
                <input type="text" name="shorturl" id="shorturl" value="http://axs.ph/Hnh34g" readonly="true"></input>
              </div>
              <div class="sites">
                <a href="#"><i class="social-foundicon-twitter"></i></a>
                <a href="#"><i class="social-foundicon-facebook"></i></a>
                <a href="#"><i class="social-foundicon-google-plus"></i></a>
                <a href="#"><i class="general-foundicon-mail"></i></a>
              </div>
            </div>




            <?php
              //checks to see if the parent category is project
              $parent_category=13;
              $categories=get_the_category();
              foreach($categories as $category){
                if($category->category_parent==$parent_category){ 
                  $c_name = $category->name;
                  $c_slug = $category->slug; 
                  $c_number = $category->cat_ID; 
            ?>
            <div class="project-info-container">
              <p>
                <strong>This tool is part of our series on <a href="/projects/<?php echo $c_slug; ?>"><?php echo $c_name; ?></a>.
                Read more from the series:</strong>
              </p>
              <div class="recent-stories">
              <?php 
                global $post2;
                $my_query2 = get_posts('numberposts=3&cat='.$c_number);
                foreach($my_query2 as $post2):
                  setup_postdata($post2);
                  $link = get_post_meta($post2->ID, 'site-url', true); ?>
                  <a href="<?php echo $post2->guid; ?>"><?php print_r($post2->post_title); ?></a>
                <?php endforeach; ?>
              </div>
            </div>
            <?php break; } } //break after it finds one instance of the parent category 13 ?>
          </div>
        </div>
      </aside>        