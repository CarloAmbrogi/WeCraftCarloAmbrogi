// librerie javascript
// by Jacopo Finocchi - 08/2021


// variabili generali
// ...


function doaction (name, object_id, context_id) { 
	// interfaccia generica per chiamate ajax verso goexe(...)

	modulo = "";
	azione = "";
	parametri = "";
	mode = "ajax";
	
	switch (name) {
		
		case "newmod" : modulo = "res"; azione="new_model"; mode="ajax"; break;
		case "clear" : modulo = "warning"; azione="clear"; break;		
		
		default: modulo = "";
	}
	
	if (mode == "link") {
		if (object_id) parametri = 'cid=' + object_id;
		if (context_id) parametri = parametri + '&pid=' + context_id;
	}
	
	if (mode == "direct") {
		if (object_id) parametri = 'cid=' + object_id;
		if (context_id) parametri = parametri + '&pid=' + context_id;
	}
		
	if (modulo) {
		if (mode == "ajax") goexe(modulo,azione,object_id,context_id);
		if (mode == "link") navexe(modulo,azione,parametri);
		if (mode == "direct") direxe(modulo,azione,parametri);
	}
		
	
	
}


// .............................................................................


function navexe(modulo,azione,sub)
{
   destinazione = 'execute.php?module=' + modulo;
   if (azione) destinazione = destinazione + '&action=' + azione;
   if (sub) destinazione = destinazione + '&' + sub;
   
   location = destinazione;
}

function direxe(modulo,azione,sub)
{
   destinazione = 'direct.php?module=' + modulo;
   if (azione) destinazione = destinazione + '&action=' + azione;
   if (sub) destinazione = destinazione + '&' + sub;
   
   location = destinazione;
}

function openexe(tabname,modulo,azione,sub)
{
   destinazione = 'execute.php?module=' + modulo;
   if (azione) destinazione = destinazione + '&action=' + azione;
   if (sub) destinazione = destinazione + '&' + sub;
   
   opTabs[tabname] = window.open(destinazione); // opens in new tab on most browsers
}

function jumpto(modulo,azione){
   destinazione = 'execute.php?module=' + modulo
   if (azione) destinazione = destinazione + '&action=' + azione;
   location = destinazione;
}


// .............................................................................

function notimp() {
	alert("funzionalità non implementata nella versione dimostrativa");
}

function nop() {
	// nessuna operazione
}


function winclose() {
	parent.$.fancybox.close();
}


function parefresh(destinazione) {
	// alert (destinazione);
	// parent.eval (istruzione);
	parent.doaction (destinazione); 
}


function gotop() {
	$("html, body").animate({ scrollTop: 0 }, "slow");
}



var redo = "";
function rego() {
	if (redo) eval(redo);
}
	

// .............................................................................


function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}


// .............................................................................

function goexe(modulo,azione,param,subparam) {
	ajexe(modulo,azione,param,subparam);
}


function ajexe(modulo,azione,parametri)
{
serverpage = jsroot + "/engine/goexecute.php";

// debug: // alert (serverpage + ":" + azione);

if ($("#waiting").length) $("#waiting").show(); // $("#waiting").show();

var params = {};
params [ 'tipo' ] = 'ajax';
params [ 'module' ] = modulo;
params [ 'action' ] = azione;
params [ 'param' ] = parametri; // per compatibilità con le chiamate già esistenti

for (var i = 0; i < arguments.length; i++) {
        // alert ( arguments[i] );
	    if (i > 2) { // p1 ora è sostituito dal preesistente "param"
			pname = 'p' + (i-1).toString(); // pnumber = i-1;		
			params [ pname ] = arguments[i]; 
		}
    }

// (meglio sostituire con una post ?)
$.get (serverpage, params,
  function(data) {
    for (k=0; k < data.length; k++) {
      xassegnare (data[k]);
    }
	
	if ($("#waiting").length) $("#waiting").hide(); // $("#waiting").hide();
  }, "json");


}


function xassegnare (elemento) {

/// alert (elemento["tipo"]);
	
switch (elemento["tipo"])
{
case "script":
  eval (elemento["cont"]);
  break;
case "htmlet":
  if (document.getElementById(elemento["nome"])) 
       document.getElementById(elemento["nome"]).innerHTML = elemento["cont"];
  break;
case "input":
  if (document.getElementById(elemento["nome"])) 
       document.getElementById(elemento["nome"]).value = elemento["cont"];
  break;
case "checkbox":
  if (document.getElementById(elemento["nome"])) 
       document.getElementById(elemento["nome"]).checked = elemento["cont"];
  break;
} 

}

function loadJsFile(filename) {
	// alert(filename);
	var jsLink = $("<script type='text/javascript' src='"+filename+"'>");
	$("head").append(jsLink); 
}



function loadElem (target_id = '#lower-page', page_url = 'ajax/elemtest.php', page_p1=1, page_p2=1 ) {

	base_url = jsroot;
	ajax_url = base_url + page_url;
	///alert(ajax_url);
	securecode = '43210';
	error_msg = "something went wrong";

	if ($("#waiting").length) $("#waiting").show(); 
	$.ajax({
		type: "POST",			   
		url: ajax_url,
		data: {
			'p1': page_p1,
			'p2': page_p2,
			's1': securecode
		},
		cache:false,
		success: function(response){
			if ($("#waiting").length) $("#waiting").hide();
			$(target_id).html(response);
			//if (document.getElementById(elemento[target_id])) document.getElementById(elemento[target_id]).innerHTML = response;
		},
		error: function(msgs){
			if ($("#waiting").length) $("#waiting").hide();
			$(target_id).html(error_msg);
		}
	});
	
}


function ajFn (page_url, obj_params, res_fn) {

	base_url = jsroot;
	ajax_url = base_url + page_url + ".php";
	///alert(ajax_url);

	securecode = '43210';
	error_msg = "something went wrong";

	$.ajax({
		type: "POST",			   
		url: ajax_url,
		data: obj_params,
		cache:false,
		success: function(response){
			// if ($("#waiting").length) $("#waiting").hide();
			const objRes = JSON.parse(response);
			res_fn(objRes);
		},
		error: function(msgs){
			// if ($("#waiting").length) $("#waiting").hide();
			// alert(error_msg);
			const objRes = {errMsg:error_msg};
			res_fn(objRes);
		}
	});
}


// ..........................................................

var doctype = '';

function selnews(elemento) {
	
	// var elemento = event.target;
	
	/// alert ( elemento.options[elemento.selectedIndex].value );
	
	if (elemento.options[elemento.selectedIndex].value == 1) menugo('ccirc');
	else menugo('cnews');
}

function godocum(userid) {

	// goexe('res','doc_list',userid);	
	navexe('res','doc_list','cid='+userid);
}

function gouser(userid) {

	navexe('res','utn_form','cid='+userid);
}

function delcmt(cmtid) {

	/// navexe('res','cmt_del','cid='+cmtid);
	goexe('res','cmt_del',cmtid);
}

function lastdocum(userid) {
	// doctype = '';
	if (document.getElementById("lastyr").checked) ajexe('res','doc_grid',userid,doctype,'1');
	else ajexe('res','doc_grid',userid,doctype,'0');
}

function seldoctype(userid, elemento) {
	
	doctype = elemento.options[elemento.selectedIndex].value;
	
	lastdocum(userid);
}

function climsgs(userid) {

	navexe('res','msg_all','cid='+userid);
}

// fine.