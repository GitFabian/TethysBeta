var lastevent=null;
$(document).ready(function(){
	$('textarea').bind('click', function(event){
		if(!(event.ctrlKey&&event.shiftKey)) return;
		e=this;
		e.value="Lade...";
		var xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","/tethys/core/ajax.dev?cmd=lorumipsum&length=short",true);
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				e.value=xmlhttp.responseText;
			}
		};
		xmlhttp.send();
	});
});
