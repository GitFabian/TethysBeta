
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

function alertify_ajax_response(response){
	if (response.substr(0,1)=='!') alertify.error(response.substr(1));
	else alertify.success(response);
}

function ask_delete(target){
	alertify.confirm("Datensatz löschen?", function (e) {
	    if (e) {
	        location.href=target;
	    }
	});
}




