<div name="pg_register">
    <table align="center">
        <tr>
            <td><img src=".././resources/images/user_48.png" alt="Usuarios"></td>
            <td><h2>Registrarse</h2></td>
        </tr>
    </table>
    <br/>

    <form method="post" id="fm_register" action="">

        <div id="panelm1" class="easyui-panel" title="Datos de acceso" style="background:#fafafa;">
            <br/>
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                <tr>
                    <td width="35%" align="left"><label for="id_mail">* Correo Electr&oacute;nico: </label></td>
                    <td width="65%"><input type="text" name="id_mail" id="id_mail" class="easyui-validatebox"
                                           required="true" validType="email" size="30"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_password">* Elige una contrase&ntilde;a: </label></td>
                    <td width="65%"><input type="password" name="id_password" id="id_password"
                                           class="easyui-validatebox" required="true" size="30"
                                           validType="minLength[5]"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_pass_comp">* Vuelve a introducir la
                            contrase&ntilde;a: </label></td>
                    <td width="65%"><input type="password" name="id_pass_comp" id="id_pass_comp"
                                           class="easyui-validatebox" required="true" size="30"
                                           validType="txt_valid_pass"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_captcha"> Verificaci&oacute;n de seguridad: </label>
                    </td>
                    <td width="65%" align="left"><img name="id_img_captcha" src="" width="100" height="30"
                                                      vspace="3"><br></td>
                </tr>
                <tr>
                    <td width="35%" align="left">Ingrese el codigo de Verificaci&oacute;n que esta arriba:</td>
                    <td width="65%"><input type="text" name="id_captcha" id="id_captcha" size="30"><br></td>
                </tr>
                <tr>
                    <td width="35%" align="left"></td>
                    <td width="65%"><input type="hidden" name="id_hid_captcha" id="id_hid_captcha" size="30"><br></td>
                </tr>
            </table>
        </div>
        <br/>

        <div id="panelm2" class="easyui-panel" title="Datos Personales" style="background:#fafafa;">
            <br/>
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                <tr>
                    <td width="35%" align="left"><label for="id_id">DPI: </label></td>
                    <td width="65%"><input type="text" name="id_id" id="id_id" size="30"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Nota:</b> Su nombre sera utilizado para la impresi&oacute;n del diploma.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <i>No nos hacemos responsables de nombres mal escritos en el diploma impreso. </i>
                    </td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_name">* Nombres: </label></td>
                    <td width="65%"><input type="text" name="id_name" id="id_name" class="easyui-validatebox"
                                           required="true" size="50"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_surname">* Apellidos: </label></td>
                    <td width="65%"><input type="text" name="id_surname" id="id_surname" class="easyui-validatebox"
                                           required="true" size="50"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_address">* Direcci&oacute;n: </label></td>
                    <td width="65%"><input type="text" name="id_address" id="id_address" class="easyui-validatebox"
                                           required="true" size="70"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_gender">* Genero: </label><br/></td>
                    <td width="65%">
                        <select id="id_gender" name="id_gender" style="width:150px;" class="easyui-combobox"
                                required="true" editable="false" panelheight="50">
                            <option value="0">Masculino</option>
                            <option value="1">Femenino</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_school">* Escolaridad: </label>

                        <P></td>
                    <td width="65%"><input id="id_school" name="id_school" style="width:150px" required="true"
                                           url=".././fw/view/Service.php?service=getschool" valueField="id"
                                           textField="text" panelheight="130px"></input></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_birthdate">* Fecha de Nacimiento: </label>

                        <P></td>
                    <td width="65%"><input id="id_birthdate" name="id_birthdate" style="width:150px;"
                                           class="easyui-datebox" required="true" size="50" editable="false"></input>

                        <P></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_phone">Telefono: </label></td>
                    <td width="65%"><input type="text" name="id_phone" id="id_phone" size="10" type="text"
                                           class="easyui-validatebox" validType="minLength[8]"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_celular">Celular: </label>

                        <P></td>
                    <td width="65%"><input type="text" name="id_celular" id="id_celular" size="10" type="text"
                                           class="easyui-validatebox" validType="minLength[8]"/>

                        <P></td>
                </tr>
            </table>
        </div>
        <br/>

        <div id="panelm3" class="easyui-panel" title="Datos de la Instituci&oacute;n" collapsible="true"
             style="background:#fafafa;">
            <br/>
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                <tr>
                    <td width="35%" align="left"><label for="id_carnet">Carn&eacute;: </label><br/></td>
                    <td width="65%"><input name="id_carnet" id="id_carnet" class="easyui-numberbox" type="text"
                                           precision="0"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_unity">Unidad: </label></td>
                    <td width="65%"><input type="text" name="id_unity" id="id_unity" size="10" type="text"
                                           class="easyui-validatebox" validType="minLength[2]"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_extention">Extensi&oacute;n: </label></td>
                    <td width="65%"><input type="text" name="id_extention" id="id_extention" size="10" type="text"
                                           class="easyui-validatebox" validType="minLength[2]"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_career">Carrera: </label>

                        <P></td>
                    <td width="65%"><input type="text" name="id_career" id="id_career" size="10" type="text"
                                           class="easyui-validatebox" validType="minLength[2]"/>

                        <P></td>
                </tr>
            </table>
        </div>
        <table align="center">
            <tr>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser();">Registar</a></td>
                <td><a href="#" class="easyui-linkbutton" iconCls="icon-back" onclick="cancel();">Regresar</a></td>
            </tr>
        </table>
    </form>
</div>
