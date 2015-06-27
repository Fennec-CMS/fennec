/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */

$(document).ready(function(){
	$.ajaxSetup({
		dataType: 'JSON',
		beforeSend: function () {
			$('body').css({
				'cursor': 'progress'
			});
		},
		complete: function () {
			$('body').css({
				'cursor': ''
			});
		}
	});
	
	$('.btn-delete').on('click', function(e){
		var route = $(this).attr('href'),
			tr = $(this).parent('div').parent('td').parent('tr');
		
		confirm('Do you really want to delete this content?', function() {
			$.ajax({
				url: route,
				success: function (data) {
					if (! data.errors && tr) {
						tr.fadeOut().delay(1000).remove()
					}
					alert(data.result);
				},
				
				error: function () {
					alert("A internal server error ocurred. Please try again later");
					// @todo implement error logging
				}
			})
		});
		
		e.preventDefault();
		return false;
	});
	
	if ("datetimepicker" in $(window)) {
		$('.datetime').datetimepicker({
			format: 'YYYY-MM-DD HH:mm'
		});
	}

	$('textarea').each(function(){
		var textarea = $(this),
			editor = textarea;
		editor.summernote({
			height: 200
		}).on('summernote.change', function(customEvent, contents, $editable) {
			textarea.val(contents);
		});
	});

	if ("tagsinput" in $(window)) {
		$('.tagsinput').tagsinput();
	}
});

function alert(message, callback) {
    var modal = '<div class="modal fade">';
    modal += '<div class="modal-dialog">';
    modal += '<div class="modal-content">';
    modal += '<div class="modal-header">';
    modal += '  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    modal += '  <h4 class="modal-title">Alert</h4>';
    modal += '</div>';
    modal += '<div class="modal-body">';
    modal += '<p>' + message + '</p>';
    modal += '</div>';
    modal += '<div class="modal-footer">';
    modal += '<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>';
    modal += '</div>';
    modal += '</div>';
    modal += '</div>';
    modal += '</div>';
    $(modal).prependTo('body').modal('show').on('hide.bs.modal', function() {
        if (callback) {
            callback();
        }
    });
}

function confirm(message, callback) {
    var modal = '<div class="modal fade">';
    modal += '<div class="modal-dialog">';
    modal += '<div class="modal-content">';
    modal += '<div class="modal-header">';
    modal += '  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    modal += '  <h4 class="modal-title">Confirm</h4>';
    modal += '</div>';
    modal += '<div class="modal-body">';
    modal += '<p>' + message + '</p>';
    modal += '</div>';
    modal += '<div class="modal-footer">';
    modal += '<button type="button" class="btn btn-danger confirm-false" data-dismiss="modal">No</button>';
    modal += '<button type="button" class="btn btn-success confirm-true" data-dismiss="modal">Yes</button>';
    modal += '</div>';
    modal += '</div>';
    modal += '</div>';
    modal += '</div>';
    $(modal).prependTo('body').modal('show').find('.confirm-true').on('click', function() {
        if (callback) {
            callback();
        }
    });
}
