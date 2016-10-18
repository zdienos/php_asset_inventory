/*

this file containts the code to populate appropriate values into the assigned_to field

*/

jQuery( document ).ready(function() {
	
	var typeSelect, assignedSelect
	typeSelect = $( "#assignment_type" );
	assignedSelect = $( "#assigned_to");

	typeSelect.change(function(){
		
		// get value of type
		
		// submit value of type
		
		// grab response
		
		// process response
		
		/*
		$.each(selectValues, function(key, value) {   
     	$('#mySelect')
			.append($('<option>', { value : key })
			.text(value)); 
		});
		*/
		
		assignedSelect.append("<option value='1'>Test</option>");
	});
});	