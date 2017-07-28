<script type="text/javascript">
    var id_page = "0";
    var url = '.././fw/view/User.php?service=1';
    $().ready(function () {

        id_page = "0";
        $('div[name|="pg_register"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_recover"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_homepage"]').each(function (index) {
            $(this).show();
        });
        $('div[name|="pg_login"]').each(function (index) {
            $(this).show();
        });


        $.extend($.fn.validatebox.defaults.rules, {
            minLength: {
                validator: function (value, param) {
                    value = $.trim((value));
                    return value.length >= param[0];
                },
                message: 'Debe introducir m&iacute;nimo {0} caracteres'
            },
            txt_valid_pass: {
                validator: function (value, param) {
                    var ck_pass = document.getElementById("id_password").value;
                    if (value !== null) {
                        if (value === ck_pass) return true;
                        else return false;
                    } else return false;
                },
                message: 'Debe ingresar la misma contrase&ntilde;a'

            }

        });

    });

    function cancel() {
        id_page = "0";
        $('div[name|="pg_register"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_recover"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_homepage"]').each(function (index) {
            $(this).show();
        });
        $('div[name|="pg_login"]').each(function (index) {
            $(this).show();
        });
    }
    function register() {


        $('#fm_register').form('clear');

        id_page = "1";
        $.ajax({
            url: '.././fw/view/Service.php',
            dataType: 'json',
            data: {service: 'getcaptcha'},
            type: 'post',
            success: function (data) {
                document.id_img_captcha.src = "main/captcha.php?texto=" + data.uno;
                //document.id_img_captcha.src="captcha.php?texto="+data.uno;
                document.getElementById("id_hid_captcha").value = data.dos;
                // document.getElementById("id_captcha").value=data.uno;
            }
        });

        $('#id_school').combobox({
            //panelHeight:100
        });

        //document.getElementById('id_mail').value="cuenta.oscio@gmail.com";
        //document.getElementById('id_password').value="12345";
        //document.getElementById("id_pass_comp").value="12345";
        //document.getElementById('id_name').value="Cuenta";
        //document.getElementById('id_surname').value="Oscio";
        //document.getElementById('id_address').value="Nueva direccion";
        //document.getElementById('id_school').value="1";
        //document.getElementById('id_birthdate').value="04/26/1986";

        $('div[name|="pg_register"]').each(function (index) {
            $(this).show();

        });
        $('div[name|="pg_recover"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_homepage"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_login"]').each(function (index) {
            $(this).hide();
        });

    }
    function RecoverPassword() {

        $('#fm_recover').form('clear');

        id_page = "2";
        $.ajax({
            url: '.././fw/view/Service.php',
            dataType: 'json',
            data: {service: 'getcaptcha'},
            type: 'post',
            success: function (data) {
                //alert(data.uno);
                document.id_img_captcharecov.src = "main/captcha.php?texto=" + data.uno;
                document.getElementById("id_hid_captcharecov").value = data.dos;
                //document.getElementById("id_captcharecov").value=data.uno;
            }
        });

        $('div[name|="pg_register"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_recover"]').each(function (index) {
            $(this).show();
        });
        $('div[name|="pg_homepage"]').each(function (index) {
            $(this).hide();
        });
        $('div[name|="pg_login"]').each(function (index) {
            $(this).hide();
        });
        //alert(id_page);
    }

    function saveUser() {
        $('#fm_register').form('submit', {
            url: url,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {
                var result = eval('(' + result + ')');

                if (result.success) {
                    $.messager.alert('Registro Exitoso', 'Registro Realizado sin Problemas!!!', 'info');
                    cancel();		// close the dialog

                } else {

                    $.messager.alert('Error', result.msg, 'error');
                }
            }
        });
    }
    function recover() {

        $('#fm_recover').form('submit',
            {
                url: '.././fw/view/User.php?service=2',
                onSubmit: function () {

                    return $(this).form('validate');
                },
                success: function (result) {

                    var result2 = eval('(' + result + ')');

                    if (result2.success) {

                        $.messager.alert('Notifiaci&oacuten', 'Correo Enviado', 'info');
                        cancel();		// close the dialog

                    } else {
                        //$.messager.alert('Error',result2.msg,'error');
                        $.messager.alert('Error', result2.msg, 'error');

                    }
                }
            });
    }
</script>