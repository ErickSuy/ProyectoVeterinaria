$(function () {
    var ColumnaName=null;//actual columna    
    var ColumnNames=null;//array
    var indexActual=-1;        
   
   
   $('#dgcursos').edatagrid({ /*propiedades de dataGrid*/
        pagination: true,
        rownumbers: true,
        nowrap: true,
        striped: false,
        singleSelect: true,
        url: 'crearActividadFunciones.php',
        queryParams: {
            llamar: 'getNotasJson'
        },
        rowStyler: function (index, row) {
            if (index % 2 == 0) {
                return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
            } else {
                return 'color: #3d3d3d;text-transform: capitalize;font-weight: lighter;';
            }
        },
        onClickRow: function (rowIndex) {
            $('#dgcursos').datagrid('endEdit', indexActual);
            indexActual = rowIndex;
        }, onClickCell: function (index, field, value) {
            ColumnaName = field;
        },
        onLoadSuccess: function (data) {
            
            getCambios();
            
            var opts = $(this).datagrid('getColumnFields');
            opts = "" + JSON.stringify(opts);
            opts = getColumnName(opts);
            ColumnNames = opts;

        },
        onLoadError: function (data, param) {
           // alert("error " + data);
        }
    });

    $("#dgcursos").datagrid('disableFilter');
    $("#dgcursos").datagrid('enableFilter', [{
            field: 'section',
            type: 'label'
        }]);
    /*---------------------- paginacion control*/

   var p = $("#dgcursos").datagrid('getPager');
   //Set pagination components parameters
   $(p).click(function() {
       getCambios();
   });
          
    //------------------- fin*/
    
    /*------------------------------------------ TABS*/
   $("#tabDetalle").tabs({tabPosition: 'left'});
    
    // Eventos de Keyboard.
    $('#dgcursos').datagrid('getPanel').panel('panel').attr('tabindex',1).bind('keydown',function(e){
	switch(e.keyCode){
		case 38:	// up
			var selected = $('#dgcursos').datagrid('getSelected');
			if (selected){
                            var index = $('#dgcursos').datagrid('getRowIndex', selected);
                            $('#dgcursos').datagrid('selectRow', index-1);
                            $('#dgcursos').datagrid('endEdit', index);
                            $("#dgcursos").datagrid('beginEdit', index - 1);
                           if(ColumnaName!=null){
                               $("#dgcursos").datagrid('editCell', {index: index - 1,field: ColumnaName});
                               var ed = $("#dgcursos").datagrid('getEditor', {index:index - 1,field:ColumnaName});
                               $(ed.target).numberbox('setValue', '');
                           }else{
                               var field ="'"+ColumnNames[0]+"'";
                               $("#dgcursos").datagrid('editCell', {index: index - 1,field: field});
                               var ed = $("#dgcursos").datagrid('getEditor', {index:index - 1,field:field});
                               $(ed.target).numberbox('setValue', '');
                           }                                
                           indexActual=index-1;
                        } else {
                            $('#dgcursos').datagrid('selectRow', 0);
                            indexActual=0;
                        }
                        break;
		case 40:	// down
			var selected = $('#dgcursos').datagrid('getSelected');
			if (selected){
                            var index = $('#dgcursos').datagrid('getRowIndex', selected);
                            $('#dgcursos').datagrid('selectRow', index + 1);
                            $('#dgcursos').datagrid('endEdit', index);
                            $("#dgcursos").datagrid('beginEdit', index + 1);
                            if (ColumnaName != null) {
                                $("#dgcursos").datagrid('editCell', {index: index + 1, field: ColumnaName});
                                var ed = $("#dgcursos").datagrid('getEditor', {index:index + 1,field:ColumnaName});
                               $(ed.target).numberbox('setValue', '');
                            } else {
                                var field = "'" + ColumnNames[0] + "'";
                                $("#dgcursos").datagrid('editCell', {index: index + 1, field: field});
                                var ed = $("#dgcursos").datagrid('getEditor', {index:index + 1,field:field});
                               $(ed.target).numberbox('setValue', '');
                            }
                            indexActual=index+1;
			} else {
				$('#dgcursos').datagrid('selectRow', 0);
                                indexActual=0;
			}
			break;
                case 13:	// down enter
			var selected = $('#dgcursos').datagrid('getSelected');
			if (selected){
                            var index = $('#dgcursos').datagrid('getRowIndex', selected);
                            $('#dgcursos').datagrid('selectRow', index + 1);
                            $('#dgcursos').datagrid('endEdit', index);
                            $("#dgcursos").datagrid('beginEdit', index + 1);
                            if (ColumnaName != null) {
                                $("#dgcursos").datagrid('editCell', {index: index + 1, field: ColumnaName});
                                var ed = $("#dgcursos").datagrid('getEditor', {index:index + 1,field:ColumnaName});
                               $(ed.target).numberbox('setValue', '');
                            } else {
                                var field = "'" + ColumnNames[0] + "'";
                                $("#dgcursos").datagrid('editCell', {index: index + 1, field: field});
                                var ed = $("#dgcursos").datagrid('getEditor', {index:index + 1,field:field});
                               $(ed.target).numberbox('setValue', '');
                            }
                            indexActual=index+1;
			} else {
				$('#dgcursos').datagrid('selectRow', 0);
                                indexActual=0;
			}
			break;
	}
});


    
    
});
/*------------------------------------------ FORMATO DE FECHA*/
function myformatter(date) {
    var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
    }
    function myparser(s) {
        if (!s)
            return new Date();
        var ss = (s.split('/'));
        var y = parseInt(ss[2], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[0], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }
    
function getCambios() {
    /* funcion encargada de ver que cambios se realizaron, y si existiese
     * agregar esos cambios en la base de datos.*/
    var cambios;  
    var rows = $('#dgcursos').datagrid('getRows');
    for ( var i = 0; i < rows.length; i++) {
       $('#dgcursos').datagrid('endEdit', i);
    }  
    
    var rows = $('#dgcursos').datagrid('getChanges', 'updated');
    if(rows.length>0){
        cambios = JSON.stringify(rows);
        $.ajax({
        type: "POST",
        url: "crearActividadFunciones.php",
        data:{"llamar":"updateNotas","cambios":cambios},
        beforeSend: function(){
        },
        error: function(data){
            $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Edicion de Notas Error</h4>'+data+'</div>');            
        },
        success: function(data){
            //$("#printTest").text(data);
            if(data.toLowerCase().indexOf("error") >= 0){
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Edicion de Notas </h4>'+data+'</div>');
            }else{
                $("#mensajeAlert").html('<div></div>');
            }                
	    $('#dgcursos').datagrid('acceptChanges');
            $('#dgcursos').datagrid('reload');
        }
        });
    }
}

function getColumnName(ArrayJasonColumnNames){
    // Funcion que convierte un arrayJson en un array Javascript.
    var posInicio= ArrayJasonColumnNames.lastIndexOf("\"[");
    var posFin= ArrayJasonColumnNames.lastIndexOf("]\"");
    var ColumnField = ArrayJasonColumnNames.substring(posInicio+2, posFin);
    ColumnField = ColumnField.replace(/\\"/g,"");
    ArrayJasonColumnNames = ColumnField.split(",");
    return ArrayJasonColumnNames;
}

function pruebagetJson(){
    $.ajax({
        type: "POST",
        url: "crearActividadFunciones.php",
        data: {"llamar":'getNotasJson'},
        beforeSend: function () {
        },
        error: function (data) {
            //alert(data);
            $("#printest").html(data);
            //alert(JSON.stringify(data));
            //$("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad Error </h4>' + data + '</div>');
        },
        success: function (data) {
            $("#printest").html(data);
            /*if (data.indexOf("¿Desea crearla?") != -1) {
                var r = confirm(data);
                if (r == true) {
                    enviarDatosActividad(true,actualizacion);
                }
            }else{
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad </h4>' + data + '</div>');
            }*/
        }
    });
}

//--------------------------------------------- FUNCION CREAR ACTIVIDAD
    //---** a continuacion se encuentran una serie de metodos necesarios para 
    //---** poder llevara cabo la opcion de crear actividad

function validaPerteneceA() {

    var tipoactividadseleccionada = $('#txtTipoActividad').val();
    //alert(tipoactividadseleccionada);
    
    if ((tipoactividadseleccionada > 0) && (tipoactividadseleccionada < 6)) {
        $("input[name=txtPerteneceA][value=" + tipoactividadseleccionada + "]").prop('checked', true);
    }

    if (tipoactividadseleccionada == 5) {
         $('#txtPonderacion').val()
    }

} // de validaPerteneceA

function ValidaFecha() {
        var isValid = true;
        if($('#txtFechaRealizar').datebox('getValue') == '') {
            isValid = false;
            $('#msg_fecha').html('*Este dato es obligatorio').show();
        } else  {
            
            $('#msg_fecha').html('').hide();
        }
        return isValid;
}

function ValidaRadio() {
    var radio_choice = false;
    $("input[name=txtPerteneceA]").each(function (index) {  
       if($(this).is(':checked')){
          radio_choice =true;
       }
    });    

    return (radio_choice);
} // de la funcion que valida los radios
    
function crearActividad(actualizar){
    
    var punteo;
    
    var longitudNombre =$('#txtNombreActividad').val().length;
    if(longitudNombre<=0 || longitudNombre >60){        
        $('#msg_nombre').html('*Debe especificar un nombre válido con longitud menor a 60').show();
        $('#txtNombreActividad').select();
        $('#txtNombreActividad').focus();
        return false;
    } else {
        $('#msg_nombre').html('').hide();
    }
    
    if ($('#txtTipoActividad').val() == 0) {
        alert("Indique el tipo de actividad. . . ");
        $('#txtTipoActividad').select();
        $('#txtTipoActividad').focus();
        return false;
    }
    
    if (ValidaFecha() == false) {
            $('#txtFechaRealizar').select();
            $('#txtFechaRealizar').focus();
            return false;
    }
    
    if (ValidaRadio() == false) {
        alert("No es una selecci&oacute;n valida para el tipo de docencia. . . ");
        $('#txtPerteneceA').focus();
        return false;
    }
    var n =$('#txtPonderacion').val();
    var pts = roundNumber(n, 2);
    punteo = pts * 1;
    if (isNaN(pts) || (pts === "")) { 
        $('#msg_ponderacion').html('*Debe especificar un dato válido').show();
        $('#txtPonderacion').select();
        $('#txtPonderacion').focus();
        return false;
    } else {
        $('#msg_ponderacion').html('').hide();
    }
    if (punteo === 0 || punteo < 0) {
        alert("Solo se permiten ponderaciones mayores a cero . . . ");
        $('#txtPonderacion').select();
        $('#txtPonderacion').focus();
        return false;
    }
    
    if($('#txtTipoActividad').val()!=5){
        enviarDatosActividad(false,actualizar);
    }else{
        if(actualizar){
            var r = confirm("Esta Actividad no permite ser actualizada, si usted desea \n\
            puede eliminarla del listado de actividades");
        }else{
            enviarDatosActividadReposicion(false,false);
        }
        
    }
    
    
}

function roundNumber(num, scale) {
    var number = Math.round(num * Math.pow(10, scale)) / Math.pow(10, scale);
    if (num - number > 0) {
        return (number + Math.floor(2 * Math.round((num - number) * Math.pow(10, (scale + 1))) / 10) / Math.pow(10, scale));
    } else {
        return number;
    }
}

function enviarDatosActividadReposicion(crearla,actualizacion){
    //alert(actualizacion);
    $.ajax({
        type: "POST",
        url: "crearActividadFunciones.php",
        data: $('#IngresaActividad').serialize() + "&llamar=agregarParcialReposicion&crearla="+crearla+"&actualizacion="+actualizacion,
        beforeSend: function () {
        },
        error: function (data) {
            //alert(JSON.stringify(data));
            if(actualizacion){
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Modificacion de Actividad Error </h4>' + data + '</div>');
            }else{
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad Error </h4>' + data + '</div>');
            }
        },
        success: function (data) {
            if (data.indexOf("¿Desea crearla?") != -1) {
                var r = confirm(data);
                if (r == true) {
                    enviarDatosActividad(true,actualizacion);
                }
            }else{
                if(actualizacion){
                    $("#mensajeAlert").html('<div class="alert alert-info"><h4><i class="fa fa-info-circle fa-lg"></i> Modificacion de Actividad </h4>' + data + '</div>');
                }else{
                    $("#mensajeAlert").html('<div class="alert alert-info"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad </h4>' + data + '</div>');
                }
                
            }
        }
    });
}

function enviarDatosActividad(crearla,actualizacion){
    //alert(actualizacion);
    $.ajax({
        type: "POST",
        url: "crearActividadFunciones.php",
        data: $('#IngresaActividad').serialize() + "&llamar=agregarActividad&crearla="+crearla+"&actualizacion="+actualizacion,
        beforeSend: function () {
        },
        error: function (data) {
            if(actualizacion){
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Modificacion de Actividad Error </h4>' + data + '</div>');
            }else
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad Error </h4>' + data + '</div>');
        },
        success: function (data) {
            if (data.indexOf("¿Desea crearla?") != -1) {
                var r = confirm(data);
                if (r == true) {
                    enviarDatosActividad(true,actualizacion);
                }
            }else{
                if (data.toLowerCase().indexOf("exito") >= 0){
                    if(actualizacion){
                        $("#mensajeAlert").html('<div class="alert alert-info"><h4><i class="fa fa-info-circle fa-lg"></i> Modificacion de Actividad </h4>' + data + '</div>');
                    }else{
                        $("#mensajeAlert").html('<div class="alert alert-info"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad </h4>' + data + '</div>');
                    }
                }else{
                    if(actualizacion){
                        $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Modificacion de Actividad </h4>' + data + '</div>');
                    }else{
                        $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Creacion de Actividad </h4>' + data + '</div>');
                    }
                }
                
            }
        }
    });
}

function borrarActividad(el) {
    var r = confirm("Realmente desea borrar la actividad?\n* esta accion no se puede deshacer");
    if (r == true) {
        var id = $(el).attr('value');
        var recargar = $(el).attr('text');
        $.ajax({
            type: "POST",
            url: "crearActividadFunciones.php",
            data: {"llamar": "eliminarActividad", "idactividad": id},
            beforeSend: function () {
            },
            error: function (data) {
                $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Eliminacion de Actividad Error </h4>' + data + '</div>');
            },
            success: function (data) {
                if(data>0){
                    window.location.href = recargar;                    
                }else{
                    $("#mensajeAlert").html('<div class="alert alert-danger"><h4><i class="fa fa-info-circle fa-lg"></i> Eliminacion de Actividad </h4>Sucedio un error al intentar eliminar la Activiad, reintente la accion asegurese que efectivamente esa actividad tiene permiso para ser eliminada.</div>');
                }
                
            }
        });
    }

}

 $("#dialog").dialog(
         {
            bgiframe: true,
            autoOpen: false,
            height: 250,
            modal: true,
            //hide: "fadeOut",
            buttons:
            {
               Exit: function()
               {
                  $(this).dialog('close');
               },
               Invia: function()
               {
                   // some operations
                  
                    //setTimeout($(this).dialog('close'), 5000);

                   //$('#dialog').delay(5000).fadeOut(400);
                   setTimeout(function()
                    {
                   
                       $(this).dialog('close');
                   
                    }, 10000);

               }
            }
         });

