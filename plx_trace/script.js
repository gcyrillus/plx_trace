let fileobj;
let index=0;
let upAction ='ignore';
let root=location.protocol + '//' + location.host;//+ location.pathname+'../../';
let subpath = location.pathname;
let uppath= subpath.replace('plugin.php', '') +'../..';
root = root + uppath;

for (let radioAction of document.querySelectorAll('input[name="action"]')) {
	  radioAction.addEventListener("change", function() {
      upAction = radioAction.value;
		});   
	}
function upload_file(e,ipt,section,dir,upAction) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj,ipt,section,dir,upAction);
}  
function file_explorer(ipt,section,dir,upAction) {
    document.getElementById(ipt).click();
    document.getElementById(ipt).onchange = function() {
        fileobj = document.getElementById(ipt).files[0];
        ajax_file_upload(fileobj,ipt,section,dir,upAction);
    };
}  
function ajax_file_upload(file_obj,ipt,section,dir,upAction) {
    let container= document.getElementById(section);    
    index++;
    let name= 'infos'+index;
    let classInfo='.'+name;
    let infos = document.createElement("p");
    infos.setAttribute('class',name);
    container.appendChild(infos);
    if(file_obj != undefined) {
        let form_data = new FormData();                  
        form_data.append('file', file_obj);
        let xhttp = new XMLHttpRequest();
        let upUrl= '../../plugins/plx_trace/ajax.php?dir='+dir+'&do='+upAction;
        xhttp.open("POST", upUrl , true);
        xhttp.onload = function(event) {
            output = document.querySelector(classInfo);
            if (xhttp.status == 200) {
                let msg='';
                let msgwarning='';
                
                if(this.responseText.trim() =='Ce fichier existe déjà') {
                    msg= ' <b class="green" style="color:tomato;background:pink; padding:0 0.5em" > ! </b>' ;
                    msgwarning='  color:tomato;text-align:center;font-weight:bold; ';
                } 
                else if(this.responseText.trim() !='Fichier avec l\'extension gpx requis' || this.responseText.trim() !='Ce fichier existes déjà') { 
                    ipt.trim();
                    let selectToUpdate='[name=select'+ipt+']';
                    let selgpx=document.querySelector(selectToUpdate); 
                    let optionLabels = Array.from(selgpx.options).map((opt) => opt.text);                    
                        if(optionLabels.includes(this.responseText.trim())) {
                            msg= ' <b class="green"> ! </b><b style="order:-1;"> Fichier mis à jour: </b>' ;
                        }
                        else {                
                             msg=' <b class="green">&check;</b> <em style="color:tomato">Le code pour ce fichier est dans la liste.</em>';
                            let newOpt = document.createElement('option');
                            newOpt.textContent =`${this.responseText}`;
                            let newAttr = `plugins/plx_trace/gpx/${dir.trim()}/${this.responseText.trim()}`;
                            newOpt.setAttribute('value',newAttr);
                            selgpx.appendChild(newOpt);  
                        }
            }  else { 
                 msg='<b  style="color:tomato;background:pink;" class="green">!</b>';
                 msgwarning='  color:tomato;text-align:center;font-weight:bold; ';             
            }  
                        
                newinfos=`    <p  style="display:flex;gap:0.25em;${msgwarning}"> ${this.responseText}   ${msg}</p> `;                 
                output.innerHTML =  newinfos;
            } else {
                output.innerHTML = "Une erreur " + xhttp.status + " est survenue en tentant de telecharger le fichier.";
            }
        }
 
        xhttp.send(form_data);
    }
}
for (let gpxFile of document.querySelectorAll('#drop_file_area .results select')) {
	  gpxFile.addEventListener("change", function() {
		let tracegpxFile= gpxFile.value;
        tracegpxFile = tracegpxFile.replace(/^(?:\.\.\/)+/, "");
		tracegpxFile.trim();
        let mydata='.'+this.getAttribute('data-code');

        let code= document.querySelector(mydata);
        let myPrvBox= '#'+this.getAttribute('data-code');
        let myPreviewId=  document.querySelector(myPrvBox);
        // reset preview
        myPreviewId.querySelector('object').removeAttribute('data');
        // setTimeout used to force CSS refresh style applied while switching map preview
        setTimeout(() => { 
            if (document.querySelector('input[name="preview"]').checked && tracegpxFile !=='' ) {
                let objectDataMap =`data:text/html;charset=utf-8,<!doctype html>
    <html lang="fr">
    <head>
      <meta charset="utf-8">
      <title> HTML5 </title>
      <meta name="description" content=" map ">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
        <style type="text/css">
        html {display:grid;min-height:100vh;}
          body { margin: auto 0; }
          .gpx { border: 2px rgba(125,125,125) solid; border-radius: 5px;
            box-shadow: 0 0 3px 3px rgba(200,200,200);
            max-width: 100%; margin:  auto; }
          .gpx header { padding: 0.5em; }
          .gpx h3 { margin: 0; padding: 0; font-weight: bold; }
          .gpx .start { font-size: smaller; color: gray; }
          .gpx .map { border: 1px rgba(105,105,105) solid; border-left: none; border-right: none;
            max-width: 100%; height: 400px; max-height: 70vh; margin: 0; }
          .gpx footer { background: ivory; padding: 0.5em; }
          .gpx ul.info { list-style: none; margin: 0; padding: 0; font-size: smaller; text-align:center}
          .gpx ul.info li { color: rgba(150,150,150); padding: 2px; display: inline; }
          .gpx ul.info li span { color: black; }
        </style>
    </head>
    <body>
            <section id="demo" class="gpx" data-gpx-source="${root}/${tracegpxFile}" data-map-target="map">
          <header>
            <h3>Chargement...</h3>
            <span class="start"></span>
          </header>

          <article>
            <div class="map" id="map"></div>
          </article>

          <footer>
        <ul class="info">
          <li>Distance:&nbsp;<span class="distance"></span>&nbsp;km</li>
          | <li>Dur&eacute;e:&nbsp;<span class="duration"></span></li>
          | <li>Temps:&nbsp;<span class="pace"></span>/km</li>
          | <li>Avg&nbsp;HR:&nbsp;<span class="avghr"></span>&nbsp;bpm</li>
          | <li>Cadence&nbsp;:&nbsp;<span class="cadence"></span>&nbsp;tpm</li>
        </ul>
        <ul class="info">
          <li>D&eacute;nivel&eacute;:&nbsp;positif <span class="elevation-gain"></span>&nbsp;m</li>
          | <li>D&eacute;nivel&eacute;:&nbsp;négatif <span class="elevation-loss"></span>&nbsp;m</li>
          | <li>D&eacute;nivel&eacute;:&nbsp;moyen <span class="elevation-net"></span>&nbsp;m</li>
        </ul>
          </footer>
        </section>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.5.1/gpx.js"></script>
        <script type="application/javascript">
          function display_gpx(elt) {
            if (!elt) return;

            var url = elt.getAttribute('data-gpx-source');
            var mapid = elt.getAttribute('data-map-target');
            if (!url || !mapid) return;

            function _t(t) { return elt.getElementsByTagName(t)[0]; }
            function _c(c) { return elt.getElementsByClassName(c)[0]; }

            var map = L.map(mapid);
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: 'Map data &copy; <a href="http://www.osm.org">OpenStreetMap</a>'
            }).addTo(map);

            var control = L.control.layers(null, null).addTo(map);

            new L.GPX(url, {
              async: true,
              marker_options: {
                startIconUrl: '${root}/plugins/plx_trace/icon/pin-icon-start.png',
                endIconUrl:   '${root}/plugins/plx_trace/icon/pin-icon-end.png',
                shadowUrl:    '${root}/plugins/plx_trace/icon/pin-shadow.png',
                wptIconUrls:  '${root}/plugins/plx_trace/icon/pin-icon-wpt.png',
              },
            }).on('loaded', function(e) {
              var gpx = e.target;
              map.fitBounds(gpx.getBounds());
              control.addOverlay(gpx, gpx.get_name());

              _t('h3').textContent = gpx.get_name();
              _c('start').textContent = gpx.get_start_time().toDateString() + ', '
                + gpx.get_start_time().toLocaleTimeString();
              _c('distance').textContent = gpx.m_to_km(gpx.get_distance().toFixed(0));

              _c('duration').textContent = gpx.get_duration_string(gpx.get_moving_time());
              _c('pace').textContent     = gpx.get_duration_string(gpx.get_moving_pace(), true);
              _c('avghr').textContent    = (gpx.get_average_hr()) ? gpx.get_average_hr() : "- -"; 
              _c('cadence').textContent =  (gpx.get_average_cadence()) ? gpx.get_average_cadence() : "- -";
              _c('elevation-gain').textContent = (gpx.get_elevation_gain()).toFixed(0);
              _c('elevation-loss').textContent = (gpx.get_elevation_loss()).toFixed(0);
              _c('elevation-net').textContent  = (gpx.get_elevation_gain()
                - gpx.get_elevation_loss()).toFixed(0);
            }).addTo(map);
          }

          display_gpx(document.getElementById('demo'));
        </script>
        </body>
    </html>`;
        
             // update preview
             myPrvBox= '#'+this.getAttribute('data-code');
             myPreviewId=  document.querySelector(myPrvBox);  
             myPreviewId.querySelector('object').setAttribute('data',objectDataMap);  
                    
        }}, 50);

        let codeTPL=`<div  data-gpxFile="${tracegpxFile}">&nbsp;</div>
`;        
        if (tracegpxFile ==='' ) { codeTPL='';       } 
        code.innerHTML=codeTPL;	
       
		});   
	}