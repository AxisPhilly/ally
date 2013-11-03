  <footer>
    <div class="row about">
      <div class="three columns">
        <img class="logo" alt="AxisPhilly" src="<?php bloginfo('template_directory'); ?>/images/logo-no-tagline-white.png"/>
        <p class="copyright">© 2013</p>
      </div>
      <div class="three columns">
        <ul>
          <li><a href="/about">About Us</a></li>
          <li><a href="/about/contact-us">Contact Us</a></li>
          <li><a href="/about/understanding-privacy">Privacy</a></li>
          <li><a href="/about/user-agreement/">User Agreement</a></li>
        </ul>
      </div>
      <section id="mc_embed_signup">
        <div class="three columns">
          <label for="mce-EMAIL" id="email-signup-label">Sign up for email updates:</label>
          <? echo do_shortcode('[constantcontactapi formid="1" lists="1895100121" exclude_lists=""]'); ?>
        </div>
        <div class="three columns">
        </div>
      </section>        

    </div>
  </footer>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/foundation.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/site.1.0.6.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/responsive-nav.min.js" type="text/javascript"></script>
  <script>
    var navigation = responsiveNav("#nav", {
      animate: true,        // Boolean: Use CSS3 transitions, true or false
      transition: 400,      // Integer: Speed of the transition, in milliseconds
      label: "Menu",        // String: Label for the navigation toggle
      insert: "before",      // String: Insert the toggle before or after the navigation
      customToggle: "",     // Selector: Specify the ID of a custom toggle
      openPos: "relative",  // String: Position of the opened nav, relative or static
      jsClass: "js",        // String: 'JS enabled' class which is added to <html> el
      init: function(){},   // Function: Init callback
      open: function(){},   // Function: Open callback
      close: function(){}   // Function: Close callback
    });
  </script>
</body>
</html>