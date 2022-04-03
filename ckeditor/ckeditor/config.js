/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


	
CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }
	];

	config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,Font,BidiLtr,BidiRtl,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Smiley,PageBreak,Iframe,About';
	 config.language = 'fr';
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,notification,button,toolbar,clipboard,panelbutton,panel,floatpanel,colorbutton,colordialog,xml,ajax,templates,menu,contextmenu,copyformatting,div,editorplaceholder,resize,elementspath,enterkey,entities,exportpdf,popup,filetools,filebrowser,find,floatingspace,listblock,richcombo,font,fakeobjects,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastetools,pastefromgdocs,pastefromlibreoffice,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,tableselection,undo,lineutils,widgetselection,widget,notificationaggregator,uploadwidget,uploadimage';
	config.skin = 'moono-lisa';
	// %REMOVE_END%
	config.removePlugins = "scayt";
	// Define changes to default configuration here. For example:
	 config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.templates = 'plx_trace,default';	
	config.templates_files = ['../../plugins/ckeditor/ckeditor/plugins/templates/templates/plx_trace.js','../../plugins/ckeditor/ckeditor/plugins/templates/templates/default.js'
	];
};

