<?php if(!defined('PLX_ROOT')) exit;
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
# Controle de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);
	if(isset($_GET['del']) && $_GET['del'] !='') {	
		deleteDir(PLX_PLUGINS.'plx_trace/gpx/'.trim($_GET['del']));		
	header('Location: plugin.php?p='.$plugin);
	exit;
	}		
    if(!empty($_POST)) {
		if (!file_exists(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']))) {
		mkdir(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']), 0777);		
		$htaxces = 'Header add Access-Control-Allow-Origin "*"';
       file_put_contents(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']).'/.htaccess', $htaxces);
       file_put_contents(PLX_PLUGINS.'plx_trace/gpx/'.trim($_POST['newDir']).'/index.html', '');
		}
		$plxPlugin->saveParams();// valide la configuration du plugin
		header('Location: plugin.php?p='.$plugin);
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
				<h3 class="fullWidth flex">Repertoire: <b>'.$dir.'</b> <a href="plugin.php?p=plx_trace&del='.$dir.'" style="margin-inline-start:auto;" onclick="return confirm(\'Cliquez OK pour effacer definitivement ce repertoire\');"> effacer ce repertoire entierement</a></h3>
				<div id="sect'.$i.'">
					<div class="drag_upload_file" ondrop="upload_file(event,\'file'.$i.'\',\'sect'.$i.'\',\''.$dir.'\', upAction )" ondragover="return false">
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
			</div>
			<div class="fullWidth" id="code'.$i.'"><object class="gpxmap"></object></div>';
		}		
	}
}
function getGpxFile($dir) {
	$gpxFile= glob(PLX_PLUGINS.'plx_trace/gpx/'.$dir.'/*.gpx'); 
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
    <p class="fullWidth flex " style="gap:1em;"><label for="newDir"> Créer un nouveau repertoire</label><input name="newDir"/><?php 	echo plxToken::getTokenPostMethod();?>
<input type="submit" name="submit" value="Enregistrer"  style="margin-inline-start:auto;"/></p>
<p class="fullWidth flex gap1 space-around"><span class="mw100"><b>Televersement fichier gpx</b> Action à effectuer si le nom de fichier existe :</span>
<span class="flex"><label for="crunch">Remplacer</label><input type="radio" name="action" id="crunch" value="crunch" /></span>
<span class="flex"><label for="rename">Renommer</label><input type="radio" name="action" id="crunch" value="rename"/></span>
<span class="flex"><label for="ignore">Signaler</label><input type="radio" name="action" id="crunch" value="ignore" checked/></span>
</p>
<p class="fullWidth flex gap1 space-around"><label for="preview" >Afficher un preview du fichier gpx selectionner</label><input type="checkbox" name="preview"/></p>
<?php getGpxDir(); ?>
</div>

  <script src="<?php echo PLX_PLUGINS.$plugin.'/script.js'; ?>"></script>

</fieldset>

</form>
