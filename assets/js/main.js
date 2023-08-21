function locationChange(url){
	window.location.replace(url);
}
function changeSelectElement(name,no,value){
	document.getElementsByName(name)[no].value = value;
}
function changePassword(newPass,returnPage){
	// alert(newPass);
	
	let form = document.createElement("form");
    let element1 = document.createElement("input"); 
    let element2 = document.createElement("input");  

    form.method = "POST";
    form.action = "passwordChange.php";   

    element1.value=newPass;
    element1.name="newPass";
    form.appendChild(element1);  

    element2.value=returnPage;
    element2.name="returnPage";
    form.appendChild(element2);

    document.body.appendChild(form);

    form.submit();
}
