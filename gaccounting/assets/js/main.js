$('[data-dismiss=modal]').on('click', function (e) {
    clearFormInputs();
});
 $(".model-reload").on('hidden.bs.modal', function (e) {
     location.reload(true)
 });
 
function clearFormInputs() {
    $('form').find("input,textarea,select").not(':input[type=checkbox],:input[type=radio],:input[type=button], :input[type=submit], :input[type=reset]')
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
    
    $(".chosen-select").val('').trigger("chosen:updated");
    $(".span_journal_text").text('');
     
}

var overlay ;
function spinner(status){   //Spinner configurations
    
    if(status=='hide'){
       $('.ui-ios-overlay').remove();
       exit;
    }
    
    var opts = {
		lines: 13, // The number of lines to draw
		length: 11, // The length of each line
		width: 5, // The line thickness
		radius: 17, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		color: '#FFF', // #rgb or #rrggbb
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};
	var target = document.createElement("div");
	document.body.appendChild(target);
	var spinner = new Spinner(opts).spin(target);
	iosOverlay({
		text: "Please wait",
		spinner: spinner
	});
       
	return false;
}

function alertManager(id){
    $("#"+id).fadeTo(3000, 500).slideUp(500, function(){
    $("#"+id).slideUp(500);
});
}