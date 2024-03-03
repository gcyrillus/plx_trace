<?php
  class plx_trace extends plxPlugin {	 

	const HOOKS = array(
			'ThemeEndHead',
        );  
        const BEGIN_CODE = '<?php' . PHP_EOL;
        const END_CODE = PHP_EOL . '?>';
		
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
		
		


 
	#fonctions des hooks
		
    }
	
		public function OnActivate() {					
			$gpxDir = glob(PLX_PLUGINS.__CLASS__.'/gpx/*');			
			foreach($gpxDir as $gpxFolder) {
				if (!file_exists($gpxFolder.'/index.html')) {					
					file_put_contents($gpxFolder.'/index.html', '');
				}			
			}	
		}


		#recherche de la balise <plx_trace/> et injecte le script
		public function ThemeEndHead() {
		$jsTpl = '			<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
			<script>
				const defLang			="'.$this->default_lang .'";
				const file_exists 		="'. $this->getLang('L_FILE_EXISTS') .'";
				const fileExt_required  ="'. $this->getLang('L_GPX_REQUIRED') .'";
				const fileError 		="'. $this->getLang('L_FILE_ERROR') .'";
				const fileUploadError	="'. $this->getLang('L_FILE_UPLOAD_ERROR') .'";
				const loading 			="'. $this->getLang('L_LOADING') .'"; 
				const distance			="'. $this->getLang('L_DISTANCE') .'"; 						 
				const duration 			="'. $this->getLang('L_DURATION') .'"; 						 
				const time 				="'. $this->getLang('L_TIME') .'"; 						 
				const avghr 			="'. $this->getLang('L_AVERAGE_HEART_RATE') .'"; 				 
				const cadence 			="'. $this->getLang('L_CADENCE') .'"; 						 
				const postilt 			="'. $this->getLang('L_POSITIVE_TILT') .'"; 					 
				const negtilt 			="'. $this->getLang('L_NEGATIVE_TILT') .'"; 					 
				const avgtilt 			="'. $this->getLang('L_AVERAGE_TILT') .'"; 					 
			</script>
			<script src="plugins/'.__CLASS__.'/jsTpl.js" defer></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
			<style>	.gpx {border: 2px rgba(125, 125, 125) solid;border-radius: 5px;box-shadow: 0 0 3px 3px rgba(200, 200, 200);max-width: 100%;margin: auto;}
					.gpx header {padding: 0.5em;}
					.gpx h3 {margin: 0;padding: 0;font-weight: bold;}
					.gpx .start {font-size: smaller;color: gray;}
					.gpx .map {border: 1px rgba(105, 105, 105) solid;border-left: none;border-right: none;max-width: 100%;height: 400px;max-height: 70vh;margin: 0;}
					.gpx footer {background: ivory;padding: 0.5em;}
					.gpx ul.info {list-style: none;margin: 0;padding: 0;font-size: smaller;text-align: center}
					.gpx ul.info li {color: rgba(150, 150, 150);padding: 2px;display: inline;}
					.gpx ul.info li span {color: black;}
			</style>
			<!--
			  Copyright (C) 2011-2012 Pavel Shramov
			  Copyright (C) 2013 Maxime Petazzoni <maxime.petazzoni@bulix.org>
			  Copyright (C) 2022 Griboval Cyrille https://github.com/gcyrillus/plx_trace/ (minors modifications about JS/CSS/HTML to turn it into a ©PluXml Plugin)
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


		global $plxMotor;	
			if ( trim(strlen(dirname($_SERVER['SCRIPT_NAME'])) >1)) {
				if($plxMotor->aConf['urlrewriting'] ==='0') {
					$localPrefX = ' ./';
				}
				else {
					$localPrefX = '../';
				}
				if( $plxMotor->mode === 'home' ){
					$localPrefX = './';
				}
			} 
			else {
				$localPrefX = '../../';
			}
			$prefX = '<script>const prefX ="'.$localPrefX.'";</script>';

				echo $prefX.PHP_EOL.$jsTpl;

		}
}
