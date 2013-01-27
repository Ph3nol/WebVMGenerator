var vmCreationForm = $('#vm-creation');

(function($) {
    var configModal = $('#vm-configuration-modal');
    configModal.modal('show');

    var configSelectField = $('#sly_vm_form_type_vm_configuration');

    configSelectField.on('change', function(e){
        e.preventDefault();

        var el           = $(this);
        var elConfigName = el.val();

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
})(jQuery);
