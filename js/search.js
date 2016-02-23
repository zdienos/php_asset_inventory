/*
function grabModels(dataObj){
    
	var nxtElem = $("#model_id");

	$.ajax({
        url:"admin/get_makes.php",
        type:"post",
        data:dataObj,
        dataType:"json", // <-------------expecting json from php
        success:function(data){
           $(nxtElem).empty(); // empty the field first here.
           $.each(data, function(i, obj){
               $(nxtElem).html(obj);
           });
        },
        error:function(err){
           console.log(err);
        }
    });
}
*/

function grabModels(dataObj){
    
	var nxtElem = $("#model_id");

	$.ajax({
		url:"admin/get_makes.php",
		type:"post",
		data:dataObj,
		dataType:"json", // <-------------expecting json from php
		success:function(data){
			$(nxtElem).empty(); // empty the field first here.
			$.each(data, function(i, obj){
				console.log(obj);
				$('<option>',{
					value:obj.id,
					text:obj.model
				}).appendTo(nxtElem);		 
			});
        },
        error:function(err){
           console.log(err);
        }
    });
}


$(document).ready(function(){

	$(function(){
		$('#make_id').on('change', function(e){
			if(this.id === "make_id"){
			  var dataObj = {make_id:this.value};
			  grabModels(dataObj);
			}
		});
	});
	
});