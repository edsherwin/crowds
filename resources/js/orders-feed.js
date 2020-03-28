$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".bid").click(function() {
    const self = $(this);
    const order_id = self.data("id");
    const recipient = self.data("recipient");
    const description = self.data("description");
    const datetime = self.data("datetime");
    const friendly_datetime = self.data("friendlydatetime");
   
    $("#order_id").val(order_id);

    $("#order_recipient").val(recipient);
    $("#order_description").val(description);
    $("#order_created_at").val(datetime);

    $("#order-recipient").text(recipient);
    $("#order-description").text(description);
    $("#order-datetime").text(friendly_datetime);

    $("#bid-modal").modal();
});


$(function() {
    if ($('.bid-modal-error').length) {
        $("#bid-modal").modal();
    }
});