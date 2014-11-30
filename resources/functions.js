// Alert box in user action

function SetBestAnswer(p_id, t_id) {
	if(confirm("Are you sure you want set the selected post as Best Answer?\nAny current best answer will be replaced by the new one.")) {
		window.location.href = "index.php?module=thread&id=" + t_id + "&action=setbestanswer&postid=" + p_id;
	}
	else {
		return false;
	}
}

function UnsetBestAnswer(t_id) {
	if(confirm("Are you sure you want to set the selected post as a regular answer.")) {
		window.location.href = "index.php?module=thread&id=" + t_id + "&action=unsetbestanswer";
	}
	else {
		return false;
	}
}