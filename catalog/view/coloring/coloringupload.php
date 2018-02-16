<?php
if (!is_dir('texture')) {
    mkdir('texture', 0777);
}

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {

	$width = getimagesize($_FILES['imges']['tmp_name']);
	if($width[0] > 0){
		$uploadfile = 'texture/'.$_FILES['imges']['name'];
		move_uploaded_file($_FILES['imges']['tmp_name'], $uploadfile);
		print($uploadfile);
	}

}

?>