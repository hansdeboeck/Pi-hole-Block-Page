<?php

// Retrieve essential user config options
$selfDomain = "dns.hacemedia.com"
$landPage   = "landing.php";
$blankGif   = "true";
$blockImage = "https://assets.xcdn.eu/extensions/pihole/afbeelding.svg";
$serverAddr = "https://assets.xcdn.eu/extensions/pihole/"
// Handle configured variables
if (empty($blankGif)) $blankGif = (in_array($usrIni["blankGif"], array('true','TRUE','yes','YES','1')) ? true : false);

// Config error checking
if ($selfDomain && !is_valid_domain_name($selfDomain)) die("[ERROR]: The configured selfDomain '$selfDomain' does not appear to be valid. Please correct this within file");
if ($landPage && !is_file($landPage)) die("[ERROR]: The configured landpage '$landPage' does not exist. Please create this file locally, or correct this within in file");

// Sanitise FQDN input
$domainName = filter_var($_SERVER["SERVER_NAME"], FILTER_SANITIZE_SPECIAL_CHARS);

// Define which URI extensions get rendered as 'Website Blocked' (Including empty for index.ext)
$webRender = array("asp", "htm", "html", "php", "rss", "xml", "");

// Retrieve serverName URI extension (e.g: jpg, exe, php)
$uriExt = pathinfo($_SERVER["REQUEST_URI"], PATHINFO_EXTENSION);

// Load Lighttpd config for use with set_xpihole_header() function
$lighttpdConf = (is_file("/etc/lighttpd/lighttpd.conf") ? file("/etc/lighttpd/lighttpd.conf") : false);

// Handle block page redirects
if ($domainName == "pi.hole") {
  // Redirect user to Pi-hole Admin Console
  header("Location: /admin");
  exit();
} elseif ($domainName == $serverAddr && $landPage || $domainName == $selfDomain && $landPage) {
  // Redirect IP addr, or configured selfDomain to custom landing page
  include $landPage;
  exit();
} elseif (substr_count($_SERVER["REQUEST_URI"], "?") && isset($_SERVER["HTTP_REFERER"]) && $blankGif) {
  // Assume that REQUEST_URI with query string and HTTP_REFERRER is PHBP being called from an iframe
  // Serve a 1x1 blank gif
  set_xpihole_header();
  die("<img src='data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs='>");
} elseif (!in_array($uriExt, $webRender) || substr_count($_SERVER["REQUEST_URI"], "?")) {
  // Non HTML renderable URL extension or non-iframed query string
  // Serve 'Mobile Friendly' block image
  set_xpihole_header();
  $blockHtml = ($blockImage !== true ? '<a href="/"><img src="$blockImage"/></a>' : '<a href="/"><svg xmlns="http://www.w3.org/2000/svg" width="180" height="16"><defs><style>a {text-decoration: none;} circle {stroke: rgba(152,2,2,0.5); fill: none; stroke-width: 2;} rect {fill: rgba(152,2,2,0.5);} text {opacity: 0.3; font: 11px Arial;}</style></defs><circle cx="8" cy="8" r="7"/><rect x="10.3" y="-6" width="2" height="12" transform="rotate(45)"/><text x="19.3" y="12">Blocked by Hacemedia</text></svg></a>');
  die("<head>$viewPort</head>$blockHtml");
}

// Now to render the block page
set_xpihole_header();


// Check setupVars for WEBPASSWORD
if (!is_file("/etc/pihole/setupVars.conf")) die("[ERROR]: Unable to retrieve file: /etc/pihole/setupVars.conf");
$setupVars = file("/etc/pihole/setupVars.conf", FILE_IGNORE_NEW_LINES);
$webPassword = (preg_replace("/(.*=)/", "", array_stripos("WEBPASSWORD", $setupVars)) ? true : false);
$setupVars = null;

?>

<!doctype html><html lang="en"><head><meta charset="utf-8"><title>This page is banned for you.</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link href="https://assets.xcdn.eu/themes/stack/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/stack-interface.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/theme.css" rel="stylesheet" type="text/css" media="all" /><link href="https://assets.xcdn.eu/themes/stack/css/custom.css" rel="stylesheet" type="text/css" media="all" /><link href="https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700" rel="stylesheet"><script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/9372C06A-E163-2F4F-BCC7-1A29415EB05E/main.js" charset="UTF-8"></script></head><body data-smooth-scroll-offset="77"><div class="nav-container"></div><div class="main-container"><section class="imageblock switchable height-100"><div class="imageblock__content col-lg-6 col-md-4 pos-right"><div class="background-image-holder"><img alt="image" src="https://assets.xcdn.eu/images/vans.jpg"></div></div><div class="container pos-vertical-center"><div class="row"><div class="col-lg-5 col-md-7"><h1>This domain is banned for you.</h1><p class="lead">This page has been blocked. If this is, in your opinion, a mistake contact support@hacemedia.be.</p></div></div></div></section></div><script src="https://assets.xcdn.eu/themes/stack/js/jquery-3.1.1.min.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/parallax.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/smooth-scroll.min.js"></script><script src="https://assets.xcdn.eu/themes/stack/js/scripts.js"></script></body></html>
<?php
// Confirm that a configured domain name appears to be valid (Will not authenticate internationalised domain names)

// Installations such as DietPi remove the lighttpd.conf configuration provided by Pi-hole
// Provide a response header for debugging using curl -I, if not already configured to do so
function set_xpihole_header() {
  global $lighttpdConf;
  if (!preg_match('/^\s+setenv.add-response-header/', $lighttpdConf[61])) {
    header("X-Pi-hole: A black hole for Internet advertisements.");
    $lighttpdConf = null;
	}
}
?>
