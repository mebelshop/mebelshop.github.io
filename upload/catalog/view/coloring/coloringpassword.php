<?php

echo "<script> if (typeof jQuery == 'undefined') {document.write(unescape(\"%3Cscript src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'%3E%3C/script%3E\"));}</script>";

function request_url()
{
  $result = '';
  $default_port = 80;

  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {

    $result .= 'https://';

    $default_port = 443;
  } else {

    $result .= 'http://';
  }

  $result .= $_SERVER['SERVER_NAME'];

  if ($_SERVER['SERVER_PORT'] != $default_port) {

    $result .= ':'.$_SERVER['SERVER_PORT'];
  }

  $result .= $_SERVER['SCRIPT_NAME'];
  
  return $result;
}

$url = request_url();

$url_new = str_replace("index.php", "", $url);

if ((isset($_GET["cola"])) || (isset($_COOKIE["cdf2ofl01a"]))) {
	
	include "coloringpass.php";
	
	$passm = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . date('z') . $pass);

	if ((isset($_GET["cola"]) && $_GET["cola"] == $pass) || ($_COOKIE["cdf2ofl01a"] == $passm)) {

		$passm = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . date('z') . $pass);
		
		echo "<script>if (typeof jQuery == 'function' && ('ui' in jQuery) && jQuery.ui && ('version' in jQuery.ui)) {} else {document.write(unescape(\"%3Clink href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' rel='stylesheet' type='text/css'%3E\"));  document.write(unescape(\"%3Cscript src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js' type='text/javascript'%3E%3C/script%3E\"));  }</script>";
		echo '<script src="' . $url_new . 'catalog/view/javascript/color/jscolor/jscolor.js" type="text/javascript"></script>';
		echo '<script src="' . $url_new . 'catalog/view/javascript/coloringcss.js" type="text/javascript"></script>';
		echo '<script type="text/javascript">var lampam = "'. $passm .'";</script>';
		echo '<link href="' . $url_new . 'catalog/view/coloring/style/penalcss.css" rel="stylesheet" type="text/css"/>';
	}	
}

echo '<link href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|Bad+Script|Ubuntu:400,700,400italic,700italic|Ruslan+Display|Marck+Script|Lobster|Russo+One|Open+Sans:400italic,700italic,400,700|Roboto:400,400italic,700,700italic|Play:400,700|Comfortaa:400,700&subset=latin,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css">';
echo '<link href="' . $url_new . 'catalog/view/coloring/style/coloringcss.css?t='.microtime(true).'" rel="stylesheet" type="text/css"/>';
echo '<script type="text/javascript" src="' . $url_new . 'catalog/view/coloring/style/coloringcss.js?t='.microtime(true).'"></script>';

?>