jQuery('.comment-up').each(function() {
	jQuery(this).unbind().on('click', function() {
		event.preventDefault();
		event.stopPropagation();
		if(comment_vote.status !== '1') {
			alert('please login to upvote comment');
			return;
		};
			
		var el = jQuery(this);
		var dataid = jQuery(this).data('id');
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
				el.find('.icon-heart').toggleClass('fill');
		    el.siblings('.comment-num').html(response['data']);
		  }
		})
		.fail(function() {
		});
		
	});
});