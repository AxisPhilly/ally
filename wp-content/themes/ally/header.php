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
  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name');?>" href="<?php bloginfo('url'); ?>/feed/" />  
  <!--[if IE]>
     <script src="<?php bloginfo('template_directory'); ?>/javascripts/html5shiv.js"></script>  
  <![endif]-->
  <meta name="description" content="<?php 
    if(stripos($_SERVER["REQUEST_URI"], 'article/') || stripos($_SERVER["REQUEST_URI"], 'tool/') || stripos($_SERVER["REQUEST_URI"], 'discussion/')) {
      echo get_the_excerpt();
    } elseif(stripos($_SERVER["REQUEST_URI"], 'project/')) {
      echo 'Project page';
    } else {
      echo 'AxisPhilly is a non-profit news and information organization whose mission is to educate and engage citizens on topics of public interest while empowering them with tools to participate in developing and implementing change.';
    }
  ?>">
  <meta name="keywords" content="axisphilly, axis philly, access philly, philadelphia public interest information network, news, philly, data, news applications, taxes, government, poverty, litter, avi, map, maps, avi map, property reassessment, assessments, crime, lobbying"/>  
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

  <?php
    if(isset($post->post_author)) {
      $author_id=$post->post_author;
      $twitter_name = get_the_author_meta('twitter', $author_id);
    } else {
      $twitter_name = 'AxisPhilly';
    }

  ?>
  <meta property="og:image" content="<?php echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); ?>">
  <meta property="twitter:site" content="@AxisPhilly">
  <meta property="twitter:card" content="summary">
  <meta property="twitter:creator" content="@<?php if (empty($twitter_name)) echo "AxisPhilly"; else echo $twitter_name; ?>">
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
  <script type="text/javascript" src="//use.typekit.net/mxe4grf.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/modernizr.foundation.js"></script>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
  <script src="<?php bloginfo('template_directory'); ?>/javascripts/libraries.1.0.6.min.js" type="text/javascript"></script>
  <script>
    $(document).ready(function(){
      $("#s").focus(function() {
        $("#search").animate({ width: "125px" } , 200);
        $("#s").animate({ width: "125px" } , 200);        
      });
      $("#s").blur(function() {
        $("#search").animate({ width: "85px" } , 200);
        $("#s").animate({ width: "85px" } , 200);                
      });      
    });
  </script>  
  <?php // if (stripos(home_url(), 'axisphilly.org')) { // production ?>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
  <?php // } else { // dev ?>
  <?php // } ?>
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
  <div id="header">
    <div class="container">
      <div class="row">
          <div class="three columns" id="logo">
            <a href="/"><img src="<?php bloginfo('template_directory'); ?>/images/axisphillylogo.png" alt="AxisPhilly"></a>
          </div>
          <div id="nav" class="nine columns">
            <div class="eight columns" id="main-menu">
              <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/projects">Projects</a></li>
                <li><a href="/tools">Tools & Data</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="http://twitter.com/axisphilly"><img src="<?php bloginfo('template_directory'); ?>/images/icon-twitter.png" alt="Twitter Logo" class="twitter"></a></li>
                <span id="stretch"></span>
              </ul>
            </div>
            <div class="four columns" id="search">
              <div id="search-container">
                <div id="search-icon"></div>
                <div id="search-box">
                  <input value="" name="s" required="">
                </div>
              </div>
            </div>      
          </div>
      </div>
    </div>
  </div>

  <?php
  
  ?>
<!-- End fixed header -->