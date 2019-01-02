<?php


// Retrieve serverName URI extension (e.g: jpg, exe, php)
$uriExt = pathinfo($_SERVER["REQUEST_URI"]);
$Ext = $uriExt['extension'];




// Handle block page redirects
if ($domainName == "pi.hole") {
  // Redirect user to Pi-hole Admin Console
  header("Location: /admin");
  exit();
}
if ($Ext == "png"){
  header ('Content-Type: image/png');
  readfile("https://assets.xcdn.eu/extensions/pihole/afbeelding.png");
}


?>

<!doctype html><html lang="en"><head><meta charset="utf-8"><title>This page is banned for you.</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link href="https://assets.xcdn.eu/themes/stack/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/stack-interface.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/theme.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/custom.css" rel="stylesheet" type="text/css" media="all" /><link href="https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700" rel="stylesheet"></head><body data-smooth-scroll-offset="77"><div class="nav-container"></div><div class="main-container"><section class="imageblock switchable height-100"><div class="imageblock__content col-lg-6 col-md-4 pos-right"><div class="background-image-holder"><img alt="image" src="https://assets.xcdn.eu/images/vans.jpg"></div></div><div class="container pos-vertical-center"><div class="row"><div class="col-lg-5 col-md-7"><h1>This domain is banned for you.</h1><p class="lead">This page has been blocked. If this is, in your opinion, a mistake contact support@hacemedia.be.</p></div></div></div></section></div><script src="https://assets.xcdn.eu/themes/stack/js/jquery-3.1.1.min.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/parallax.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/smooth-scroll.min.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/scripts.js"></script></body></html>