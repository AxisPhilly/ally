<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    // Generate list of meta tags
    global $meta_tags;
    $meta_tags = list_of_meta_tags();
  ?>
  <title>AxisPhilly</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <?php
    if (in_array('scalable', $meta_tags))
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1">';
    else
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">';
  ?>

  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,800,600' rel='stylesheet' type='text/css'>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/modernizr.foundation.js"></script>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/libraries.0.0.1.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
</head>
<body <?php body_class(); ?>>
  <!-- Fixed header -->
  <div class="fixed contain-to-grid">
    <nav class="top-bar">
      <ul>
        <li class="name">
          <h1><a href="/demo/">AxisPhilly</a></h1>
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
          <li class="divider"></li>
          <li class="has-dropdown">
            <a href="#">Our Projects</a>
            <ul class="dropdown">
              <li><a href="/projects/avi">AVI</a></li>
              <li><a href="/projects/lobbying">Lobbying</a></li>
              <li><a href="/demo/projects">More...</a></li>
            </ul>
          </li>
          <li class="divider"></li>
          <li><a href="#">Tools &amp; Data</a></li>
          <li class="divider"></li>
          <li><a href="/about">About</a></li>
          <li class="search">
          <li class="divider"></li>
          <li>
            <span class="social mobile">
              <a href="twitter"><i class="social-foundicon-twitter"></i></a>
              <a href="facebook"><i class="social-foundicon-facebook"></i></a>
              <a href="googleplus"><i class="social-foundicon-google-plus"></i></a>
              <a href="rss"><i class="social-foundicon-rss"></i></a>
            </span>
            <ul class="social desktop">
              <li><a href="twitter"><i class="social-foundicon-twitter"></i></a></li>
              <li><a href="facebook"><i class="social-foundicon-facebook"></i></a></li>
              <li><a href="googleplus"><i class="social-foundicon-google-plus"></i></a></li>
              <li><a href="rss"><i class="social-foundicon-rss"></i></a></li>
            </ul>
          </li>
        </ul>
        <ul class="right">
          <li class="search">
            <form class="collapse" role="search" method="get" id="searchform" action="<?php echo site_url(); ?>">
              <input type="search" placeholder="Search" name="s" id="s"></input>
            </form>
          </li>
        </ul>
      </section>
    </nav>
  </div>


  <!-- This is a WordPress generated header that doesn't quite work yet. It uses a custom Walker class, which is referred to in functions.php




  <div class="fixed contain-to-grid">
    <nav class="top-bar">
      <ul>
        <li class="name">
          <h1><a href="/demo/">AxisPhilly</a></h1>
        </li>
        <li class="toggle-topbar has-button">
          <span class="tiny secondary button menu-button">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
          </span>
        </li>
      </ul>
      <section> -->
        <?php
          //wp_nav_menu(array('container' => 'none', 'walker' => new Walker_Nav_Menu_CMS()));
        ?>
  <!--
        <ul class="social desktop">
          <li><a href="twitter"><i class="social-foundicon-twitter"></i></a></li>
          <li><a href="facebook"><i class="social-foundicon-facebook"></i></a></li>
          <li><a href="googleplus"><i class="social-foundicon-google-plus"></i></a></li>
          <li><a href="rss"><i class="social-foundicon-rss"></i></a></li>
        </ul>
        <ul class="right">
          <li class="search">
            <form class="collapse">
              <input type="search" placeholder="Search"></input>
            </form>
          </li>
        </ul>
      </section>
    </nav>
  </div>

End fixed header -->