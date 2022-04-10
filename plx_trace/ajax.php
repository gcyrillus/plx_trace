<?php
$arr_file_ext = ['gpx'];
$dir= $_GET['dir'];
$do =trim($_GET['do']);
 foreach($_FILES as $filename) {
	 $ext=pathinfo($filename['name']);
	 if(!in_array($ext['extension'],$arr_file_ext)) {
		echo "Fichier avec l'extension gpx requis";
		return;
	 }
}  
if (!file_exists('gpx/'.$dir)) {
    mkdir('gpx/'.$dir, 0777);
}
$filename = $_FILES['file']['name'];
$newfilename=$filename;

# rename file if requested
if ($do == "rename") {
	$name = pathinfo($filename, PATHINFO_FILENAME);
	$increment = ''; 
	while(file_exists('gpx/'.$dir.'/'.$name . $increment . '.' . $arr_file_ext[0])) {
		if ($increment=='') { $increment='-AAA';}
		else {$increment++;}
		$newfilename= $name . $increment . '.' . $arr_file_ext[0];
	}
}
if ($do == "ignore" && file_exists('gpx/'.$dir.'/'.$newfilename) ) {
	echo 'Ce fichier existe déjà';
	exit;
}
move_uploaded_file($_FILES['file']['tmp_name'], 'gpx/'.$dir.'/'.$newfilename);

echo  $newfilename ;
die;
?>