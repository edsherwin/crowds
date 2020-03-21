$('#show-bid-cancel-modal').click(function() {
	const self = $(this);
	const id = self.data('id');

	$('#bid-cancel-form').prop('action', `/bid/${id}/cancel`);
	$('#bid-cancel-modal').modal();
});