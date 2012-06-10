/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



CKEDITOR.editorConfig = function( config )

{
	// Define changes to default configuration here. For example:

	// config.language = 'fr';

	// config.uiColor = '#AADC6E';

	config.filebrowserBrowseUrl = 'yonetim/file_manager';
	config.filebrowserImageBrowseUrl = 'yonetim/file_manager';
	config.filebrowserFlashBrowseUrl = 'yonetim/file_manager';
	config.filebrowserUploadUrl = 'yonetim/file_manager';
	config.filebrowserImageUploadUrl = 'yonetim/file_manager';
	config.filebrowserFlashUploadUrl = 'yonetim/file_manager';
	config.filebrowserWindowWidth = '800';
	config.filebrowserWindowHeight = '500';

	config.resize_enabled = false;
	
	config.toolbar = 'Custom';

	config.toolbar_Custom = [
		['Source'],
		['Maximize'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
		['SpecialChar'],
		'/',
		['Undo','Redo'],
		['Font','FontSize'],
		['TextColor','BGColor'],
		['Link','Unlink','Anchor'],
		['Image','Table','HorizontalRule']
	];
	
	config.toolbar_Full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];
};

