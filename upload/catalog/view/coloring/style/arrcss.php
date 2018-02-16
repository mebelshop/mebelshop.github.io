<?php
header('Content-type: text/html; charset=UTF-8');
if(isset($_POST)) {
	$coloringcss = file_get_contents('coloringcss.css');
	print($coloringcss);
}
?>