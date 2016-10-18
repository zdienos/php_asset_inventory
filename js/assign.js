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