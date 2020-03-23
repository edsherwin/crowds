$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.loader, .loaded-content').hide();

$('.view-contact').click(function() {
	const self = $(this);
	const bidder_id = self.data('userid');

	$('#contact-info-modal').modal();
	$('.loader').show();
	$('.loaded-content').hide();

	$.get(`/user/${bidder_id}`, ({ phone_number, messenger_id }) => {
		$('.loader').hide();
		$('.loaded-content').show();
		
		$('#phone-number')
			.prop('href', `tel:+${phone_number}`)
			.text(phone_number);
		$('#messenger-id')
			.prop('href', `https://m.me/${messenger_id}`)
			.text(messenger_id);
	});
});