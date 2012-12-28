




<!DOCTYPE html>
<html lang="en">
<head>
  <title>AxisPhilly</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
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

<?php

wp_nav_menu( array( 'container' => 'none', 'walker' => new Walker_Nav_Menu_CMS() ) );

?>

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





  
  </div><!-- End fixed header -->