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
	    
		#Intitulé lien dans le menu admin
		$this->setAdminMenu( ' TRACE GPX'  , 20,  ' GESTION TRACE GPX');
		
		$this->jsTpl = '<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
<script src="plugins/'.__CLASS__.'/jsTpl.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
<!--
  Copyright (C) 2011-2012 Pavel Shramov
  Copyright (C) 2013 Maxime Petazzoni <maxime.petazzoni@bulix.org>
  All Rights Reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions are met:

  - Redistributions of source code must retain the above copyright notice,
    this list of conditions and the following disclaimer.

  - Redistributions in binary form must reproduce the above copyright notice,
    this list of conditions and the following disclaimer in the documentation
    and/or other materials provided with the distribution.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
  ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
  LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
  CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
  POSSIBILITY OF SUCH DAMAGE.
-->';

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
				if ( trim(strlen(dirname($_SERVER['SCRIPT_NAME'])) >1)) {
					if($plxMotor->aConf['urlrewriting'] =='0') {
						$localPrefX = ' ./';
					}
					else {
						$localPrefX = '../';
					}
					if( $plxMotor->mode != 'article' ){
						$localPrefX = '';
					}
				} 
				else {
					$localPrefX = '../../';
				}
				$prefX ='<script>let prefX =\''.$localPrefX.'\'; 	</script>';
				$output = str_replace('<?php echo $prependToTag; ?>', $prefX.'<?php echo $this->jsTpl ?>' , $output);
				ob_get_clean();
		 ?>
		  <?php	
		}
}
?>
