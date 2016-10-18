/*

this file containts the code to populate appropriate values into the assigned_to field

*/

jQuery( document ).ready(function() {
	$( "#assignment_type" ).change(function(){
		$( "#assigned_to").append("<option value='1'>Test</option>");
	});
});	