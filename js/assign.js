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
		
		console.log("type: " + dataId );
		
		// submit value of type
		$.get( "get_assigned_to.php", { id: dataId } ).done( function ( resp ){
			$.each( resp, function ( key, value ){
				console.log( key + ": " + value );
			});
		});
	
		
		// grab response
		var tempVal = [1,"Russell Pruitt"];
		var tempVal2 = [2,"James Grimm"];
		var tempVal3 = [3,"Matt Cravens"];
		var tempValues = [tempVal,tempVal2,tempVal3]
		
		// process response
		$.each(tempValues, function(key, value) {   
			$(assignedSelect)
				.append($('<option>', { value : key })
				.text(value)); 
		});
		
		
		//assignedSelect.append("<option value='1'>Test</option>");
	});
});