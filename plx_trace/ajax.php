<?php
$arr_file_ext = ['gpx'];
$dir= $_GET['dir'];
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
/*
awaiting feedbacks, to choose: do not overwrite, rename or overwrite file or all 3 config options ?*//*
$name = pathinfo($filename, PATHINFO_FILENAME);
$increment = ''; 
while(file_exists($name . $increment . '.' . $arr_file_ext)) {
    $increment++;
}
*/
// if(file_exists('gpx/'.$dir.'/'.$filename)) { $filename=" Un fichier du même nom existe déja.";}
// else {
move_uploaded_file($_FILES['file']['tmp_name'], 'gpx/'.$dir.'/'.$filename);
// }
echo $filename ;
die;
?>
