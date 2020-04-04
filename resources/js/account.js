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
});

$('#request-officer-account').click(function() {
	const self = $(this);
	
	confirmAlert.fire({
		text: "Are you sure you want to continue? Your account could get banned if you're not who you say you are.",
	}).then((result) => {
	  if (result.value) {
	  	console.log('rara');
	    self.parent('form').submit();
	  }
	});

});