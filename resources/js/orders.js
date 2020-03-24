import Swal from 'sweetalert2';

const confirmAlert = Swal.mixin({
	customClass: {
		confirmButton: 'btn btn-sm ml-2 btn-primary',
		cancelButton: 'btn btn-sm ml-2 btn-secondary'
	},
	buttonsStyling: false,
	showClass: {
		popup: 'swal2-noanimation',
		backdrop: 'swal2-noanimation'
	},
	title: ``,
	icon: 'warning',
	showCancelButton: true,
	reverseButtons: true,
	confirmButtonText: 'Yes'
})

$('.accept-bid').click(function() {
	const self = $(this);
	const bidder = self.data('bidder');

	confirmAlert.fire({
		text: `Are you sure you want to accept the bid by ${bidder}?`,
	}).then((result) => {
	  if (result.value) {
	    self.parent('form').submit();
	  }
	});
});

$('.mark-as-noshow').click(function() {
	const self = $(this);
	const bidder = self.data('bidder');

	confirmAlert.fire({
		text: `Are you sure you want to mark ${bidder} as no show?`,
	}).then((result) => {
	  if (result.value) {
	    $(this).parent('form').submit();
	  }
	});
});

$('.mark-as-fulfilled').click(function() {
	const self = $(this);
	confirmAlert.fire({
		text: "Are you sure you want to mark the order as fulfilled?",
	}).then((result) => {
	  if (result.value) {
	  	console.log('bam');
	    self.parent('form').submit();
	  }
	});
});