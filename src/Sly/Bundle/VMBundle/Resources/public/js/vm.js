var specialCharacters = { "à": "a", "á": "a", "â": "a", "ã": "a", "ä": "a", "å": "a", "ò": "o", "ó": "o", "ô": "o", "õ": "o", "ö": "o", "ø": "o", "è": "e", "é": "e", "ê": "e", "ë": "e", "ç": "c", "ì": "i", "í": "i", "î": "i", "ï": "i", "ù": "u", "ú": "u", "û": "u", "ü": "u", "ÿ": "y", "ñ": "n", "-": " ", "_": " " }; 

(function($) {
    var apacheCheckbox = $('#sly_vm_form_type_vm_apache');
    var nginxCheckbox = $('#sly_vm_form_type_vm_nginx');
    var varnishCheckbox = $('#sly_vm_form_type_vm_varnish');
    var phpCheckbox    = $('#sly_vm_form_type_vm_php');
    var vimCheckbox    = $('#sly_vm_form_type_vm_systemPackages_4');
    var mysqlCheckbox  = $('#sly_vm_form_type_vm_mysql');
    var vmHostname     = $('#sly_vm_form_type_vm_hostname');

    if (typeof(vmUKey) == 'undefined') {
        var configModal = $('#vm-configuration-modal');
        configModal.modal('show');
    }

    $('#sly_vm_form_type_vm_name').on('keyup', function(e){
        e.preventDefault();

        var el = $(this);

        el.val(replaceSpecialCharacters(el.val()));
    });

    $('#generate-vm-ip-address').on('click', function(e){
        e.preventDefault();

        ipNumeric = Math.floor(Math.random() * (255 - 1 + 1)) + 1;
        ipAddress = '11.11.11.%randNumeric%'.replace('%randNumeric%', ipNumeric);

        $('#sly_vm_form_type_vm_ip').val(ipAddress);
    });

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

    checkNginxOption(nginxCheckbox);
    nginxCheckbox.on('change', function(){
        checkNginxOption(nginxCheckbox);
    });

    checkVarnishOption(varnishCheckbox);
    varnishCheckbox.on('change', function(){
        checkVarnishOption(varnishCheckbox);
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

function checkNginxOption(nginxCheckbox) {
    $('.nginx-options input, input.nginx-options').attr('readonly', nginxCheckbox.is(':checked') ? false : true);
    $('.nginx-options input[type=checkbox], input[type=checkbox].nginx-options').attr('disabled', nginxCheckbox.is(':checked') ? false : true);
}

function checkVarnishOption(varnishCheckbox) {
    $('.varnish-options input, input.varnish-options').attr('readonly', varnishCheckbox.is(':checked') ? false : true);
    $('.varnish-options input[type=checkbox], input[type=checkbox].varnish-options').attr('disabled', varnishCheckbox.is(':checked') ? false : true);
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

function replaceSpecialCharacters(text) {
    var reg = /[àáäâèéêëçìíîïòóôõöøùúûüÿñ_-]/gi;

    return text.replace(reg, function() { return specialCharacters[arguments[0]]; });
}
