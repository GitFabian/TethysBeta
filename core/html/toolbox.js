
function highlight_table_col(selector){
	$( selector+" td" ).hover(
		function() {
			$(this).parents('table').find('th:nth-child(' + ($(this).index() + 1) + ')')
				.addClass("col_highlight");
		}, function() {
			$(this).parents('table').find('th:nth-child(' + ($(this).index() + 1) + ')')
				.removeClass("col_highlight");
		}
	);
}

var PASSalpha="abdefghijnpqrt";
var PASSNUM="23456789";

function autofill_password(target_id){
	var pass="";
	for( var i=0; i < 2; i++ ) pass += PASSalpha.charAt(Math.floor(Math.random() * PASSalpha.length));
	for( var i=0; i < 4; i++ ) pass += PASSNUM.charAt(Math.floor(Math.random() * PASSNUM.length));
	document.getElementById(target_id).value=pass;
}


/**
 * tethys_ajax('.../ajax.php?cmd=...',"alert(response);");
 */
function tethys_ajax(query,Funktion){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",query,true);
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			if (Funktion){
				new Function("response",Funktion)(xmlhttp.responseText);
			}
		}
	};
	xmlhttp.send();
}

function tethys_ajax_to_id(query,id){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",query,true);
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById(id).innerHTML=xmlhttp.responseText;
		}
	};
	xmlhttp.send();
}

function alertify_ajax_response(response){
	if (response.substr(0,1)=='!') alertify.error(response.substr(1));
	else alertify.success(response);
}

function ask_delete(target,datensatz){
	if (!datensatz) datensatz="Datensatz";
	alertify.confirm(datensatz+" löschen?", function (e) {
	    if (e) {
	        location.href=target;
	    }
	});
}




