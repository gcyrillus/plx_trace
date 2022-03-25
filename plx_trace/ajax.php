<?php

$arr_file_ext = ['gpx'];
$dir= $_GET['dir'];
 foreach($_FILES as $filename) {
	 $ext=pathinfo($filename['name']);
	 if(!in_array($ext['extension'],$arr_file_ext)) {
		echo " Fichier avec l'extension gpx requis";
		return;
	 }
}
  
if (!file_exists('$dir')) {
    mkdir('$dir', 0777);
}
  
$filename = $_FILES['file']['name'];
  
move_uploaded_file($_FILES['file']['tmp_name'], 'gpx/'.$dir.'/'.$filename);
  
echo $filename;
die;
?>