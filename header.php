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
  <title>AxisPhilly</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta property="og:site_name" content="AxisPhilly">
  <meta property="og:url" content="<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>">
  <meta property="og:type" content="<?php
    if (stripos($_SERVER["REQUEST_URI"], 'article/')) { 
      echo 'article'; 
    } elseif (stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo 'project page';
    } else {
      echo 'homepage';
    }
  ?>">
  <meta property="og:title" content="<?php
    if(stripos($_SERVER["REQUEST_URI"], 'article/')) {
      echo get_the_title();
    } elseif (stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo get_category_by_slug(get_slug())->name . ' Project Page';
    } else {
      echo 'AxisPhilly Homepage';
    }
  ?>">
  <meta property="og:description" content="<?php echo get_the_excerpt(); ?>">
  <meta property="og:image" content="<?php echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); ?>">
  <meta property="twitter:site" content="@AxisPhilly">
  <meta property="twitter:card" content="summary">
  <meta property="twitter:url" content="<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>">
  <meta property="twitter:title" content="<?php echo get_the_title(); ?>">
  <meta property="twitter:description" content="<?php echo get_the_excerpt(); ?>">
  <meta property="twitter:image" content="<?php echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); ?>">
  <?php
    if (in_array('scalable', $meta_tags))
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">';
    else
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">';
  ?>
  <script type="text/javascript" src="//use.typekit.net/nuc2aoh.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/modernizr.foundation.js"></script>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/libraries.0.0.1.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
</head>
<body <?php body_class(); ?>>
  <!-- Fixed header -->
  <header class="header">
    <div class="header-logo">
      <div class="row">
        <div>
          <a href="/"><img src="<?php bloginfo('template_directory'); ?>/images/transparent.png"/></a>
        </div>
      </div>
    </div>
    <div class="nav-container contain-to-grid">
      <nav class="top-bar">
        <ul>
          <li class="name">
            <a href="/"><img class="compressed-logo" src="<?php bloginfo('template_directory'); ?>/images/logo.png"/>AxisPhilly</a>
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
            <li class="has-dropdown">
              <a href="#">Our Projects</a>
              <ul class="dropdown">
                <li><a href="/project/avi">AVI</a></li>
                <li class="last"><a href="/project/lobbying">Lobbying</a></li>
              </ul>
            </li>
            <li><a href="/tools">Tools &amp; Data</a></li>
            <li><a href="/about">About</a></li>
            <li class="search">
            <li><a href="http://www.twitter.com/AxisPhilly"><i class="social-foundicon-twitter"></i></a></li>
            <li><a href="http://www.facebook.com/AxisPhilly"><i class="social-foundicon-facebook"></i></a></li>
            <li><a href="http://www.axisphilly.org/?feed=rss"><i class="social-foundicon-rss"></i></a></li>
          </ul>
          <ul class="right">
            <li class="search">
              <form class="collapse" role="search" method="get" id="searchform" action="<?php echo site_url(); ?>">
                <input type="search" placeholder="Search" name="s" id="s">
              </form>
            </li>
          </ul>
        </section>
      </nav>
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