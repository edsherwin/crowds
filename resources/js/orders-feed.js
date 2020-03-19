$(".bid").click(function() {
    const self = $(this);
    const recipient = self.data("recipient");
    const address = self.data("address");
    const description = self.data("description");
    const datetime = self.data("datetime");

    $("#order-recipient").text(recipient);
    $("#order-address").text(address);
    $("#order-description").text(description);
    $("#order-datetime").text(datetime);

    $("#bid-modal").modal();
});
