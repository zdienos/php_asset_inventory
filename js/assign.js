/*

this file containts the code to populate appropriate values into the assigned_to field

*/

jQuery( document ).ready(function() {
	
	// datepicker for assignment start
    $( "#assignment_start" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: null,
        maxDate:  null,
        onSelect: function(selected) {
            $( "#assignment_start" ).datepicker("option","minDate",selected)
        }
    });

	
	// datepicker for assignment end
    $( "#assignment_end" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: null,
        maxDate:  null,
        onSelect: function(selected) {
            $( "#assignment_end" ).datepicker("option","minDate",selected)
        }
    });

});

jQuery( document ).ready(function() {

	var typeSelect, assignedSelect
	typeSelect = $( "#assignment_type" );
	assignedSelect = $( "#assigned_to");

	typeSelect.change(function(){
		
		// get value of type
		var dataId = typeSelect.val();

		// submit value of type
		$.get( "get_assigned_to.php", { id: dataId } ).done( function ( resp ){
			$(assignedSelect).empty();
			$.each( resp, function ( key, value ){
				var newOption = new Option(value, key);
				console.log(newOption);
				$(assignedSelect).append(newOption);
				//console.log( key + ": " + value );
			});
		});

	});
});