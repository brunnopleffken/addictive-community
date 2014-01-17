/**
 *  Main Javascript/jQuery file
 */

// Functions for BBCode

function SimpleTag(tag, instr)
{
	var postTextarea = document.getElementById('post');
	var content = window.prompt(instr);
	
	if(content != null) {
		var done = "[" + tag + "]" + content + "[/" + tag + "]";
		postTextarea.value += done;
	}
	
	postTextarea.focus();
}

function FullTag(tag, param)
{
	var postTextarea = document.getElementById('post');
	var content = window.prompt('Enter your text');
	
	if(content != null) {
		var done = "[" + tag + "=\"" + param + "\"]" + content + "[/" + tag + "]";
		postTextarea.value += done;
	}

	postTextarea.focus();
}

function List(type)
{
	var postTextarea = document.getElementById('post');
	var list = new Array();
	var n = 1;
	
	if(type == "unordered") {
		var item = window.prompt('Type your item number ' + n);

		while(list != null) {
			n++;

			list.push(item);
			item = window.prompt('Type your item number ' + n);

			if(item == null || item == "") {
				for(var i = 0; i < list.length; i++) {
					postTextarea.value +=  "[*]" + list[i] + "\n";
				}
				return true;
			}
		}
	}

	postTextarea.focus();
}

// Control panel: edit profile photo (custom or Gravatar)

function SelectPhotoType()
{
	var option = document.getElementById('option');
	var opt_value = option.value;
	
	var gravatar_box = document.getElementById('gravatar');
	var custom_box = document.getElementById('custom');
	
	if(option.checked == true) {
		gravatar_box.style.display = "block";
		custom_box.style.display = "none";
	}
	else {
		gravatar_box.style.display = "none";
		custom_box.style.display = "block";
	}
	
	return 0;
}

// Alert box when setting a post as best answer

function SetBestAnswer(p_id, t_id)
{
	if(confirm("Are you sure you want set the selected post as Best Answer?\nAny current best answer will be replaced by the new one.")) {
		window.location.href = "index.php?module=thread&id=" + t_id + "&action=setbestanswer&postid=" + p_id;
	}
	else {
		return false;
	}
}

function UnsetBestAnswer(t_id)
{
	if(confirm("Are you sure you want to set the selected post as a regular answer.")) {
		window.location.href = "index.php?module=thread&id=" + t_id + "&action=unsetbestanswer";
	}
	else {
		return false;
	}
}

// Popup window (640x480)

function Popup(url)
{
	window.open(url, 'share', 'width=640, height=480, resizable=no, menubar=no, status=no, toolbar=no');
}