if(gpxFile) { 
  let pathToGpx= prefX+gpxFile;
  let mapHTML =`<section id="demo" class="gpx" data-gpx-source="${pathToGpx}" data-map-target="map">
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
  </section>`;
  
    function display_gpx(elt) {
      if (!elt) return;
      var url = pathToGpx;
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
          startIconUrl: prefX+'plugins/plx_trace/icon/pin-icon-start.png',
          endIconUrl:   prefX+'plugins/plx_trace/icon/pin-icon-end.png',
          shadowUrl:    prefX+'plugins/plx_trace/icon/pin-shadow.png',
        },
      }).on('loaded', function(e) {
        var gpx = e.target;
        // date format options
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        map.fitBounds(gpx.getBounds());
        control.addOverlay(gpx, gpx.get_name());

        /*
         * Note: the code below relies on the fact that the demo GPX file is
         * an actual GPS track with timing and heartrate information.
         */
        _t('h3').textContent = gpx.get_name();
        _c('start').textContent = gpx.get_start_time().toLocaleDateString( locale, options)  + ', '
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
    document.getElementById('myMapGpx').innerHTML=mapHTML;
    display_gpx(document.getElementById('demo'));
  }
