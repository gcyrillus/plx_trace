<?php if(!defined('PLX_ROOT')) exit;
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
# Controle de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

	if(isset($_GET['del']) && $_GET['del'] !='') {	
	$removeDir= basename($_GET['del']);
		deleteDir(PLX_PLUGINS.'plx_trace/gpx/'.trim($removeDir));		
	header('Location: plugin.php?p='.$plugin);
	exit;
	}		
    if(!empty($_POST)) {
		if (!file_exists(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']))) {
			$newDir = basename($_POST['newDir']);
		mkdir(PLX_PLUGINS.'plx_trace/gpx/'.trim($newDir), 0777);		
       file_put_contents(PLX_PLUGINS.'plx_trace/gpx/'.trim($newDir).'/index.html', '');
		}
		$plxPlugin->saveParams();// valide la configuration du plugin
		header('Location: plugin.php?p='.$plugin);
	exit;
    }
// efface un sous repertoire de gpx
function deleteDir($deldir) {
	
	// on verifie que l'on est bien dans un sous repertoire de gpx
	if(substr(0,strlen(PLX_PLUGINS.'plx_trace/gpx/'))== PLX_PLUGINS.'plx_trace/gpx/') {
		if (file_exists($deldir)) {
			$dir = opendir($deldir);
			while (false !== ($file = readdir($dir))) {
				if (($file != '.') && ($file != '..')) {
					$full = $deldir . '/' . $file;
					if (is_dir($full)) {
						deleteDir($full);
					} else {
						unlink($full);
					}
				}
			}
			@closedir($deldir);
			if(rmdir($deldir)) {
				return plxMsg::Info(L_DELETE_SUCCESSFUL);
			}
		}
	}
	else {return plxMsg::Info(L_DELETE_FOLDER.' '.$deldir.' - ' .L_NO_ENTRY);}
}			
// on liste les fichiers par repertoire et on affiche leur zone de televersement et la listes des traces disponibles.
function getGpxDir() {
	global $plxPlugin;
	$gpxDir = glob(PLX_PLUGINS.'plx_trace/gpx/*');
	$i='0';
	foreach($gpxDir as $item) {
		if(is_dir($item)) {
			$dir=basename($item);
			$i++;
			echo'
				<h3 class="fullWidth flex">'.$plxPlugin->getLang('L_FOLDER').': <b>'.$dir.'</b> <a href="plugin.php?p=plx_trace&del='.$dir.'" style="margin-inline-start:auto;" onclick="return confirm(\''.$plxPlugin->getLang('L_CONFIRM_DELETE').'\');">'.$plxPlugin->getLang('L_DELETE_FOLDER').'</a></h3>
				<div id="sect'.$i.'">
					<div class="drag_upload_file" ondrop="upload_file(event,\'file'.$i.'\',\'sect'.$i.'\',\''.$dir.'\', upAction )" ondragover="return false">
					<input type="hidden" value="'.basename($dir).'" name="dirfile'.$i.'"/>
					  <p>'.$plxPlugin->getLang('L_DROP_GPX_FILE_OR').' <input type="file" id="file'.$i.'" name="file'.$i.'[]"   multiple />
					  <input type="button" value="Select File" onclick="file_explorer(\'file'.$i.'\',\'sect'.$i.'\',\''.$dir.'\');" />
					  <br><label for="file'.$i.'">'.$plxPlugin->getLang('L_CLICK_HERE').'</label></p>
					</div>
			</div>'.PHP_EOL .
			'<div class="results">
				<select name="selectfile'.$i.'" data-code="code'.$i.'">
					<option value="">'.$plxPlugin->getLang('L_CHOOSE_GPX_TRACE').'</option>'.PHP_EOL;
			getGpxFile($dir);
			echo'</select>
			<p>'.$plxPlugin->getLang('L_CODE_TO_PASTE').'</p>
			<textarea class="code'.$i.'"></textarea>
			</div>
			<div class="fullWidth" id="code'.$i.'"></div>';
		}		
	}
}
function getGpxFile($dir) {
	$gpxFile= glob(PLX_PLUGINS.'plx_trace/gpx/'.$dir.'/*.gpx'); 
		foreach($gpxFile as $file) {
			echo '<option value="'.$file.'">'.basename($file).'</option>';
		}	
}

// INI Lang javascript
?><script>
const defLang			='<?php echo $plxAdmin->aConf['default_lang']; ?>';
const file_exists 		='<?php $plxPlugin->lang('L_FILE_EXISTS') ?>';
const fileExt_required 	='<?php $plxPlugin->lang('L_GPX_REQUIRED') ?>';
const fileError 		='<?php $plxPlugin->lang('L_FILE_ERROR') ?>';
const fileUploadError	='<?php $plxPlugin->lang('L_FILE_UPLOAD_ERROR') ?>';
const loading 			='<?php $plxPlugin->lang('L_LOADING') ?>'; 
const distance			='<?php $plxPlugin->lang('L_DISTANCE') ?>'; 						 
const duration 			='<?php $plxPlugin->lang('L_DURATION') ?>'; 						 
const time 				='<?php $plxPlugin->lang('L_TIME') ?>'; 						 
const avghr 			='<?php $plxPlugin->lang('L_AVERAGE_HEART_RATE') ?>'; 				 
const cadence 			='<?php $plxPlugin->lang('L_CADENCE') ?>'; 						 
const postilt 			='<?php $plxPlugin->lang('L_POSITIVE_TILT') ?>'; 					 
const negtilt 			='<?php $plxPlugin->lang('L_NEGATIVE_TILT') ?>'; 					 
const avgtilt 			='<?php $plxPlugin->lang('L_AVERAGE_TILT') ?>'; 					 
</script>

<style>
#drop_file_area {
	border-bottom:solid;
	padding-bottom:1em;
	margin-bottom:1em;
}
#drop_file_area .drag_upload_file{
    background-color: #EEE;
    border: #999 3px dashed;
    padding: 0.5em; 
    margin:1em;
  height:10em;
}
.results {
  display:flex;
  flex-direction:column;
}
.results textarea {
  width:100%;
  flex-grow:1;
}
.drag_upload_file {
  text-align: center;
}
#drag_upload_file p {
  text-align: center;
  display: inline-block;
  margin: 0 1em;
}
.drag_upload_file input {
  float: right;
  margin: 0.25em 1.5em;
}
#drag_upload_file #selectfile {
  display: none;
}
b.green {
  color:green;
  display: inline-block;
  aspect-ratio: 1/1;
  padding: 0.25em 0.5em;
  background: lightgreen;
  border-radius: 50%;
  box-sizing: border-box;
  box-shadow: 1px 1px 1px;
}
#drop_file_area [type="file"] {
  display:none;
}

