


function doform (name, option) { 
	
	switch (name) {
		
		case "upload" : do_submit("upload_save"); break;
		case "edit" : do_submit("edit_save"); break;
		
		default: alert( "(" + name + ") no action request");
	}
}


function do_submit(action, form_id="editform") {

	base_url = jsroot;
	post_url = base_url + "ajax/" + action + ".php";

	var form_selector = '#'+form_id;

	/// alert($(form_selector).serialize());
	/// var frmdata = new FormData($(form_selector)[0]);

	if ($("#waiting").length) $("#waiting").show(); // $("#waiting").show();

	$.ajax({
		type: "POST",
		url: post_url,
		data: $(form_selector).serialize(),
		dataType: "json", 
		success: function(data) {

			for (k=0; k < data.length; k++) {
				xassegnare (data[k]);
			}

			if ($("#waiting").length) $("#waiting").hide();
  		},
		error: function (jqXHR, status, err) {
			alert("an error occurred during request handling" + post_url);
		},
		complete: function (jqXHR, status) {
			// after success or error callback are executed
		}

	});

}


// fine