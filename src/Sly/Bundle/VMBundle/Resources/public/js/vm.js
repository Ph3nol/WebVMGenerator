(function($) {
    var phpCheckbox   = $('#sly_vm_form_type_vm_php');
    var vimCheckbox   = $('#sly_vm_form_type_vm_systemPackages_3');
    var mysqlCheckbox = $('#sly_vm_form_type_vm_mysql');

    var configModal = $('#vm-configuration-modal');
    configModal.modal('show');

    $('#sly_vm_form_type_vm_configuration').on('change', function(e){
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

    $('#vm-creation').on('submit', function(e) {
        e.preventDefault();

        var el            = $(this);
        var submitInput   = $('input[type=submit]', el);
        var responseModal = $('#vm-creation-modal');

        submitInput.attr('disabled', 'disabled');

        $.ajax({
            url:  el.attr('action'),
            type: el.attr('method'),
            data: el.serialize(),
            success: function(response) {
                $('.modal-body', responseModal).html(response);
                responseModal.modal('show');
                submitInput.removeAttr('disabled');
            }
        });
    });

    checkPHPOption(phpCheckbox);
    phpCheckbox.on('change', function(){
        checkPHPOption(phpCheckbox);
    });

    checkVimOption(vimCheckbox);
    vimCheckbox.on('change', function(){
        checkVimOption(vimCheckbox);
    });

    checkMySQLOption(mysqlCheckbox);
    mysqlCheckbox.on('change', function(){
        checkMySQLOption(mysqlCheckbox);
    });
})(jQuery);


function checkPHPOption(phpCheckbox)
{
    $('.php-options input, input.php-options')
        .attr('disabled', phpCheckbox.is(':checked') ? false : true)
    ;
}

function checkVimOption(vimCheckbox)
{
    $('.vim-options input, input.vim-options')
        .attr('disabled', vimCheckbox.is(':checked') ? false : true)
    ;
}

function checkMySQLOption(mysqlCheckbox)
{
    $('.mysql-options input, input.mysql-options')
        .attr('disabled', mysqlCheckbox.is(':checked') ? false : true)
    ;
}
