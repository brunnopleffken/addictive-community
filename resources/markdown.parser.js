/* MARKDOWN REAL-TIME PARSER
 * Created by Brunno Pleffken Hosti
 * E-mail: brunno.pleffken@outlook.com
 * Release: 1.0.0
 * Since: 02/02/2014
 */

function markdownReplace(content) {
	console.log(content);
	var newline = content.indexOf('\r\n') != -1 ? '\r\n' : content.indexOf('\n') != -1 ? '\n' : '';
	
	// Avoid HTML tags
	//content = content.replace(/\</gm, '&lt;');
	//content = content.replace(/\>/gm, '&gt;');
	
	// **bold**, *italic*, __underline__ and ~~strikethrough~~
	content = content.replace(/\*\*(.+?)\*\*/gm, '<b>$1</b>');
	content = content.replace(/\*(.+?)\*/gm, '<em>$1</em>');
	content = content.replace(/\_\_(.+?)\_\_/gm, '<u>$1</u>');
	content = content.replace(/\~\~(.+?)\~\~/gm, '<s>$1</s>');
	
	// [[links]] with http://
	content = content.replace(/\[\[([http://].+?)\|(.+?)\]\]/gm, '<a href="$1" target="_blank" rel="nofollow">$2</a>');
	content = content.replace(/\[\[([http://].+?)\]\]/gm, '<a href="$1" target="_blank" rel="nofollow">$1</a>');
	// [[links]] without http://
	content = content.replace(/\[\[(.+?)\|(.+?)\]\]/gm, '<a href="http://$1" target="_blank" rel="nofollow">$2</a>');
	content = content.replace(/\[\[(.+?)\]\]/gm, '<a href="http://$1" target="_blank" rel="nofollow">http://$1</a>');
	
	// {{images}}
	content = content.replace(/\{\{([^\}\{]+?)\|([0-9]+?)x([0-9]+?)\}\}/gm, '<img src="$1" width="$2" height="$3">');
	content = content.replace(/\{\{([^\}\{]+?)\}\}/gm, '<img src="$1">');
	
	// ``code``
	content = content.replace(/``(.+?)``/gm, '<code>$1</code>');
	
	// [color:value][/color]
	content = content.replace(/\[color\:(.+?)\](.+?)\[\/color\]/gm, '<span style="color:$1">$2</span>');
	
	// * Lists
	// * And more lists...
	content = content.replace(/\*\*\*\* (.*)/gm, '<ul><ul><ul><ul><li>$1</li></ul></ul></ul></ul>');
	content = content.replace(/\*\*\* (.*)/gm, '<ul><ul><ul><li>$1</li></ul></ul></ul>');
	content = content.replace(/\*\* (.*)/gm, '<ul><ul><li>$1</li></ul></ul>');
	content = content.replace(/\* (.*)/gm, '<ul><li>$1</li></ul>');
	for(var ii = 0; ii < 3; ii++) content = content.replace(new RegExp('</ul>' + newline + '<ul>', 'g'), newline);
	
	// New lines to <br>
	content = content.replace(/\n/gm, '<br>');
	content = content.replace(/<\/li><br><li>/gm, '</li><li>');

	return content;
}

$(document).ready(function(){

	/* FUNCTION: Realtime markdown preview
	 * .markdownRealTime({ target: '#containerId'});
	 * Created by Brunno Pleffken Hosti
	 */

	$.fn.markdownRealTime = function(options) {
		var settings = $.extend({
			// These are default values
			target: '#markdownPreview'
		}, options);
		
		// Textarea alias
		var markdownTextarea = this;
		console.log(markdownTextarea);

		// Where the formatted text must be shown
		var markdownPreview = $(settings.target);
		console.log(markdownPreview);

		// If textarea has existing content
		var content = markdownTextarea.val();
		$(markdownPreview).html(markdownReplace(content));
		
		// Fire event and replace markdown elements when typing
		this.on('change keyup paste', function(){
			var content = markdownTextarea.val();
			content = markdownReplace(content);
			
			// Put formatted content in DIV container
			$(markdownPreview).html(content);
		});
	}

	/* FUNCTION: Markdown parser in existing content
	 * .markdownParser();
	 * Created by Brunno Pleffken Hosti
	 */

	$.fn.markdownParser = function() {
		this.each(function(){
			var content = $(this).html();
			content = markdownReplace(content);

			$(this).html(content);
		});
	}

	// ---------------------------------------------------
	// HOW TO USE IT
	// ---------------------------------------------------

	// REALTIME MARKDOWN PARSING
	// $('#markdownTextarea').markdownRealTime();

	// PARSING EXISTENT CONTENT
	// $('.postParsing').markdownParser();

});