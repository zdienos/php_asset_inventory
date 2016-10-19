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
		var dataObj = {id:dataId};

		$.ajax({
			url:"get_assigned_to.php",
			type:"get",
			data:dataObj,
			dataType:"json", // <-------------expecting json from php
			success:function(data){
				$(nxtElem).empty(); // empty the field first here.
				$.each(data, function(i, obj){
					console.log(obj);
					$('<option>',{
						value:obj.id,
						text:obj.name
					}).appendTo(assignedSelect);		 
				});
			},
			error:function(err){
			   console.log(err);
			}
		});
		
		
		
		/*
		// submit value of type
		$.get( "get_assigned_to.php", { id: dataId } ).done( function ( resp ){
			//$(assignedSelect).empty();
			console.log(resp);
			$.each( resp, function ( key, value ){
				var newOption = new Option(value, key);
				console.log(newOption);
				$(assignedSelect).append(newOption);
			});
		});
		*/

	});
});