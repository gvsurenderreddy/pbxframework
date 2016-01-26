$('#addNumber').on('show.bs.modal', function (e) {
	var number = $(e.relatedTarget).data('number');
	var description = $(e.relatedTarget).data('description');
	$("#number").val(number);
	$("#oldval").val(number);
	$("#description").val(description);
});

$(document).on('show.bs.tab', 'a[data-toggle="tab"]', function (e) {
    var clicked = $(this).attr('href');
    switch(clicked){
		case '#settings':
			$('#action-bar').removeClass('hidden');
			$('#Upload').addClass('hidden');
			$('#Submit').removeClass('hidden');
			$('#Reset').removeClass('hidden');
		break;
		case '#importexport':
			$('#action-bar').removeClass('hidden');
			$('#Submit').addClass('hidden');
			$('#Reset').addClass('hidden');
			$('#Upload').removeClass('hidden');
		break;
		default:
			$('#action-bar').addClass('hidden');
		break;
	}
});
$('#action-bar').addClass('hidden');

$('#submitnumber').on('click',function(){
	var num = $('#number').val(),
			desc = $('#description').val(),
			oldv = $('#oldval').val(),
			$this = this;

	$(this).blur();
	$(this).prop("disabled",true);
	$(this).text(_("Adding..."));
	if(num === ''){
		warnInvalid($('#number'), 'This cannot be blank');
	}
	$.post("ajax.php?module=blacklist&command=add",
		{
			action : "add",
			oldval : oldv,
			number : num,
			description: desc
		},
		function(data,status){
			$($this).prop("disabled",false);
			$($this).text(_("Save Changes"));
			if(data.status) {
				if(oldv.length > 0){
					alert(_("Entry Updated"));
				}else {
					alert(sprintf(_("Added %s to the blacklist."), num));
				}
				$('#blGrid').bootstrapTable('refresh',{});
			} else {
				alert(data.message);
			}
		}
	);
});
var processing = null;
$(document).on('click', '[id^="del"]', function(){
	var num = $(this).data('number');
	var idx = $(this).data('idx');
	$.post("ajax.php?module=blacklist&command=del",
		{
			action : "delete",
			number : num,
		}).done(function(){
			$('#blGrid').bootstrapTable('refresh',{silent: true});
		});
	});

	$(document).on('click', '[id^="report"]', function(){
		var num = $(this).data('number');
		$.post("ajax.php?module=blacklist&command=calllog",
			{number: num},
			function(data,status){
				console.log(data);
					$("#blReport").bootstrapTable({});
					$('#blReport').bootstrapTable('load',data);
			}
		);
		$("#numreport").modal("show");
	});
$('#Upload').on('click',function(){
	var file = document.getElementById("blacklistfile");
	var formData = new FormData();
	formData.append("blacklistfile", file.files[0])
	var xhr = new XMLHttpRequest();
	xhr.open('POST','config.php?display=blacklist&action=import', true);
	xhr.send(formData);
	xhr.onreadystatechange = function(){
		if(xhr.status == 200){
			location.reload()
		}else{
			alert("Import Failed");
		}
	}
});
//Bulk Actions
$('#action-toggle-all').on("change",function(){
	var tval = $(this).prop('checked');
	$('input[id^="actonthis"]').each(function(){
		$(this).prop('checked', tval);
	});
});

$('input[id^="actonthis"],#action-toggle-all').change(function(){
	if($('input[id^="actonthis"]').is(":checked")){
		$("#trashchecked").removeClass("hidden");
	}else{
		$("#trashchecked").addClass("hidden");
	}

});
//This does the bulk delete...
$("#trashchecked").on("click",function(){
	var reload = false;
	$('input[id^="actonthis"]').each(function(){
		if($(this).is(":checked")){
			var num = $(this).val();
			$.post("config.php?display=blacklist",
				{
					action : "delete",
					number : num,
				},
				function(data,status){
					if(status == "success"){
						reload = true;
						$("#row"+num).fadeOut(2000,function(){
							$(this).remove();
						});
					}
				}
			);
		}
		if(reload){
			location.reload();
		}
	});
	//Reset ui elements
	//hide the action element in botnav
	$("#delchecked").addClass("hidden");
	//no boxes should be checked but if they are uncheck em.
	$('input[id^="actonthis"]').each(function(){
		$(this).prop('checked', false);
	});
	//Uncheck the "check all" box
	$('#action-toggle-all').prop('checked', false);
});
