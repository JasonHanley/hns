<!DOCTYPE html>
<html lang="en">

<head>
    <title>HNS</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/custom.css" rel="stylesheet" media="screen">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>

<div id="fb-root"></div>
<script>
var page = '<?php echo $page ?>';
var FBC = {callback: null};

window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo $appId ?>', // App ID from the App Dashboard
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here
    if(FBC.callback)
        FBC.callback();
  };

  // Load the SDK's source Asynchronously
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
</script>

<div id="page">

    <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="./index.php">HNS</a>
            <ul class="nav">
                <li<?php if($page == 'news') echo ' class="active"'?>><a href="./index.php?page=news">News</a></li>
                <!-- <li<?php if($page == 'newest') echo ' class="active"'?>><a href="./index.php?page=newest">Newest</a></li> -->
                <li<?php if($page == 'mine') echo ' class="active"'?>><a href="./index.php?page=mine">My Stories</a></li>
            </ul>
        </div>
    </div>
    
    <div id="content">
        <?php require_once($page.'.php'); ?>
    </div>

</div>
</body>

</html>
