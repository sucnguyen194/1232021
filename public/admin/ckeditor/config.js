/**

 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.

 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license

 */

 CKEDITOR.editorConfig = function (config) {
 	config.filebrowserBrowseUrl = '/admin/ckfinder/ckfinder.html';
 	config.filebrowserImageBrowseUrl = '/admin/ckfinder/ckfinder.html?type=Images';
 	config.filebrowserFlashBrowseUrl = '/admin/ckfinder/ckfinder.html?type=Flash';
 	config.filebrowserUploadUrl = '/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
 	config.filebrowserImageUploadUrl = '/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
 	config.filebrowserFlashUploadUrl ='/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	config.height = 400; // 500 pixels high.
	config.extraPlugins = 'youtube,uploadimage';

	//config.contentsCss =  '/admin/ckeditor/plugins/fontawesome/font-awesome/css/font-awesome.min.css';
	config.extraAllowedContent = 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*};div(*)[*]{*};style(*)[*]{*};strong(*)[*]{*};span(*)[*]{*};section(*)[*]{*};dl; dt dd[dir]';
	config.allowedContent = true;
    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;

	config.filebrowserUploadMethod = 'form';

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
	config.removeButtons = 'NewPage,Preview,Paste,PasteText,PasteFromWord,Cut,Copy,Undo,Redo,Replace,Find,SelectAll,Scayt,Save,Templates,Print,Form,Checkbox,TextField,Radio,Textarea,Select,Button,ImageButton,HiddenField,NumberedList,BulletedList,Outdent,Indent,Blockquote,CreateDiv,Anchor,Language,BidiRtl,BidiLtr,HorizontalRule,Smiley,ShowBlocks,About';

     CKEDITOR.dtd.$removeEmpty.i = 0;
     CKEDITOR.dtd.$removeEmpty['span'] = false;
};

