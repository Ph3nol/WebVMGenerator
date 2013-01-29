(function($) {
    var apacheCheckbox = $('#sly_vm_form_type_vm_apache');
    var phpCheckbox    = $('#sly_vm_form_type_vm_php');
    var vimCheckbox    = $('#sly_vm_form_type_vm_systemPackages_4');
    var mysqlCheckbox  = $('#sly_vm_form_type_vm_mysql');
    var vmHostname     = $('#sly_vm_form_type_vm_hostname');

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

    checkApacheOption(apacheCheckbox);
    apacheCheckbox.on('change', function(){
        checkApacheOption(apacheCheckbox);
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

    checkVMHostname(vmHostname);
    vmHostname.on('change', function(){
        checkVMHostname(vmHostname);
    });
})(jQuery);

function checkApacheOption(apacheCheckbox) {
    $('.apache-options input, input.apache-options').attr('readonly', apacheCheckbox.is(':checked') ? false : true);
    $('.apache-options input[type=checkbox], input[type=checkbox].apache-options').attr('disabled', apacheCheckbox.is(':checked') ? false : true);
}

function checkPHPOption(phpCheckbox) {
    $('.php-options input, input.php-options').attr('readonly', phpCheckbox.is(':checked') ? false : true);
    $('.php-options input[type=checkbox], input[type=checkbox].php-options').attr('disabled', phpCheckbox.is(':checked') ? false : true);
}

function checkVimOption(vimCheckbox) {
    $('.vim-options input, input.vim-options').attr('readonly', vimCheckbox.is(':checked') ? false : true);
    $('.vim-options input[type=checkbox], input[type=checkbox].vim-options').attr('disabled', vimCheckbox.is(':checked') ? false : true);
}

function checkMySQLOption(mysqlCheckbox) {
    $('.mysql-options input, input.mysql-options').attr('readonly', mysqlCheckbox.is(':checked') ? false : true);
    $('.mysql-options input[type=checkbox], input[type=checkbox].mysql-options').attr('disabled', mysqlCheckbox.is(':checked') ? false : true);
}

function checkVMHostname(vmHostname) {
    $('#apache-hostname').html(vmHostname.val());
}
