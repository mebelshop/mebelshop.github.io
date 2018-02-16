<?php
header('Content-type: text/html; charset=UTF-8');
if(isset($_POST["arrDataCss"]) && is_array($_POST["arrDataCss"])) {
	$arr_css = $_POST["arrDataCss"];
	$current = "";
	foreach ($arr_css as $value) {
		$current .= htmlspecialchars_decode(stripslashes("$value\n"));
	}
	$file = "coloringcss.css";
	file_put_contents($file, $current);
}
if(isset($_POST["arrNewEl"]) && is_array($_POST["arrNewEl"])) {
	$arr_css = $_POST["arrNewEl"];
	$current = "jQuery(function() {\n";
	foreach ($arr_css as $value) {
		$value1 = htmlspecialchars_decode(stripslashes($value));
		$value2 = explode("::::", $value1);
		if($value2[0] != ""){
			$value3 = "jQuery('$value2[0]').append('<div class=\"$value2[1]\">$value2[2]</div>');";
			$current .= "$value3\n";
		}
	}
	$current .= "});";
	$filejs = "coloringcss.js";
	file_put_contents($filejs, $current);
}
?>