<?php 
  define('WP_DEBUG', true); 
  define('WP_DEBUG_DISPLAY', true);
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    // Generate list of meta tags
    global $meta_tags;
    $meta_tags = get_post_meta_tags();
  ?>

  <?php if( is_front_page() ) : ?>
    <title><?php bloginfo('name'); ?> | <?php bloginfo('description');?></title>
  <?php elseif( is_404() ) : ?>
    <title>Page Not Found | <?php bloginfo('name'); ?></title>
  <?php elseif( is_search() ) : ?>
    <title><?php printf(__ ("Search results for '%s'", "punchcut"), attribute_escape(get_search_query())); ?> | <?php bloginfo('name'); ?></title>
  <?php else : ?>
    <title><?php wp_title($sep = ''); ?> | <?php bloginfo('name');?></title>
  <?php endif; ?>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!--[if IE]>
     <script src="<?php bloginfo('template_directory'); ?>/javascripts/html5shiv.js"></script>  
  <![endif]-->
  <meta property="og:site_name" content="AxisPhilly">
  <meta property="og:url" content="http://www.<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>">
  <meta property="og:type" content="<?php
    if (stripos($_SERVER["REQUEST_URI"], 'article/')) { 
      echo 'article'; 
    } elseif (stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo 'project page';
    } elseif (stripos($_SERVER["REQUEST_URI"], 'tool/')) {
      echo 'tool';
    } elseif (stripos($_SERVER["REQUEST_URI"], 'about/')) {
      echo 'about page';
    } elseif (stripos($_SERVER["REQUEST_URI"], 'author/')) {
      echo 'author page';
    } elseif (stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo 'discussion page';
    } else {
      echo 'homepage';
    }
  ?>">
  <meta property="og:title" content="<?php
    if(stripos($_SERVER["REQUEST_URI"], 'article/') || stripos($_SERVER["REQUEST_URI"], 'tool/') || stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo get_the_title();
    } elseif (stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo get_category_by_slug(get_slug())->name . ' Project Page';
    } else {
      echo 'AxisPhilly Homepage';
    }
  ?>">
  <meta property="og:description" content="<?php 
    if(stripos($_SERVER["REQUEST_URI"], 'article/') || stripos($_SERVER["REQUEST_URI"], 'tool/') || stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo get_the_excerpt();
    } elseif(stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo 'Project page';
    } else {
      echo 'Where News Breaks Through';
    }
  ?>">
  <meta property="og:image" content="<?php echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); ?>">
  <meta property="twitter:site" content="@AxisPhilly">
  <meta property="twitter:card" content="summary">
  <meta property="twitter:url" content="http://www.<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>">
  <meta property="twitter:title" content="<?php
    if(stripos($_SERVER["REQUEST_URI"], 'article/') || stripos($_SERVER["REQUEST_URI"], 'tool/') || stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo get_the_title();
    } elseif (stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo get_category_by_slug(get_slug())->name . ' Project Page';
    } else {
      echo 'AxisPhilly Homepage';
    }
  ?>">
  <meta property="twitter:description" content="<?php 
    if(stripos($_SERVER["REQUEST_URI"], 'article/') || stripos($_SERVER["REQUEST_URI"], 'tool/') || stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo get_the_excerpt();
    } elseif(stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo 'Project page';
    } else {
      echo 'Where News Breaks Through';
    }
  ?>">
  <meta property="twitter:image" content="<?php echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); ?>">
  <?php
    if (in_array('scalable', $meta_tags))
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">';
    else
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">';
  ?>
  <link rel="icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.png" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.png" type="image/x-icon" />
  <script type="text/javascript" src="//use.typekit.net/nuc2aoh.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/modernizr.foundation.js"></script>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/libraries.1.0.1.min.js" type="text/javascript"></script>
  <?php if (stripos(home_url(), 'axisphilly.org')) { // production ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/style.css" />
  <?php } else { // dev ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
  <?php } ?>
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36899552-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
</head>
<body <?php body_class(); ?>>
  <!-- Fixed header -->
  <header class="header">
    <div class="header-logo">
      <div class="row">
        <div class="twelve columns">
          <div>
            <a href="/"><img alt="AxisPhilly: Where News Breaks Through" src="<?php bloginfo('template_directory'); ?>/images/transparent.png"/></a>
          </div>
        </div>
      </div>
    </div>
  <div class="row">
    <div class="twelve columns">
      <div class="nav-container contain-to-grid">
        <nav class="top-bar">
          <ul>
            <li class="name">
              <a href="/"><img class="compressed-logo" alt="AxisPhilly: Where News Breaks Through" src="<?php bloginfo('template_directory'); ?>/images/logo.png"/>AxisPhilly</a>
            </li>
            <li class="toggle-topbar has-button">
              <span class="tiny secondary button menu-button">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
              </span>
            </li>
          </ul>
          <section>
            <ul class="left">
              <li <?php if($_SERVER["REQUEST_URI"] == '/') { echo 'class="active"'; } ?>><a href="/">Home</a></li>
              <li class="has-dropdown">
                <a href="#">Our Projects</a>
                <ul class="dropdown">
                  <li <?php if(stripos($_SERVER["REQUEST_URI"], 'taxes/')) { echo 'class="active"'; } ?>><a href="/project/taxes">Taxes</a></li>
                  <li <?php if(stripos($_SERVER["REQUEST_URI"], 'open-gov/')) { echo 'class="active"'; } ?>><a href="/project/open-gov">Open Government</a></li>
                  <li <?php if(stripos($_SERVER["REQUEST_URI"], 'litter/')) { echo 'class="active last"'; } ?> class="last"><a href="/project/litter">Litter</a></li>
                </ul>
              </li>
              <li <?php if(stripos($_SERVER["REQUEST_URI"], 'tools/')) { echo 'class="active"'; } ?> ><a href="/tools">Tools &amp; Data</a></li>
              <li <?php if(stripos($_SERVER["REQUEST_URI"], 'about/')) { echo 'class="active"'; } ?> ><a href="/about">About</a></li>
              <li class="search">
              <li class="social"><a href="http://www.twitter.com/AxisPhilly"><i class="social-foundicon-twitter"></i></a></li>
              <li class="social"><a href="http://www.facebook.com/AxisPhilly"><i class="social-foundicon-facebook"></i></a></li>
              <li class="social"><a href="http://www.axisphilly.org/?feed=rss"><i class="social-foundicon-rss"></i></a></li>
            </ul>
            <ul class="right">
              <li class="search">
                <form class="collapse" role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
                  <input type="search" placeholder="Search" name="s" id="s">
                </form>
              </li>
            </ul>
          </section>
        </nav>
      </div>
    </div>
  </div>
  </header>
  <?php
    #<!-- This is a WordPress generated header that doesn't quite work yet. It uses a custom Walker class, which is referred to in functions.php
    #<div class="fixed contain-to-grid">
    #  <nav class="top-bar">
    #    <ul>
    #      <li class="name">
    #        <h1><a href="/demo/">AxisPhilly</a></h1>
    #      </li>
    #      <li class="toggle-topbar has-button">
    #        <span class="tiny secondary button menu-button">
    #          <span class="line"></span>
    #          <span class="line"></span>
    #          <span class="line"></span>
    #        </span>
    #      </li>
    #    </ul>
    #    <section> -->
    #        //wp_nav_menu(array('container' => 'none', 'walker' => new Walker_Nav_Menu_CMS()));
    #      <!--
    #      <ul class="social desktop">
    #        <li><a href="twitter"><i class="social-foundicon-twitter"></i></a></li>
    #        <li><a href="facebook"><i class="social-foundicon-facebook"></i></a></li>
    #        <li><a href="googleplus"><i class="social-foundicon-google-plus"></i></a></li>
    #        <li><a href="rss"><i class="social-foundicon-rss"></i></a></li>
    #      </ul>
    #      <ul class="right">
    #        <li class="search">
    #          <form class="collapse">
    #            <input type="search" placeholder="Search"></input>
    #          </form>
    #        </li>
    #      </ul>
    #    </section>
    #  </nav>
    #</div> -->
  ?>
<!-- End fixed header -->