/*
jQuery( document ).ready(function(){
	$( "#start" ).datepicker();
	$( "#end" ).datepicker();
});
*/

jQuery( document ).ready(function() {

    // datepicker for purchase date
    $( "#purchase_date" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: null,
        maxDate:  null,
        onSelect: function(selected) {
            $( "#purchase_date" ).datepicker("option","minDate",selected)
        }
    });
    
    // datepicker for surplus date
    $( "#surplus_date" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: null,
        maxDate:  null,
        onSelect: function(selected) {
            $( "#surplus_date" ).datepicker("option","minDate",selected)
        }
    });

    // validation
    
    $("#assetForm").validate({
        rules: {
            asset_tag: "required",
            serial_number: "required",
            
        }
    });
	
	$("#assetForm").validate({
		rules: {
			firstname: "required",
			topic: {
				required: "#newsletter:checked",
				minlength: 2
			},
			agree: "required"
		},
		messages: {
			firstname: "Please enter your firstname",
			username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			topic: "Please select at least 2 topics"
		}
	});

});	