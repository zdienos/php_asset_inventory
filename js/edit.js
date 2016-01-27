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

});	