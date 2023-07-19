function locationChange(url){
	window.location.replace(url);
}
function changeSelectElement($name,$no,$value){
	document.getElementsByName($name)[$no].value = $value;
}