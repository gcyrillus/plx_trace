/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 * Template usefull for the CMS PluXml Plugin : plx_trace , to share gpx trails over an openstreetmap.
 */

// Register a templates definition set named "plx_trace".
CKEDITOR.addTemplates( 'plx_trace', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

	// The templates definitions.
	templates: [ {
		title: 'Modéle gpx, pour le plugin plx_trace',
		image: 'template4.gif',
		description: 'Un modèle pour y inserer le code d\'une carte gpx. Uniquement compatible avec le plugin <b>plx_trace</b>',
		html: '<h3>Titre: Remplacez ce titre et le texte d\'aide par les votres.</h3>'+
		'<p><strong><u>Aide:</u></strong>: Pour finaliser l\'insertion de votre trace GPX, <b style="color:red">veuillez, passez en mode </b><i>Source</i> en cliquant l\'icône <img src="../../plugins/ckeditor/ckeditor/plugins/sourcearea/icons/hidpi/source.png"> Source ,<b style="color:red"> puis remplacer et mettre à jour le lien</b> <i><code>plugins/plx_trace/gpx/<b style="color:tomato">test/etape1.gpx</b> </code></i> en indiquant celui de votre fichier gpx.(repertoire et nom de fichier)</p>'+
		' '+
		'<script>const gpxFile="plugins/plx_trace/gpx/demo/demo.gpx";</script>'+
		'<div id="myMapGpx">'+
		'  <img src="../../plugins/ckeditor/ckeditor/plugins/templates/templates/images/map.jpg" style="width:80%;margin:1em auto;display:block;">'+
		'  <p style="text-align:center;text-decoration:underline;">Cette image et ce texte seront remplacés par votre carte si le plugin <b>plx_trace</b> est actif.</p>'+
		'</div>'+
		'<p data-use="plx_trace">&nbsp;</p>'+
		' '+
		'<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>'
	}]
} );
