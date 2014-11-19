/*
$page->add_library(ROOT_HTTP_CORE."/demo/modules/myqueries/toolbox.js");
 */
function view(root,ext){
	query=document.getElementById('id_query').value;
	document.getElementById('target').innerHTML="Lädt...";
	tethys_ajax(root+"/demo/modules/myqueries/ajax."+ext+"?cmd=view&query="+encodeURIComponent(query),
			"document.getElementById('target').innerHTML=response;" +
			"exe();" +
			"window.setTimeout(\"$('input[type=search]').first().focus()\",500);"
			);
}