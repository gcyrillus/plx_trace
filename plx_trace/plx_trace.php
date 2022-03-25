<?php
  class plx_trace extends plxPlugin {	 

	const HOOKS = array(
			'IndexEnd',
        );  
		
    public function __construct($default_lang) {
	

		# appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# Ajoute des hooks
            foreach(self::HOOKS as $hook) {
                $this->addHook($hook, $hook);
            }	
		
		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);	
		
		# limite l'accès à l'écran d'administration du plugin
        $this->setAdminProfil(PROFIL_ADMIN);	

		
		$this->jsTpl = '<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
<script src='.PLX_PLUGINS.__CLASS__.'/jsTpl.js></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />';

    }

	function str_replace_limit($haystack, $needle, $replace, $limit, $start_pos = 0) {
		if ($limit <= 0) {
				return $haystack;
			} else {
				$pos = strpos($haystack,$needle,$start_pos);
				if ($pos !== false) {
					$newstring = substr_replace($haystack, $replace, $pos, strlen($needle));
					return str_replace_limit($newstring, $needle, $replace, $limit-1, $pos+strlen($replace));
				} else {
				return $haystack;
				}
			}
		}
 
	#fonctions des hooks
		

		
		#recherche de la balise <plx_trace/> et injecte le script
		public function IndexEnd() {
			$prependToTag ='<plx_trace/>';
			echo '<?php ';?>
				ob_start();
				$output = str_replace('<?php echo $prependToTag; ?>', '<?php echo $this->jsTpl ?>' , $output);
				ob_get_clean();
		 ?>
		  <?php	
		}
}
?>