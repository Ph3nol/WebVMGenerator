$(function() {
    $('#vm-creation').on('submit', function() {
        var el            = $(this);
        var submitInput   = $('input[type=submit]', el);
        var responseModal = $('#vm-creation-response');

        submitInput.attr('disabled', 'disabled');

        $.ajax({
            url:    el.attr('action'),
            type:   el.attr('method'),
            data:   el.serialize(),
            success: function(generatorSessionID) {
                $('#vm-creation-modal').modal('show');
                submitInput.removeAttr('disabled');
            }
        });

        return false;
    });
});
