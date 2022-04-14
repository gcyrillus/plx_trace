/*
* This file is part of the plugin for PluXml 'plx_trace' , it is not a standalone file.
* original script was modified by Griboval Cyrille for plugin purpose
* prints '--' when value is missing from gpx file instead of NaN
* removed HTML IDs for CLASSs to allow showing a few maps at once.
* Multilingual language injected via the PluXml Plugin plx_trace
* Date format, according to default language* fr & en 
* actual (04/22) avalaible languages are french and english
* To translate , use languages file at: plugins/plx_trace/lang/fr.php - en.php . You can copy and rename them to translate to match another language
*/
for (let e of document.querySelectorAll("[data-gpxFile]")) {
  let gpx = prefX+e.getAttribute("data-gpxFile");
  displayMap(e, gpx);
} 
function displayMap(el, gpx) {
console.log(el + ' - ' + gpx);
  let tplMap =`<section class="gpx" data-gpx-source="${gpx}" data-map-target="map">
  <style type="text/css">
        
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
      <header>
       <h3>${loading}</h3>
      <p class="start"></p>
      </header>

      <article>
            <div class="map" id="map"></div>
      </article>

      <footer>
        <ul class="info">
          <li>${distance}<span class="distance"></span>&nbsp;km</li>
          | <li>${duration}<span class="duration"></span></li>
          | <li>${time}<span class="pace"></span>/km</li>
          | <li>${avghr}<span class="avghr"></span>&nbsp;bpm</li>
          | <li>${cadence}<span class="cadence"></span>&nbsp;tpm</li>
        </ul>
        <ul class="info">
          <li>${postilt}<span class="elevation-gain"></span>&nbsp;m</li>
          | <li>${negtilt} <span class="elevation-loss"></span>&nbsp;m</li>
          | <li>${avgtilt} <span class="elevation-net"></span>&nbsp;m</li>
        </ul>
      </footer>
    </section>`;

   
        el.innerHTML = tplMap;  

   if (!el) return;
        var url = gpx;
        var mapid = el.querySelector('.map');
        var locale = window.navigator.userLanguage || window.navigator.language;
        if (!url || !mapid) return;
        // search for tag
        function _t(t) { return el.getElementsByTagName(t)[0]; }
        // search for class
        function _c(c) { return el.getElementsByClassName(c)[0]; }  
                var map = L.map(mapid);
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: 'Map data &copy; <a href="http://www.osm.org">OpenStreetMap</a>'
        }).addTo(map);
        
        var control = L.control.layers(null, null).addTo(map);
         new L.GPX(url, {
          async: true,
          marker_options: {
          startIconUrl: prefX+'plugins/plx_trace/icon/pin-icon-start.png',
          endIconUrl:   prefX+'plugins/plx_trace/icon/pin-icon-end.png',
          shadowUrl:    prefX+'plugins/plx_trace/icon/pin-shadow.png',
          wptIconUrls:  prefX+'plugins/plx_trace/icon/pin-icon-wpt.png',
          },
        }).on('loaded', function(e) {
          var gpx = e.target;
          var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          map.fitBounds(gpx.getBounds());
          control.addOverlay(gpx, gpx.get_name());

          /*
           * Note: the code below relies on the fact that the demo GPX file is
           * an actual GPS track with timing and heartrate information.
           */
          _t('h3').textContent              = gpx.get_name();
          _c('start').textContent           = gpx.get_start_time().toLocaleDateString( defLang , options)  + ', ' + gpx.get_start_time().toLocaleTimeString();
          _c('distance').textContent        = gpx.m_to_km(gpx.get_distance().toFixed(0));

          _c('duration').textContent        = gpx.get_duration_string(gpx.get_moving_time());
          _c('pace').textContent            = gpx.get_duration_string(gpx.get_moving_pace(), true);
          _c('avghr').textContent           = (gpx.get_average_hr()) ? gpx.get_average_hr() : "- -"; 
          _c('cadence').textContent         =  (gpx.get_average_cadence()) ? gpx.get_average_cadence() : "- -";
          _c('elevation-gain').textContent  = (gpx.get_elevation_gain()).toFixed(0);
          _c('elevation-loss').textContent  = (gpx.get_elevation_loss()).toFixed(0);
          _c('elevation-net').textContent   = (gpx.get_elevation_gain() - gpx.get_elevation_loss()).toFixed(0);
        }).addTo(map);       
}
   