<div name="pg_recover">
    <h2>Recuperar contrase&ntilde;a </h2>

    <form method="post" id="fm_recover" action="">


        <div id="panelm4" class="easyui-panel" title="Ingrese su Correo Electr&oacute;nico" collapsible="true"
             style="background:#fafafa;">
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
                <tr>
                    <td width="35%" align="left"><label for="txt_user">Usuario: </label>

                        <P></td>
                    <td width="65%"><input type="text" name="id_mailrecov" id="id_mailrecov" class="easyui-validatebox"
                                           required="true" validType="email" size="45"/></td>
                </tr>
                <tr>
                    <td width="35%" align="left"><label for="id_captcharecov">* Verificaci&oacute;n de
                            seguridad: </label></td>
                    <td width="65%" align="left"><img name="id_img_captcharecov" src="" width="100" height="30"
                                                      vspace="3"><br></td>
                </tr>
                <tr>
                    <td width="35%" align="left">Ingrese el codigo de Verificaci&oacute;n que esta arriba:</td>
                    <td width="65%"><input type="text" name="id_captcharecov" class="easyui-validatebox" required="true"
                                           id="id_captcharecov" size="45"><br></td>
                </tr>
                <tr>
                    <td width="35%" align="left"></td>
                    <td width="65%"><input type="hidden" name="id_hid_captcharecov" id="id_hid_captcharecov"
                                           size="30"><br></td>
                </tr>

            </table>
            <table align="center">
                <tr>
                    <td><a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="recover();">Enviar</a></td>
                    <td><a href="#" class="easyui-linkbutton" iconCls="icon-back" onclick="cancel();">Regresar</a></td>
                </tr>
            </table>
        </div>
    </form>
</div>				
