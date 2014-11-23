/*
$page->add_library(ROOT_HTTP_CORE."/demo/modules/myqueries/toolbox.js");
 */
function view(root,ext){
	query=document.getElementById('id_query').value;
	con=js_getSelectedValue('id_con');
	document.getElementById('target').innerHTML="Lädt...";
	tethys_ajax(root+"/demo/modules/myqueries/ajax."+ext+"?cmd=view"
			+"&con="+con
			+"&query="+encodeURIComponent(query),
		"document.getElementById('target').innerHTML=response;"
			+ "datatable_exe();"
			+ "window.setTimeout(\"$('input[type=search]').first().focus()\",500);"
	);
}
function save(root,ext){
	name=document.getElementById('id_name').value;
	desc=document.getElementById('id_desc').value;
	query=document.getElementById('id_query').value;
	con=js_getSelectedValue('id_con');
	if (!query){alertify.error("Kein Query angegeben!");document.getElementById('id_query').focus();return;}
	if (!name){alertify.error("Bitte Name angeben!");document.getElementById('id_name').focus();return;}
	document.getElementById('target').innerHTML="Speichere...";
	tethys_ajax(root+"/demo/modules/myqueries/ajax."+ext+"?cmd=save"
			+"&query="+encodeURIComponent(query)
			+"&name="+encodeURIComponent(name)
			+"&desc="+encodeURIComponent(desc)
			+"&con="+con
		,"location.href='?response='+response;"
	);
}
function save_create(root,ext){
	desc=(document.getElementById('id_desc')?document.getElementById('id_desc').value:"");
	query=document.getElementById('id_query').value;
	con=js_getSelectedValue('id_con');
	document.getElementById('target').innerHTML="Speichere...";
	location.href="?cmd=save"
		+"&beschreibung="+encodeURIComponent(desc)
		+"&query="+encodeURIComponent(query)
		+"&connection="+con;
}
