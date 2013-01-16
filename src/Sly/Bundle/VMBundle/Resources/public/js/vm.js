$(function() {
    $('#vm-creation').on('submit', function() {
        var el            = $(this);
        var submitInput   = $('input[type=submit]', el);
        var responseModal = $('#vm-creation-modal');

        submitInput.attr('disabled', 'disabled');

        $.ajax({
            url:       el.attr('action'),
            type:      el.attr('method'),
            data:      el.serialize(),
            success: function(response) {
                $('.modal-body', responseModal).html(response);
                responseModal.modal('show');
                submitInput.removeAttr('disabled');
            }
        });

        return false;
    });
});
