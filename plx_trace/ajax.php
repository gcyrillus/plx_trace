<?php
# Définition des constantes 
$gu_sub = explode('plugins',$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
$gu_sub = str_replace($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR,'',$gu_sub[0]);
$plugName = __DIR__ ;
define('PLX_ROOT',$gu_sub); 
define('PLX_CORE', PLX_ROOT.'core'.DIRECTORY_SEPARATOR);
define('PLX_PLUGINS', PLX_ROOT.'plugins'.DIRECTORY_SEPARATOR);

include(PLX_ROOT.'config.php');
include(PLX_CORE.'lib'.DIRECTORY_SEPARATOR.'config.php');

# On verifie que PluXml est installé
if(!file_exists(path('XMLFILE_PARAMETERS'))) {
	header('Location: '.PLX_ROOT.'install.php');
	exit;
}
# On continue et on démarre la session
session_start();

# On inclut les class interdépendantes de pluxml
include_once(PLX_CORE.'lib/class.plx.date.php');
include_once(PLX_CORE.'lib/class.plx.glob.php');
include_once(PLX_CORE.'lib/class.plx.utils.php');
include_once(PLX_CORE.'lib/class.plx.msg.php');
include_once(PLX_CORE.'lib/class.plx.record.php');
include_once(PLX_CORE.'lib/class.plx.motor.php');
include_once(PLX_CORE.'lib/class.plx.admin.php');
include_once(PLX_CORE.'lib/class.plx.encrypt.php');
include_once(PLX_CORE.'lib/class.plx.medias.php');
include_once(PLX_CORE.'lib/class.plx.plugins.php');
include_once(PLX_CORE.'lib/class.plx.token.php');
include_once(PLX_CORE.'lib/class.plx.capcha.php');
include_once(PLX_CORE.'lib/class.plx.erreur.php');
include_once(PLX_CORE.'lib/class.plx.feed.php');
include_once(PLX_CORE.'lib/class.plx.show.php');

# Creation de l'objet principal et lancement du traitement
$plxMotor = plxMotor::getInstance();
// on s'occupe de notre plugin
$plxMotor->plxPlugins->plug = array(
			'dir' 			=> PLX_PLUGINS,
			'name' 			=> $plugName,
			'filename'		=> PLX_PLUGINS.$plugName.'/'.$plugName.'.php',
			'parameters.xml'=> PLX_ROOT.PLX_CONFIG_PATH.'plugins/'.$plugName.'.xml',
			'infos.xml'		=> PLX_PLUGINS.$plugName.'/infos.xml'
		);
// on declare le plugin concerné
$plxPlugin = $plxMotor->plxPlugins->aPlugins['plx_trace'];
// on verifie que notre plugin est bien là 
if (!isset($plxMotor->plxPlugins->aPlugins['plx_trace'])) exit;
// enfin si tout va bien jusqu'ici, notre plugin peut-etre multilingue
if(isset($_GET['dir'])) {
$arr_file_ext = ['gpx'];
$dir= $_GET['dir'];
$do =trim($_GET['do']);
 foreach($_FILES as $filename) {
	 $ext=pathinfo($filename['name']);
	 if(!in_array($ext['extension'],$arr_file_ext)) {
		$plxPlugin->lang('L_GPX_REQUIRED');
		exit;
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
	echo $plxPlugin->getLang('L_FILE_EXISTS');
	exit;
}
move_uploaded_file($_FILES['file']['tmp_name'], 'gpx/'.$dir.'/'.$newfilename);

echo  $newfilename ;
die;
}
?>