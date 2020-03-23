$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".bid").click(function() {
    const self = $(this);
    const order_id = self.data("id");
    const recipient = self.data("recipient");
    const address = self.data("address");
    const description = self.data("description");
    const datetime = self.data("datetime");
   
    $("#order_id").val(order_id);

    $("#order_recipient").val(recipient);
    $("#order_address").val(address);
    $("#order_description").val(description);
    $("#order_created_at").val(datetime);

    $("#order-recipient").text(recipient);
    $("#order-address").text(address);
    $("#order-description").text(description);
    $("#order-datetime").text(datetime);

    $("#bid-modal").modal();
});


$(function() {
    if ($('.bid-modal-error').length) {
        $("#bid-modal").modal();
    }

    const setup_modal = parseInt($('#setup_step').val());
    $(`#user-setup-modal-${setup_modal}`).modal();

    $('#province').change(function() {
        const id = $(this).val();
        $.get(`/province/${id}`, (cities) => {
            
            let html = '<option value="">Select city</option>';
            cities.forEach((row) => {
                html += `<option value="${row.id}">${row.name}</option>`;
            });

            $('#city').html(html);
            $('#barangay').html('');
        });
    });

    $('#city').change(function() {
        const id = $(this).val();
        $.get(`/city/${id}`, (barangays) => {

            let html = '<option value="">Select barangay</option>';
            barangays.forEach((row) => {
                html += `<option value="${row.id}">${row.name}</option>`;
            });

            $('#barangay').html(html);
        });
    });
    
});