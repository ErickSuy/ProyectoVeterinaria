<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- INCLUDESCRIPT BLOCK : ihead -->
    <script type="text/javascript">
        /*
        $().ready(function() {
            $('#frmPin').form('clear');

            $.ajax({
                url: '../.././fw/view/Service.php',
                dataType: 'json',
                data: {aData},
                type: 'post',
                success: function(data){
                    alert(data.uno);
                    document.id_img_captcharecov.src="../main/captcha.php?texto="+data.uno;
                    document.getElementById("id_hid_captcharecov").value=data.dos;
                }
            });
        });
        */
    </script>
    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }

        function fPalabraClave() {
            document.frmPin.txtPalabraClave.focus();
            document.frmPin.txtPalabraClave.select();
        }

        function vIngresaPalabra() {
            strPalabraClave = new String;
            strPalabraClave = document.frmPin.txtPalabraClave.value;
            if ((strPalabraClave.length<1)||(strPalabraClave==' ')||(strPalabraClave=='_'))  {
                alert("!! Falta de Informacion !! \n Debe ingresar su Palabra Clave");
                document.frmPin.Dato12.value = 0;
                document.frmPin.txtPalabraClave.focus();
                document.frmPin.txtPalabraClave.select();
            }
            else {  document.frmPin.Dato12.value = 1;  }
        }

        function vTelefono()  {
            strTelefono = new String;
            strTelefono = document.frmPin.txtTelefono.value;
            if ((strTelefono.length>0)&&(strTelefono.length<8))  {
                document.frmPin.txtTelefono.focus();
                document.frmPin.txtTelefono.select();
            }
        }

        function vCelular(form)  {
            strCelular = new String;
            strCelular = form.txtCelular.value;
            if ((strCelular.length>0)&&(strCelular.length<8))  {
                form.txtCelular.focus();
                form.txtCelular.select();
            }
        }

        function HabilitaActualiza() {
            document.frmPin.btnSubmit.disabled=false;
            document.frmPin.btnSubmit.focus();
        }

        function HabilitarBoton(forma) {
            strCorreo = new String;
            strCorreo = forma.txtCorreo.value;
            if ((strCorreo=="correo@empresa.com")||(strCorreo.length<1))  {
                forma.txtCorreo.focus();
                forma.txtCorreo.select();
                forma.btnSubmit.disabled=true;
            }
            else {
                forma.btnSubmit.disabled=false;
            }
        }

        function vCorreo() {
            document.frmPin.txtCorreo.focus();
            document.frmPin.txtCorreo.select();
        }

        function vDireccion(form) {
            document.frmPin.txtDireccion.focus();
            document.frmPin.txtDireccion.select();
        }

        function vContrasenia(form) {
            TamanioN = form.txtContraseniaN.value;
            TamanioC = form.txtContraseniaC.value;
            if ((TamanioN.length<4)||(TamanioN.length>16))  {  // que el tamaño de la contraseña sea >=4 y <=16
                alert("!! La Nueva Contraseña debe contener como mínimo 4 caracteres !!");
                form.txtContraseniaN.focus();
                form.txtContraseniaN.select();
                form.txtContraseniaC.value = '';
            }
            else {
                if (form.txtContraseniaC.value != form.txtContraseniaN.value)  {  // que el nuevo Contrasenia coincida con la confirmación
                    alert("!! La confirmación de la Nueva Contraseña no coincide !!");
                    form.txtContraseniaN.focus();
                    form.txtContraseniaN.select();
                    form.txtContraseniaC.value = '';
                }
                else {
                    form.btnSubmit.disabled=false;
                    form.btnSubmit.focus();
                }
            }
        }

        function vBoton(form,forma) {
            switch (forma) {
                case 1 : form.btnSubmit.disabled=true;    break;
                case 2 : form.btnModClave.disabled=true;  break;
            }
        }

        function checkIt(paramboton,campo,msg) {
            /*
            var reCorreo=/^\w[\w\-\.\!\#\%\(\)\¡\_)]+\@\w[\w\-]+(\.[\w\-]+)+$/;
            if (!reCorreo.test(campo.value)) {
                alert(msg);
                campo.select();
                campo.focus();
                document.frmPin.Dato11.value = 0;
                return false;
            }
            BloquearBoton(paramboton,0);
//   boton.disabled=true;
*/
            document.frmPin.Dato11.value = 1;
            return true;
        }

        // funcion para completar el carne o el registro de personal correctamente
        function Completar(form)
        {
            if (vCarnet(form))
                if (form.txtGrupo.value==3)  {  // si el grupo es estudiantes
                    strCarne = new String;
                    strCarne = form.txtUser.value;
                    strYear = new String;
                    var intNumeric=0;
                    switch(strCarne.length)  {
                        // esto es necesario si son carnes con inicio de 98 y 99
                        case 7: intNumeric=strCarne;
                            if (intNumeric>9799999){
                                strCarne="19"+strCarne;
                                form.txtUser.value=strCarne;
                            }
                            else {
                                strCarne="00"+strCarne;
                                form.txtUser.value=strCarne;
                            }
                            break;
                        // esto es necesario si son carnes con todo el anio completo pero menores a 1998
                        case 9: strYear=strCarne.charAt(0)+strCarne.charAt(1)+strCarne.charAt(2)+strCarne.charAt(3);
                            if (strYear < 1998)
                                form.txtUser.value = "00" + strCarne.substr(2,strCarne.length-2);
                            break;
                        //esto es necesario para autocompletar para carnes de longitud distinta
                        default: for (;(strCarne.length<9);)
                            strCarne="0"+strCarne;
                            form.txtUser.value=strCarne;
                    } //end del swicht(strCarne.length)
                } //end del if(form.txtGrupo.value==3)
                else {
                    strRegPer = new String;
                    strRegPer = form.txtUser.value;
                    //esto es necesario para autocompletar porque son registros de personal
                    for (;(strRegPer.length<9);)
                        strRegPer="0"+strRegPer;
                    form.txtUser.value=strRegPer;
                }
        } // end de la function Completar

        /***************************************************************
         Esta función elimina todos los espacios en blanco de izquierda
         y derecha del campo txtUser del formulario.
         ***************************************************************/
        function depura(form)
        {
            strCarne = new String();
            strCarne = document.frmPin.txtUser.value;
            j=0;
            for(i=0;strCarne.charCodeAt(i)==32;i++) j++;
            strCarne=strCarne.substr(j,strCarne.length);
            j=strCarne.length;
            for(i=strCarne.length;strCarne.charCodeAt(i-1)==32;i--) j--;
            strCarne=strCarne.substr(0,j);
            document.frmPin.txtUser.value=strCarne;
        }

        /**************************************************************
         Esta función verifica el tipo de datos del campo txtUser que
         corresponde al carnet.  Primero llama a depura para eliminar
         espacios en blanco del mismo, luego verifica que sea solamente
         de contenido numérico y si es así autocompleta el campo con
         ceros o con uno nueve dependiendo del caso.  Retorna True si
         todos los datos son numéricos y tiene longitud mayor a 0.
         **************************************************************/
        function vCarnet(form)
        {
            var intNumeric=0;
            strCarne = new String;
            strYear = new String;
            dateHoy = new Date;
            depura(form);
            strCarne = document.frmPin.txtUser.value;
            if ((strCarne.length<1)||(strCarne.length>9)) return false;
            for(i=0;i<strCarne.length;i++)
                if((strCarne.charCodeAt(i)>57)||(strCarne.charCodeAt(i)<48))   return false;
            return true;
        }

        function vDatos(form)
        {
            if (vCarnet(form)) {
                Completar(form);
                document.frmPin.txtGrupo.focus();
            }
//  echo('   form.btnLogin.disabled=false;     ');
        }

        function vGrupo() {
            if (document.frmPin.txtGrupo.value=='0') {
                alert("!! Falta de Informacion !! \n Debe ingresar el Grupo");
                document.frmPin.txtGrupo.focus();
            }
        }

        /******************************************************************
         Esta función verifica el tipo de datos del carnet.  Debe invocarse
         al abandonar el campo txtUser o
         el campo txtPass del formulario para verificar que los datos
         insertados son correctos
         ******************************************************************/
        function vPalabraClave(form) {
            strCarne = new String();
            strCarne = document.frmPin.txtUser.value;
            strPalabraClave = new String;
            strPalabraClave = document.frmPin.txtPalabraClave.value;
            strCorreo = new String;
            strCorreo = document.frmPin.txtCorreo.value;
            if (strCarne.length<1) {
                alert("!! Falta de Informacion !! \n Debe ingresar su Carné");
                document.frmPin.txtUser.focus();
            } else if ((strCorreo=="correo@empresa.com")||(strCorreo.length<1)) {
                alert("!! Falta de Informacion !! \n Debe ingresar su Correo");
                document.frmPin.txtCorreo.focus();
                document.frmPin.txtCorreo.select();
            } else if ((strPalabraClave.length<1)||(strPalabraClave==' '))  {
                alert("!! Falta de Informacion !! \n Debe ingresar su Palabra Clave");
                document.frmPin.txtPalabraClave.focus();
                document.frmPin.txtPalabraClave.select();
            } else {
                form.btnSubmit.disabled=false;
                form.btnSubmit.focus();
            }
        }
    </script>
