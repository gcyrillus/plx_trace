<?php if(!defined('PLX_ROOT')) exit; 
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
# Controle de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);
	if(isset($_GET['del']) && $_GET['del'] !='') {	
		deleteDir(PLX_PLUGINS.'plx_trace/gpx/'.trim($_GET['del']));		
	header('Location: parametres_plugin.php?p='.$plugin);
	exit;
	}		
    if(!empty($_POST)) {
		if (!file_exists(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']))) {
		mkdir(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']), 0777);
		}
		$plxPlugin->saveParams();// valide la configuration du plugin
		header('Location: parametres_plugin.php?p='.$plugin);
	exit;
    }
// efface un sous repertoire de gpx
function deleteDir($deldir) {
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
// on liste les fichiers par repertoire et on affiche leur zone de televersement et la listes des traces disponibles.
function getGpxDir() {
	$gpxDir = glob(PLX_PLUGINS.'plx_trace/gpx/*');
	$i='0';
	foreach($gpxDir as $item) {
		if(is_dir($item)) {
			$dir=basename($item);
			$i++;
			echo'
				<h3 class="fullWidth flex">Repertoire: <b>'.$dir.'</b> <a href="parametres_plugin.php?p=plx_trace&del='.$dir.'" style="margin-inline-start:auto;" onclick="return confirm(\'Cliquez OK pour effacer definitivement ce repertoire\');"> effacer ce repertoire entierement</a></h3>
				<div id="sect'.$i.'">
				<div class="drag_upload_file" ondrop="upload_file(event,\'file'.$i.'\',\'sect'.$i.'\',\''.$dir.'\')" ondragover="return false">
				<input type="hidden" value="'.basename($dir).'" name="dirfile'.$i.'"/>
				  <p>Deposer votre fichier  <i>gpx</i> ici ou  <input type="file" id="file'.$i.'" name="file'.$i.'[]"   multiple />
				  <input type="button" value="Select File" onclick="file_explorer(\'file'.$i.'\',\'sect'.$i.'\',\''.$dir.'\');" />
				  <br><label for="file'.$i.'"> cliquez ici.</label></p>
				</div>
			</div>'.PHP_EOL .
			'<div class="results">
				<select name="selectfile'.$i.'" data-code="code'.$i.'">
					<option value="">Choississez un fichier de parcours</option>'.PHP_EOL;
			getGpxFile($dir);
			echo'</select>
			<p> Code à copier dans l\'article</p>
			<textarea class="code'.$i.'"></textarea>
			</div>';
		}		
	}
}
function getGpxFile($dir) {
	$gpxFile= glob(PLX_PLUGINS.'plx_trace/gpx/'.$dir.'/*'); 
		foreach($gpxFile as $file) {
			echo '<option value="'.$file.'">'.basename($file).'</option>';
		}
	
}

?>


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
}
</style>
<form action="parametres_plugin.php?p=<?php echo $plugin ?>" method="post" class="HookMyTheme">
 <fieldset>
 <legend>gestion des traces</legend>

 <div id="drop_file_area" >
    <p class="fullWidth flex" style="gap:1em;"><label for="newDir"> Créer un nouveau repertoire</label><input name="newDir"/><?php 	echo plxToken::getTokenPostMethod();?>
<input type="submit" name="submit" value="Enregistrer"  style="margin-inline-start:auto;"/></p>
<?php getGpxDir(); ?>
</div>

  <script src="<?php echo PLX_PLUGINS.$plugin.'/script.js'; ?>"></script>

</fieldset>

</form>