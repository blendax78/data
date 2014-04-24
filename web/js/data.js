var urlbase = 'http://data.vintimilla.org';
$(document).ready(function(){
	$('#editBtn').click(function(){
		editObject(this)
	});
	$('#deleteBtn').click(function(){
		if (confirm('Are you sure?')){
			deleteObject(this);
		}
	});
	$('#saveBtn').click(function(){
		saveObject(this);
	});
	$('#cancelBtn').click(function(){
		cancelObject(this);
	});
	$('.collapseHeader').click(function(){
		collapseOrHide(this);
	});
	$('#newGroupBtn').click(function(){
		if (confirm('Add a new group?')){
			location.href=urlbase+'/groups/?new=new';
		}
	});
	$('#newContactBtn').click(function(){
		if (confirm('Add a new contact?')){
			location.href=urlbase+'/contacts/?new=new';
		}
	});
	$('#newEventBtn').click(function(){
		if (confirm('Add a new event?')){
			location.href=urlbase+'/events/?new=new';
		}
	});
	$('#newNoteBtn').click(function(){
		if (confirm('Add a new note?')){
			location.href=urlbase+'/notes/?new=new';
		}
	});
	$('#newDocumentBtn').click(function(e) {
	    e.preventDefault();
	    $('#newDocumentModal').modal({
	        show: true, 
	        backdrop: 'static',
	        keyboard: true
	     });
        
        $('.btn-success').click(function(){
        	$('#newDocumentFrm').submit();
        });
	});

	$('.newRelationGroupBtn').click(function(){
		if (confirm('Add a new group?')){
			var parent = getHiddenParent();
			location.href=urlbase+'/groups/?new=new&parentid=' + parent.parentid + '&parenttype=' + parent.parenttype;
		}
	});
	$('.newRelationContactBtn').click(function(){
		if (confirm('Add a new contact?')){
			var parent = getHiddenParent();
			location.href=urlbase+'/contacts/?new=new&parentid=' + parent.parentid + '&parenttype=' + parent.parenttype;
		}
	});
	$('.newRelationEventBtn').click(function(){
		if (confirm('Add a new event?')){
			var parent = getHiddenParent();
			location.href=urlbase+'/events/?new=new&parentid=' + parent.parentid + '&parenttype=' + parent.parenttype;
		}
	});
	$('.newRelationNoteBtn').click(function(){
		if (confirm('Add a new note?')){
			var parent = getHiddenParent();
			location.href=urlbase+'/notes/?new=new&parentid=' + parent.parentid + '&parenttype=' + parent.parenttype;
		}
	});
	$('.newRelationDocumentBtn').click(function(e) {
	    e.preventDefault();
	    var parent = getHiddenParent();
	    $('#newDocumentModal').modal({
	        show: true, 
	        backdrop: 'static',
	        keyboard: true
	     });

        $('.btn-success').click(function(){
        	$('#newDocumentFrm').submit();
        });
	});

	$('#newRelationBtn').click(function(e) {
	    e.preventDefault();
	    $('#newRelationModal').modal({
	        show: true, 
	        backdrop: 'static',
	        keyboard: true
	     });
        
        $('#saveRelationBtn').click(function(){
        	$('#newRelationFrm').submit();
        });
        
    	$("#selectRelationType").change(function(e){
    		var $this = $(this);
    		$('.relationDD').hide();
    		
    		if ($this.val() != ""){
    			$('.' + $this.val() + 'DD').fadeIn();
    		}
    	});
	});
	
	$('.deleteRelationBtn').click(function(e){
		e.preventDefault();
		if (confirm('Are you sure?')){
			deleteRelation($(this).attr('id'));
		}
	});
	
});

function collapseOrHide(elem){
	var id = $(elem).attr('id');
	if ($('.' + id + 'Collapse:visible').length > 0){
		$(elem).find('.chevron').html('<i class=" icon-chevron-up"></i>');
		$('.' + id + 'Collapse:visible').hide(300);
	}else{
		$(elem).find('.chevron').html('<i class=" icon-chevron-down"></i>');
		$('.' + id + 'Collapse:hidden').show(300);
	}
}

function editObject(elem){
	var type = $(elem).attr('objectType');
	var url = location.href;
	
	switch (type){
	default: 
		url = urlbase+'/' + type + 's/?' + type + 'id=' + $(elem).attr(type + 'id') + '&edit=edit';
		break;
	}
	
	location.href = url;
}

function deleteRelation(id){
	$.get(urlbase+'/ajax.php?delete=1&type=relation&id=' +id, function(result){
		location.reload();
		//alert(result);
	});
}

function deleteObject(elem){
	var type = $(elem).attr('objectType');
	var id = $(elem).attr(type + 'id');
	var url = location.href;
	
	switch (type){
	default:
		url = urlbase+'/ajax.php?delete=1&type=' + type + '&id=' + id;
		break;
	}
	$.get(url, function(result){
		console.log(result);
	location.href = urlbase+'/' + type +'s/';

	});
	
}

function saveObject(elem){
	var type = $(elem).attr('objectType');

	switch (type){
	default:
		$('#editFrm').submit();
		break;
	}
}

function cancelObject(elem){
	var type = $(elem).attr('objectType');
	var url = location.href;
	
	switch (type){
	default:
		url = urlbase+'/' + type + 's/?' + type + 'id=' + $(elem).attr(type + 'id');
		break;
	}
	
	location.href = url;
}

function getHiddenParent(){
	var hiddenParent = $('#hiddenParent');
	return $.parseJSON(hiddenParent.val());
}
