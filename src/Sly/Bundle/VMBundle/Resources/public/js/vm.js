var vmCreationForm = $('#vm-creation');

(function($) {
    vmCreationForm.on('submit', function(e) {
        e.preventDefault();

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
    });

    $('button.apply-config').on('click', function(e){
        e.preventDefault();

        var el = $(this);

        var elConfigName = el.data('config');

        if (typeof(vmConfigs[elConfigName]) == 'undefined') {
            alert('"' + elConfigName + '" config name is not available');
        }

        vmConfig = vmConfigs[elConfigName];

        for (i in vmConfig) {
            var formEl = $('#sly_vm_form_type_vm_' + i);

            if (formEl.length > 0) {
                if (true === vmConfig[i]) {
                    formEl.attr('checked', 'checked');
                } else if (false === vmConfig[i]) {
                    formEl.removeAttr('checked');
                } else {
                    formEl.val(vmConfig[i]);
                }
            }
        }
    });
})(jQuery);
