
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

function random_integer(min,max){
	return Math.floor(Math.random() * (max*1 - min*1 +1)) + min*1;
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

function tethys_ajax_to_id2(query,id,Funktion){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET",query,true);
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById(id).innerHTML=xmlhttp.responseText;
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

function ask_delete(target,datensatz){
	if (!datensatz) datensatz="Datensatz";
	alertify.confirm(datensatz+" löschen?", function (e) {
	    if (e) {
	        location.href=target;
	    }
	});
}

function js_getSelectedValue(id){
	return document.getElementById(id).options[document.getElementById(id).selectedIndex].value
}

/**
 * datatable neu laden/refresh/reload: "datatable_init('table','".ROOT_HTTP_CORE."');"
 */
function datatable_init(selector,ROOT_HTTP_CORE){
	$(selector).dataTable({
		'bLengthChange':false,
		'aaSorting':[],
//		'bPaginate':false,
		language:{url:ROOT_HTTP_CORE+'/core/html/jquery.dataTables.German.json'},
	});
}

function time_delta_start(delay){
	setInterval(function(){{
		$('.td_ajax').each(function(){
			$(this).html(time_delta($(this).data('timestamp')));
		});
	}},delay*1000);
}
function time_delta(timestamp){
	now=Math.round(Date.now()/1000);
	delta=now-timestamp;
	if (delta>0){
		vz="vor";
	}else{
		delta=-delta;
		vz='noch';
	}
	if (delta>47304000) return vz+" "+(Math.round(delta/31536000))+" "+(vz=='noch'?"Jahre":"Jahren");
	if (delta>3952800) return vz+" "+(Math.round(delta/2635200))+" "+(vz=='noch'?"Monate":"Monaten");
	if (delta>907200) return vz+" "+(Math.round(delta/604800))+" Wochen";
	if (delta>129600) return vz+" "+(Math.round(delta/86400))+" "+(vz=='noch'?"Tage":"Tagen");
	if (delta>5400) return vz+" "+(Math.round(delta/3600))+" Stunden";
	if (delta>90) return vz+" "+(Math.round(delta/60))+" Minuten";
	return vz+" "+delta+" Sekunden";
}

function css_position(id,target){
	$('#'+id).draggable({
		stop: function(event, ui) {
			stop_css_position(target,this);
		}
	});
	$('#'+id).resizable({
		stop:function(event,ui){
			stop_css_position(target,this);
		}
	});
}
function stop_css_position(id,elem){
	$('#'+id).html('top:'+$(elem).position().top+'px;'
			+'<br>left:'+$(elem).position().left+'px;'
			+'<br>width:'+$(elem).width()+'px;'
			+'<br>height:'+$(elem).height()+'px;'
		);
}

function startSpinner(){
	document.getElementById('uploadSpinner').style.display="block";
}

/**
"tethys_update_members(&quot;".ROOT_HTTP_MODULES."/xxxxMODULxxxx/ajax.".CFG_EXTENSION."&quot;,
	&quot;update_member&quot;,&quot;xxxxIDxxxx&quot;,this,null);"
 */
function tethys_update_members(url,cmd,id,e,func){
		if(func==null)func="alertify_ajax_response(response);";
		ids=new Array();
		$(e).find(':selected').each(function(){
			ids.push($(this).val());
		});
		ids=ids.join(',');
		tethys_ajax(url+"?cmd="+cmd+"&id="+id+"&ids="+ids,func);
}

function showhide(state,target_selector){
	if(state){
		$(target_selector).removeClass('invisible');
	}else{
		$(target_selector).addClass('invisible');
	}
}