</head>
<body>
<!-- ENCABEZADO -->
<!-- INCLUDESCRIPT BLOCK : iheader -->
<!-- CONTENIDO -->
<div class="bga">
    <div id="content">
        <table width="100%">
            <tbody>
            <tr>
                <td id="menucol" valign="top">
                    <div id="actions" class="actions column">
                        <!-- INCLUDESCRIPT BLOCK : imenu -->
                    </div>
                </td>
                <td valign="top" id="vaultcol">
                    <div id="usercoldiv" class="usercolvisible" name="usercol"></div>
                    <table id="tabstable">
                        <tbody>
                        <tr>
                            <td colspan="2" id="tdtabrow"></td>
                        </tr>
                        <tr id="treerow">
                            <td valign="top" colspan="2">
                                <!-- I: CONTENIDO PRINCIPAL -->
                                <div id="treepane" tabindex="100">
                                    <!-- INCLUDESCRIPT BLOCK : isessioninfo -->
                                    <div class="demo tree tree-default" id="thetree" tabindex="100">
                                        <div id="ffframe-large">
                                            <div id="ffheader">
                                                <span class="page_title">Cambio de contraseña</span>

                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>

                                            </div>
                                            <form name="frmPin" id="frmPin" method=POST onSubmit="vIngresaPalabra(); checkIt(null,null,null);">
                                                <div id="ffbody">
                                                    <div id="page_content" class="page_content">
                                                        <div class="ffpad fftop">
                                                            <div class="clear"></div>
                                                            <div id="headerrow2"></div>
                                                        </div>
                                                        <div id="ff_content">
                                                            <div class="ff_pane" style="display: block;">
                                                                {Mensaje}
                                                                <table class="fffields"  cellspacing="0" >
                                                                    <thead>
                                                                    </thead>
                                                                    <tbody>
                                                                    <!--
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            Código de  Verificaci&oacute;n
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <img name="id_img_captcharecov" src="" width="100" height="30" vspace="3">
                                                                            <input type="hidden" name="id_hid_captcharecov" id="id_hid_captcharecov"  size="30">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">Ingrese el codigo de  Verificaci&oacute;n</td>
                                                                        <td class="page_col2">
                                                                            <input type="text" name="id_captcharecov" class="easyui-validatebox"  required="true" id="id_captcharecov"  size="45">
                                                                        </td>
                                                                    </tr>
                                                                    -->
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            Contrase&ntilde;a Actual
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <input name="txtPalabraClave" id="txtPalabraClave" type="hidden" autocomplete="off" value="{PalabraClave}" maxlength="20" size="20">
                                                                            <input name='txtContrasenia' type='password' value='{Contrasenia}' maxlength=16 size=16
                                                                                   onKeypress="if (!(event.keyCode==33||event.keyCode==35||event.keyCode==42||event.keyCode==45||event.keyCode==46||event.keyCode==95||event.keyCode==173||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123))) event.returnValue = false;"
                                                                                   onKeydown="funDoFocus(false,false,13,document.frmPin.txtContraseniaN,true);"
                                                                                   onChange = "document.frmPin.Dato7.value = 1;">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            Contrase&ntilde;a Nueva
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <input name='txtContraseniaN' type='password' value='' maxlength=16 size=16
                                                                                   onKeypress="if (!(event.keyCode==33||event.keyCode==35||event.keyCode==42||event.keyCode==45||event.keyCode==46||event.keyCode==95||event.keyCode==173||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123))) event.returnValue = false;"
                                                                                   onKeydown="funDoFocus(false,false,13,document.frmPin.txtContraseniaC,true);"
                                                                                   onChange = "document.frmPin.Dato8.value = 1;">
                                                                            <font size='1'>  Entre 4 y 16 caracteres.   </font>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="page_col1">
                                                                            Repetir la Contrase&ntilde;a
                                                                        </td>
                                                                        <td class="page_col2">
                                                                            <input name='txtContraseniaC' type='password' value='' maxlength=16 size=16
                                                                                   onKeypress="if (!(event.keyCode==33||event.keyCode==35||event.keyCode==42||event.keyCode==45||event.keyCode==46||event.keyCode==95||event.keyCode==173||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123))) event.returnValue = false;"
                                                                                   onblur = "vContrasenia(this.form);"
                                                                                   onChange = "document.frmPin.Dato9.value = 1;">
                                                                            <font size='1'>  Permite letras y n&uacute;meros.   </font>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="buttons">
                                                        <input type=hidden name="Dato6" value="1">
                                                        <input type=hidden name="Dato7">
                                                        <input type=hidden name="Dato8">
                                                        <input type=hidden name="Dato9">
                                                        <input type=hidden name="Dato10" value="1">
                                                        <input type=hidden name="Dato11">
                                                        <input type=hidden name="Dato12">
                                                        <input type=hidden name="btnModPIN">
                                                        <input type="submit" name="btnSubmit" id="btnSubmit"
                                                               value="Modificar contraseña"
                                                               class="nbtn rbtn btn_midi btn_exp_h okbutton">
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="contenido-pie"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- PIE -->
<!-- INCLUDESCRIPT BLOCK : ifooter -->
</body>
</html>