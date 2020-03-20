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
});