#drop_file_area {
  display: grid;
  grid-template-columns: repeat(auto-fill,minmax(300px,1fr));
max-width:clamp(300px, 100%,880px);
  margin:auto;
}
.fullWidth {
	min-width:80%;
	grid-column:1/-1;
}
h3.fullWidth {
	border-top:solid;
	padding-top:1em;
	margin-top:1em;
}

legend {
  font-size:1.5em;
  text-transform:uppercase;
  color:hotpink;
  font-weight:bold;
  text-indent:15vw;
}
.flex {
  display:flex;
  flex-wrap:wrap;
  gap:inherit;
}
.gap1{
	gap:1rem;
}
.mw100 {
	min-width:95%;
}
.space-around {
	justify-content:space-around;
}
p.fullWidth.flex.gap1.space-around {
  background: #bee;
  margin: 0;
  padding: 0.5em;
  border-radius: 5px;
}
.mw100 {
  text-align: center;
}
.mw100 b {
  padding: 0 3em;
  color: orangered;
}
.gpxmap {
  width: 100%;
  aspect-ratio: 16/10;
}
object:not([data]) {
    display: none;
}
p:last-of-type.fullWidth.flex.gap1.space-around {
  background: darkseagreen;
  margin-top: 0.5em;
}
</style>
<form action="plugin.php?p=<?php echo $plugin ?>" method="post" class="HookMyTheme">
 <fieldset>
 <legend>gestion des traces</legend>

 <div id="drop_file_area" >
    <p class="fullWidth flex " style="gap:1em;"><label for="newDir"><?php $plxPlugin->lang('L_CREATE_FOLDER') ?> </label><input name="newDir"/><?php 	echo plxToken::getTokenPostMethod();?>
<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>"  style="margin-inline-start:auto;"/></p>
<p class="fullWidth flex gap1 space-around"><span class="mw100"><?php $plxPlugin->lang('L_IF_FILE_EXISTS_DO') ?></span>
<span class="flex"><label for="crunch"><?php $plxPlugin->lang('L_CRUNCH') ?></label><input type="radio" name="action" id="crunch" value="crunch" /></span>
<span class="flex"><label for="rename"><?php $plxPlugin->lang('L_RENAME') ?></label><input type="radio" name="action" id="crunch" value="rename"/></span>
<span class="flex"><label for="ignore"><?php $plxPlugin->lang('L_WARN') ?></label><input type="radio" name="action" id="crunch" value="ignore" checked/></span>
</p>
<p class="fullWidth flex gap1 space-around"><label for="preview" ><?php $plxPlugin->lang('L_PREVIEW_GPX') ?></label><input type="checkbox" name="preview"/></p>
<?php getGpxDir(); ?>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
  <script src="<?php echo PLX_PLUGINS.$plugin.'/jsTpl.js'; ?>"></script>
  <script src="<?php echo PLX_PLUGINS.$plugin.'/script.js'; ?>"></script>

</fieldset>

</form>