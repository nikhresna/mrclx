jQuery('.comment-up').each(function() {
	jQuery(this).unbind().on('click', function() {
		event.preventDefault();
		event.stopPropagation();
		var el = jQuery(this);
		var dataid = jQuery(this).data('id');
		console.log(dataid);
		jQuery.ajax({
			url: comment_vote.ajaxurl,
			type: 'POST',
			data: {
		    'action': 'comment_vote_up',
		    'data': {'id': dataid}
		  }, 
		})
		.done(function(response) {
			if(response['success'] == false) {
				alert(response['data']);
			} else {
				console.log(response);
				el.toggleClass('comment-up-voted');
		    el.siblings('.comment-num').html(response['data']);
		  }
		})
		.fail(function() {
		});
		
	});
});