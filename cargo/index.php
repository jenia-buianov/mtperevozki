
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title>:: Missing - A Lost Page ::</title>
<meta name="description" content="Missing is  a responsive 404 Page Template can be used for missing or broken links on your websites.">
<meta name="author" content="saptarang">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!--STYLESHEETS-->
<link href="http://www.dezinethemes.com/envato/missing/1/2/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="http://www.dezinethemes.com/envato/missing/1/2/css/style.css" />

<!--GOOGLE FONTS CODE-->
<link href='http://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Muli:400,400italic' rel='stylesheet' type='text/css'>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css"></style>
<!-- Enable this css if you want to override styles-->
<!--<link href="css/override.css" rel="stylesheet" media="screen">-->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--FAVICON & APPLE ICONS-->


<!--SCRIPTS-->
<script type="text/javascript" src="http://www.dezinethemes.com/envato/missing/1/2/js/modernizr-1.0.min.js"></script>
</head>
<body>
<div class="container">
  <div id="wrapper">
    <header class="clearfix">
      <div class="row">
        <div class="col-md-5 col-sm-5">
          <h3 class="brand"><a href="<?php echo $home_url; ?>" title="404"><i class="fa fa-leaf"></i>BRAND</a></h3>
        </div>
        
      </div>
      <!--end-of-row--> 
    </header>
    <article> 
      
      <!-- Tab panes -->
      <div class="tab-content-wrapper">
        <div class="tb-content active" id="home">
          <div class="box"> <span class="section-icon"><i class="fa fa-chain-broken fa-2x"></i></span>
            <h1>404</h1>
            <h4>Sorry - Page Not Found!</h4>
            <p>The page you are looking for was moved, removed, renamed or might never existed. <br>
              You stumbled upon a broken link :(</p>
          </div>
          <form action="" method="post" id="search" class="form-dark">
            <div class="input-group">
              <input type="text" id="s" name="s" placeholder="Search...">
              <input type="submit" id="search-submit" name="search-submit" value="&#xf002;" >
            </div>
          </form>
        </div>
        <!--end-of-tab-content-->
        
        <div class="tb-content" id="subscribe">
          <div class="box"> <span class="section-icon"><i class="fa fa-plus-circle fa-2x"></i></span>
            <h4>Subscribe For Our E-Newsletter</h4>
            <p>Join our email list to receive updates, notifications, news, events and more...</p>
          </div>
          <form action="form/subscribe.php" method="post" id="subscribe-form" class="form-dark">
            <div class="input-group">
              <input type="email" id="emailSubscribe" name="emailSubscribe" placeholder="Enter your email address...">
              <input type="submit" id="subscribe-submit" name="subscribe-submit" value="&#xf058;" >
            </div>
          </form>
        </div>
        <!--end-of-tab-content-->
        
        <div class="tb-content" id="contact">
          <div class="box"> <span class="section-icon"><i class="fa fa-envelope fa-2x"></i></span>
            <h4>Contact</h4>
            <p>Call Us: <strong>333.555.1212</strong> OR <br />
              Send us a message if you have any questions.</p>
          </div>
          <form id="contact-form" method="post" action="form/contact.php" class="form-dark">
            <div class="field-wrapper">
              <div class="row">
                <div class="col-md-12 form-row">
                  <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" />
                </div>
                <div class="col-md-12 form-row">
                  <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" />
                </div>
              </div>
              <div class="form-row clearfix">
                <textarea cols="60" rows="5" id="comment" name="comment" class="form-control" placeholder="Please write us where you find broken link on our website..."></textarea>
              </div>
              <button type="submit" class="btn btn-custom btn-lg"  id="contact-submit" name="contact-submit"> <span class="glyphicon glyphicon-send"></span> Send </button>
            </div>
          </form>
        </div>
      </div>
      <!--end-of-tab-content--> 
      
    </article>
    <footer> 
      <!-- tabs -->
      <ul class="tabs clearfix">
        <li><a href="#home" data-toggle="tab" title="404"><i class="fa fa-chain-broken"></i> 404</a></li>
        <li><a href="#subscribe" data-toggle="tab" title="Subscribe"><i class="fa fa-plus-circle"></i> Subscribe</a></li>
        <li><a href="#contact" data-toggle="tab" title="Contact"><i class="fa fa-envelope"></i> Contact</a></li>
      </ul>
    </footer>
  </div>
  <!--end-of-wrapper--> 
</div>
<!--end-of-container--> 
<!--SCRIPTS--> 
<script src="http://www.dezinethemes.com/envato/missing/1/2/js/jquery.js"></script> 
<script src="http://www.dezinethemes.com/envato/missing/1/2/js/bootstrap.min.js"></script> 
<script src="http://www.dezinethemes.com/envato/missing/1/2/js/custom.js"></script> 
<script src="http://www.dezinethemes.com/envato/missing/1/2/js/missing.js"></script>
<script src="http://www.dezinethemes.com/envato/missing/1/2/js/placeholders.js"></script>
</body>
</html>
