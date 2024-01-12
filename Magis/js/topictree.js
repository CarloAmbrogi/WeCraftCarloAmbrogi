// MAGIS - topic tree management functions


function nop() { 
	
   alert("nop");
}

function do_topnodes() {

   ajFn("ajax/topnodes",{poiId:0},show_topnodes);
}
function show_topnodes(objResp) {
   if (objResp.errMsg) console.log(objResp.errMsg);
   //else console.log(objResp.htm);
   else $("#topics").html(objResp.htm);
}

function tagbranch(tagid) {

   // alert(tagid);

   // $("#item_" + tagid).html("branch...");

   ajFn("ajax/children",{tagId:tagid},show_tagbranch);

}
function show_tagbranch(objResp) {
   if (objResp.errMsg) console.log(objResp.errMsg);
   // else console.log(objResp.target);
   else $('#item_' + objResp.target).html(objResp.htm);
}



function TEST_updateMap() {

   if (currLayer) detachLayer(map1,currLayer);

   currLayer = new L.LayerGroup();
   attachLayer(map1,currLayer);

   showall_mk();
}

function updateMap() {

var datainizio = $('#iniziale').val();
var datafine = $('#finale').val();

console.log ("time selection: " + datainizio + " to " + datafine);

var selezionati = "(";
$("input[type='checkbox']").each(function(){
    var sid = $(this).attr('id'); 
    var ischecked = $(this).is(":checked");

    if (ischecked) selezionati += ',' + sid;
});
selezionati += ")";
selezionati = selezionati.replace("(,", "(");

console.log("topic selection: " + selezionati);


   if (currLayer) detachLayer(map1,currLayer);
   currLayer = new L.LayerGroup();
   attachLayer(map1,currLayer);

   // showall_mk();
   ajFn("ajax/selpois",{time1:datainizio,time2:datafine,tags:selezionati},show_pois);
}
function show_pois(objResp) {
   if (objResp.errMsg) console.log(objResp.errMsg);
   // else console.log(objResp.poilist);
   else {
      console.log(objResp.debug);
      let poilist = objResp.poilist;
      objResp.poilist.forEach(function (e) {
         ajFn("ajax/read_poi",{poiId:e},showone_mk);
      });
   }

}


// fine