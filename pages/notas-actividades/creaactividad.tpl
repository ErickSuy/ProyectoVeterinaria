<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="must-revalidate">
    <meta http-equiv="Cache-Control" content="post-check=0">
    <meta http-equiv="Cache-Control" content="pre-check=0">
    <!-- INCLUDESCRIPT BLOCK : ihead -->

    <script language='javascript'>
        function salida() {
            alert("Se esta cerrando!!!");
            window.open('../LogOut.php');
        }
    </script>
    <script type="text/javascript">
        function myformatter(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d) + '/' + (m<10?('0'+m):m) + '/' + y;
        }
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('/'));
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    </script>

    <script language="javascript">
    function redireccionar(nuevadireccion) {
        location.href = nuevadireccion;
    }

    function AlInicio() {
        direccion = "..\menu\D_CourseList.php";
        redireccionar(direccion);
    }

    function validaPerteneceA() {

        var tipoactividadseleccionada = document.IngresaActividad.txtTipoActividad.value;

        if ((tipoactividadseleccionada > 0) && (tipoactividadseleccionada < 6)) {
            document.IngresaActividad.txtPerteneceA[0].checked = "cheked";
        }

        if (tipoactividadseleccionada == 5) {
            document.IngresaActividad.txtPonderacion.value = 0;
        }

    } // de validaPerteneceA


    function compararfecha(Fecha1, Fecha2) {
        var str1 = Fecha1;
        var str2 = Fecha2;
        var dt1 = parseInt(str1.substring(0, 2), 10);
        var mon1 = parseInt(str1.substring(3, 5), 10);
        var yr1 = parseInt(str1.substring(6, 10), 10);
        var dt2 = parseInt(str2.substring(0, 2), 10);
        var mon2 = parseInt(str2.substring(3, 5), 10);
        var yr2 = parseInt(str2.substring(6, 10), 10);
        var date1 = new Date(yr1, mon1 - 1, dt1);
        var date2 = new Date(yr2, mon2 - 1, dt2);
        if (date1 >= date2) {
            //alert("Fecha 1: "+(dt1 + '-' + mon1 + '-' + yr1)+" mayor que Fecha 2: "+(dt2 + '-' + mon2 + '-' + yr2));
            return 1;
        }
        else {
            if (date1 < date2) {
                //alert("Fecha 2: "+(dt2 + '-' + mon2 + '-' + yr2)+" mayor que Fecha 1: "+(dt1 + '-' + mon1 + '-' + yr1));
                return -1;
            }
        }
//alert ("SON IGUALES");
        return 0;
    }// de la funcion para comaprarFecha


    function FechaEntreRango(FechaInicial, FechaFinal, Fecha) {
        var comparacion1 = compararfecha(Fecha, FechaInicial);
        var comparacion2 = compararfecha(FechaFinal, Fecha);
        if (((comparacion1 == 1) && (comparacion2 == 1))) {
            //alert( "EUREKA esta en rango");
            return 1;
        }
        else {
            //alert (" este FUERA de rango");
            return 0;
        }
    }
    // /////////////////////////////////////////////// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    function validaPerteneceA() {
        tipoactividadseleccionada = document.IngresaActividad.txtTipoActividad.value;
//	alert(tipoactividadseleccionada);
        if ((tipoactividadseleccionada == 1) || (tipoactividadseleccionada == 2) || (tipoactividadseleccionada == 3) || (tipoactividadseleccionada == 4) || (tipoactividadseleccionada == 5)) {
            document.IngresaActividad.txtPerteneceA[0].checked = "cheked";
        }
    } // de validaPerteneceA


    function BuscarActividadXFecha(fechabuscar) {

        var VecNombre = new Array();
        var VecFecha = new Array();
        var VecPerteneceA = new Array();
        var TotalActividades;
        <!-- START BLOCK : iniciovectoractividades -->
        TotalActividades ={totalactividades};
        <!-- START BLOCK : valorvector -->
        VecNombre[{posicion}] = '{datoNombre}';
        VecFecha[{posicion}] = '{datoFecha}';
        VecPerteneceA[{posicion}] ={datoPerteneceA};
        <!-- END BLOCK : valorvector -->
        <!-- END BLOCK : iniciovectoractividades -->

        RecorreActividades = 0;
//alert(fechabuscar)
        while (RecorreActividades < TotalActividades) {
//   alert(VecFecha[RecorreActividades]);
            if (fechabuscar == VecFecha[RecorreActividades]) {
                if (!confirm("ya existe una actividad en esta fecha, desea agregarla de todas formas?"))
                    return false;
                //RecorreActividades++;
            }
            RecorreActividades++;
        }

        return true;
    }

    function ValidaValorAcumuladoZona(PonderacionMaxima, PonderacionAcumulada) {
        if (PonderacionAcumulada > PonderacionMaxima) {
            alert("La suma de actividades no debe de exceder " + PonderacionMaxima + " puntos netos de zona. . . . (Artículo 11º. del normativo de la FMVZ)");
            return false;
        }// sumaparciales + ponderacion >0

        return true;
    }

    // Funcion que valida la fecha de los parciales
    function ValidaFechaParciales() {
        var FechaPrimerParcial;
        var PonderacionPrimerParcial;
        var FechaSegundoParcial;
        var PonderacionSegundoParcial;
        var SumaParciales;
        var EsActualizacion = 0;
        var FinalPrimerParcial;
        var FinalSefundoParcial;
        var FinalTercerParcial;
        var InicioCiclo;
        var FinalCiclo;
        var SumaPonderacionActividades;
        var FechaParcialReposicion;
        var TopeLaboratorio;
        var SumaLaboratorio;
        var TipoCurso;
//alert (" VALIDAR FECHAS PARCIALES");

        <!-- START BLOCK : datosparciales -->
        FechaPrimerParcial ={txtFechaPrimerParcial};
        PonderacionPrimerParcial ={txtPonderacionPrimerParcial};
        FechaSegundoParcial ={txtFechaSegundoParcial};
        PonderacionSegundoParcial ={txtPonderacionSegundoParcial};
        FechaTercerParcial ={txtFechaTercerParcial};
        PonderacionTercerParcial ={txtPonderacionTercerParcial};
        SumaParciales ={txtSumaParciales};
        FinalPrimerParcial ={txtFinalPrimerParcial};
        FinalSegundoParcial ={txtFinalSegundoParcial};
        FinalTercerParcial ={txtFinalTercerParcial};
        InicioPeriodo ={txtInicioPeriodo};
        FinalPeriodo ={txtFinalPeriodo};
        SumaPonderacionActividades ={txtSumaPonderacionActividades};
        PonderacionMaxima ={txtPonderacionMaxima};
        FechaParcialReposicion ={txtFechaParcialReposicion};
        TopeLaboratorio ={txtTopeLaboratorio};
        SumaLaboratorio ={txtSumaLaboratorio};
        TipoCurso = {txtTipoCurso};

        if(TipoCurso==1) {
            PonderacionMaxima = 70; //Zona Máxima Introductorio y Básico
        } else {
            PonderacionMaxima = 80; //Zona Máxima Modulares
        }

        <!-- END BLOCK : datosparciales -->
        TipoActividad = document.IngresaActividad.txtTipoActividad.value;
        Ponderacion = document.IngresaActividad.txtPonderacion.value * 1;
        FechaRealizar = $('#txtFechaRealizar').datebox('getValue');
        PerteneceA = document.IngresaActividad.txtPerteneceA.value;
        EsActualizacion = 0;
        if (document.IngresaActividad.txtIdActividad.value != "") {
            EsActualizacion = 1;
        }

        if ((TipoActividad > 0) && (TipoActividad < 5)) {
            switch (TipoCurso) {
                case 1:
                    if (SumaParciales + Ponderacion > 30) {
                        alert(" La suma de parciales no debe de exceder de 30 puntos netos. . . . (Artículo 12º. del normativo de la FMVZ)");
                        return false;
                    }// sumaparciales + ponderacion >0
                    break;
                case 2:
                    if (SumaParciales + Ponderacion > 40) {
                        alert(" La suma de parciales no debe de exceder de 40 puntos netos. . . . (Artículo 12º. del normativo de la FMVZ)");
                        return false;
                    }// sumaparciales + ponderacion >0
                    break;
            }

        }// if es parcial


        if(ValidaValorAcumuladoZona(PonderacionMaxima,(SumaPonderacionActividades + Ponderacion)) == false) {
            return false;
        }

        /* Edwin Saban. Se dehabilito debido a que solo se clasifican actividades sin darle un punteo maximo de zona a las actividades de tipo laboratorio
        if (document.IngresaActividad.txtPerteneceA[1].checked) {
            if (SumaLaboratorio + Ponderacion > TopeLaboratorio) {
                alert("La suma de laboratorio no debe de exceder de " + TopeLaboratorio + " puntos netos de zona. . . .");
                return false;
            }// comparacion de la suma del laboratorio
        }// verifica que este seleccionado el laboratorio
*/

        if ((InicioPeriodo != -1) && (FinalPeriodo != 1)) {
            if (FechaEntreRango(InicioPeriodo, FinalPeriodo, FechaRealizar) == 0) {
                alert("Las actividades deben ser programadas entre " + InicioPeriodo + " y " + FinalPeriodo + "  (De acuerdo al calendario de labores de la FMVZ)");
                return false;
            }
        }

        if (TipoActividad == 1)  // PrimerParcial
        {
            if ((FechaPrimerParcial != -1) && (EsActualizacion == 0)) {
                alert("Ya ingreso el primer parcial. . . . ");
                return false;
            }

            if (FinalPrimerParcial != -1) {
                if (compararfecha(FinalPrimerParcial, FechaRealizar) == -1) {
                    alert("Ultima fecha para realizar el primer parcial es: " + FinalPrimerParcial + " (Articulo 21. del normativo de la FMVZ)");
                    return false;
                }
            } // existe fecha finalParcial!=-1
            else {
                alert("no existe fechas del periodo. . . ");
            }
        } // DEL IF VALIDAR PRIMER PARCIAL

        if (TipoActividad == 2)   //  SegundoParcial
        {
            if (FechaPrimerParcial == -1) {
                alert("Debe crear primero la actividad de primer parcial y luego el segundo");
                return false;
            }
            if ((FechaSegundoParcial != -1) && (EsActualizacion == 0)) {
                alert("Ya ingreso el segundo parcial. . . . ");
                return false;
            }
//	  alert("Primer Parcial= "+FechaPrimerParcial);
//	  alert ("ingresar: "+document.IngresaActividad.txtFechaRealizar.value);
            if (compararfecha(FechaPrimerParcial, FechaRealizar) == 1) {
                alert("El segundo parcial debe ser asignado despues de la fecha del primer parcial que es: " + FechaPrimerParcial + " (Articulo 21. del normativo de la FMVZ)");
                return false;
            }
            if (FinalSegundoParcial != -1) {
                if (compararfecha(FinalSegundoParcial, FechaRealizar) == -1) {
                    alert("La ultima fecha para realizar el segundo parcial es: " + FinalSegundoParcial);
                    return false;
                }
            } // existe fecha finalParcial!=-1
            else {
                alert("no existe fechas del periodo. . . ");
            }
        } // DEL IF VALIDAR SEGUNDO PARCIAL

        if (TipoActividad == 3) // Tercer Parcial
        {
            if (FechaPrimerParcial == -1) {
                alert("Debe crear el primer parcial y luego el segundo . . . .");
                return false;
            }
            if (FechaSegundoParcial == -1) {
                alert("Debe crear primero la actividad de segundo parcial y luego los demas parciales");
                return false;
            }
            if ((FechaTercerParcial != -1) && (EsActualizacion == 0)) {
                alert("Ya ingreso el tercer parcial. . . . ");
                return false;
            }
            if (compararfecha(FechaSegundoParcial, FechaRealizar) == 1) {
                alert("El tercer parcial debe ser asignado despues de la fecha del segundo parcial que es: " + FechaSegundoParcial + " (Articulo 21. del normativo de la FMVZ)");
                return false;
            }

            if (FinalTercerParcial != -1) {
                if (compararfecha(FinalTercerParcial, FechaRealizar) == -1) {
                    alert("Ultima fecha para realizar el tercer parcial es: " + FinalTercerParcial + "(Articulo 21. del normativo de la FMVZ");
                    return false;
                }
            } // existe fecha finalParcial!=-1
            else {
                alert("no existe fechas del periodo. . . ");
            }


        } // fin de valida tercer parcial. . . . .

        if (TipoActividad == 4) // Otro Parcial
        {
            if (FechaPrimerParcial == -1) {
                alert("Debe crear el primer parcial y luego el segundo . . . .");
                return false;
            }
            if (FechaSegundoParcial == -1) {
                alert("Debe crear primero el segundo parcial y luego el tercero");
                return false;
            }
            if (FechaTercerParcial == -1) {
                alert("Debe crear el tercer parcial y luego los siguientes . . . .");
                return false;
            }
            if (compararfecha(FechaTercerParcial, FechaRealizar) == 1) {
                alert("Deben ser asignados despues de la fecha del tercer parcial que es: " + FechaTercerParcial);
                return false;
            }
        }// DEL IF QUE VALIDA LOS OTROS PARCIALES. . .  . .

        if (TipoActividad == 5) {
            if ((FechaParcialReposicion != -1) && (EsActualizacion == 0)) {
                alert("Ya ingreso el parcial de reposici&oacute;n . . . . ");
                return false;
            }
            if (FechaPrimerParcial == -1) {
                alert("Debe  crear el primer parcial antes del parcial de reposici&oacute;n. . . ");
                return false;
            }

            if (FechaSegundoParcial == -1) {
                alert("Debe crear el segundo parcial antes del parcial de reposici&oacute;n. . . ");
                return false;
            }


        } // del IF valida parcial de reposicion. . . .


//   alert(PonderacionMaxima);
//   alert(Ponderacion);
//   if(PonderacionMaxima>0)
//   {
//     if(PonderacionMaxima>Ponderacion)
//	   {
//	     if(!confirm(" Existen notas ingresadas con valores mayores al nuevo valor de la actividad, si continua debe de modificar las notas ya ingresadas.  Desea continuar.."))
//		 return false;
//	   }
//   }// del if que valida la ponderacion maxima


        return true;
    }  // fin de la funcion que valida la fecha de los parciales
    ////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    // de la funcion que llama para cargarnotas (secciones). .  .
    function CargarNotas(txtCurso, txtSeccion, txtPeriodo, txtAnio, txtIdActividad, txtTipoActividad, txtEsSuperActividad, txtCarrera) {
//   alert("Vino a cargar notas. . . .super=="+txtEsSuperActividad);
        direccion = "manejoarchivoactividades.php?opcion=20&txtIdActividad=" + txtIdActividad + "&txtCurso=" + txtCurso + "&txtSeccion=" + txtSeccion + "&txtPeriodo=" + txtPeriodo + "&txtAnio=" + txtAnio + "&txtTipoActividad=" + txtTipoActividad + "&txtEsSuperActividad=" + txtEsSuperActividad + "&txtCarrera=" + txtCarrera;
//   alert (direccion);
        redireccionar(direccion);

    }
    // de la funcion que llama para asignarsecciones. .  .

    // de la funcion que llama para cargar notas a las actividades
    function AsignarSecciones(txtCurso, txtSeccion, txtPeriodo, txtAnio, txtIdActividad, txtTipoActividad) {
        direccion = "manejoarchivoactividades.php?opcion=1&txtIdActividad=" + txtIdActividad + "&txtCurso=" + txtCurso + "&txtSeccion=" + txtSeccion + "&txtPeriodo=" + txtPeriodo + "&txtAnio=" + txtAnio + "&txtTipoActividad=" + txtTipoActividad;
//   alert (direccion);
        redireccionar(direccion);
    }
    // de la funcion que llama para cargar notas a las actividades


    // INICIO VALIDA FECHA
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
    // FIN VALIDA FECHA


    function ValidaRadio() {
        var radio_choice = false;

        for (counter = 0; counter < document.IngresaActividad.txtPerteneceA.length; counter++) {
            if (document.IngresaActividad.txtPerteneceA[counter].checked)
                radio_choice = true;
        }

        return (radio_choice);
    } // de la funcion que valida los radios


    function ValidaActividad() {
 //alert("VALIDAR ACTIVIDAD");
        var punteo;
        if (ValidaFecha() == false) {
            document.IngresaActividad.txtFechaRealizar.select();
            return false;
        }
        if (!BuscarActividadXFecha($('#txtFechaRealizar').datebox('getValue'))) {
            document.IngresaActividad.txtFechaRealizar.select();
            return false;
        }

        if (isNaN(document.IngresaActividad.txtPonderacion.value) || (document.IngresaActividad.txtPonderacion.value == "")) {
            //alert("No es una ponderación valida. . . ");
            $('#msg_ponderacion').html('*Debe especificar un dato válido').show();
            document.IngresaActividad.txtPonderacion.select();
            document.IngresaActividad.txtPonderacion.focus();
            return false;
        }
        else {
            $('#msg_ponderacion').html('').hide();
        }

        punteo = document.IngresaActividad.txtPonderacion.value * 1;

        if (punteo == 0) {
            if (document.IngresaActividad.txtTipoActividad.value != 5) {
                alert("Solo se permiten ponderaciones mayores a cero . . . ");
                document.IngresaActividad.txtPonderacion.select();
                document.IngresaActividad.txtPonderacion.focus();
                return false;
            }
        }

        if (punteo < 0) {
            alert("Solo se permiten ponderaciones mayores a cero . . . ");
            document.IngresaActividad.txtPonderacion.select();
            document.IngresaActividad.txtPonderacion.focus();
            return false;
        }
        punteo = document.IngresaActividad.txtPonderacion.value;
        if (punteo.indexOf(".") > 0) {
            alert("No se perminte valores con decimales. . . ");
            document.IngresaActividad.txtPonderacion.select();
            document.IngresaActividad.txtPonderacion.focus();
            return false;
        }

        if (ValidaRadio() == false) {
            alert("No es una selecci&oacute;n valida. . . ");
            return false;
        }
        if (document.IngresaActividad.txtTipoActividad.value == 0) {
            alert("Indique el tipo de actividad. . . ");
            document.IngresaActividad.txtTipoActividad.select();
            document.IngresaActividad.txtTipoActividad.focus();
            return false;
        }

        if (document.IngresaActividad.txtTipoActividad.value == 5) {
            //document.IngresaActividad.txtPonderacion.value = 0;
        }

        if (!ValidaFechaParciales()) {
            return false;
        }

        document.IngresaActividad.submit();
    }


    function BorrarActividad(txtIdActividad, txtPosicion, txtCurso, txtSeccion, txtPeriodo, txtAnio,txtCarrera) {
        direccion = "../notas-actividades/creaactividad.php?opcion=5&txtIdActividad=" + txtIdActividad + "&txtPosicion=" + txtPosicion + "&txtCurso=" + txtCurso + "&txtSeccion=" + txtSeccion + "&txtPeriodo=" + txtPeriodo + "&txtAnio=" + txtAnio + "&txtCarrera=" + txtCarrera;
//alert(direccion);
        if (confirm('Si borra esta actividad tambien borrara los datos, desea continuar?'))
            redireccionar(direccion);

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
                                                <span class="page_title">Carga de notas de actividades</span>

                                                <div id="fffonticon">
                                                    <i class="fa fa-sun-o fa-spin fa-lg"></i>
                                                </div>

                                            </div>
                                            <div id="ffbody">
                                                <div id="page_content" class="page_content">
                                                    <div class="ffpad fftop">
                                                        <div class="clear"></div>
                                                        <div id="headerrow2"></div>
                                                    </div>
                                                    <div id="ff_content">
                                                    <div class="ff_pane" style="display: block;">
                                                    <!-- START BLOCK : creaactividad -->
                                                    <form id="IngresaActividad" name="IngresaActividad" method="post" action="creaactividad.php?opcion={Opcion}&despuesopcion={DespuesIrA}">
                                                        <div id="sitebody">
                                                            <br>
                                                            <hr>
                                                            <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                            <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                            <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                            <div class="siterow"><br/><div class="siterow-center"><span>{NombreCuadro}</span></div><br/></div>
                                                            <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                            <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span></div>
                                                            <hr>
                                                            <div id="dynheader" class="restrict_right"></div>
                                                            <div id="dynbody" class="restrict_right ff">
                                                                <div id="notesrow2">
                                                                    <div class="ff_pane" style="display: block;">
                                                                        <table cellspacing="0" class="fffields">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td width="237">&nbsp;</td>
                                                                                <td width="395"><label>
                                                                                        <input name="txtIdActividad" type="hidden" id="txtIdActividad" value="{txtIdActividad}" size="10" />
                                                                                    </label>
                                                                                    <input name="txtCurso" type="hidden" id="" value="{txtCurso}" size="4" />
                                                                                    <label>
                                                                                        <input name="txtSeccion" type="hidden" id="txtSeccion" value="{txtSeccion}" size="3" />
                                                                                        <input name="txtPeriodo" type="hidden" id="txtPeriodo" value="{txtPeriodo}" size="3" />
                                                                                        <input name="txtAnio" type="hidden" id="txtAnio" value="{txtAnio}" size="5" />
                                                                                        <input name="txtRegPer" type="hidden" id="txtRegPer" value="{txtRegPer}" />
                                                                                        <input name="txtCarrera" type="hidden" id="txtCarrera" value="{txtCarrera}" />
                                                                                        <input name="txtIndex" type="hidden" id="txtIndex" value="{txtIndex}" />
                                                                                        <input name="txtEstadoActividad" type="hidden" id="txtEstadoActividad" size="3" maxlength="3" value="{txtEstadoActividad}"/>
                                                                                    </label>&nbsp;</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td class="page_col1">Nombre de la actividad</td>
                                                                                <td class="page_col2">
                                                                                    <input name="txtNombreActividad" type="text" id="txtNombreActividad" value="{txtNombreActividad}" placeholder="Máximo 20 carácteres" maxlength="20" />
                                                                                </td>
                                                                                <td>
                                                                                    <span id="msg_nombre" class="msg-danger-txt"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Tipo de actividad</tI></td>
                                                                                <td class="page_col2">
                                                                                    <!-- START BLOCK : tipoactividad -->
                                                                                    <select name="txtTipoActividad" id="txtTipoActividad" onChange="validaPerteneceA()">
                                                                                        <!-- START BLOCK : opciontipoactividad -->
                                                                                        <option value="{valoropciontipoactividad}" {txtSeleccionado}>{nombreopciontipoactividad}</option>
                                                                                        <!-- START BLOCK : opciontipoactividad -->
                                                                                    </select>
                                                                                    <!-- END BLOCK : tipoactividad -->
                                                                                </td>
                                                                                <td>
                                                                                    <span id="msg_tipo" class="msg-danger-txt"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Fecha de actividad</tI></td>
                                                                                <td class="page_col2">
                                                                                    <input id="txtFechaRealizar" name="txtFechaRealizar" data-options="formatter:myformatter,parser:myparser" style="width: inherit;" class="easyui-datebox" size="15" editable="false" value="{txtFechaRealizar}"/>
                                                                                </td>
                                                                                <td>
                                                                                    <span id="msg_fecha" class="msg-danger-txt"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Docencia directa</tI></td>
                                                                                <td class="page_col2">
                                                                                    <input name="txtPerteneceA" type="radio"  value="1"  {SeleccionaRadioClaseMagistral}  {HabilitadoClaseMagistral} />
                                                                                    Teoria<br />
                                                                                    <input type="radio" name="txtPerteneceA"  value="2" {SeleccionaRadioLaboratorio} {HabilitadoLaboratorio}/>
                                                                                    Práctica<br />
                                                                                </td>
                                                                                <td>
                                                                                    <span id="msg_pertenece" class="msg-danger-txt"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="page_col1">Ponderación</tI></td>
                                                                                <td class="page_col2">
                                                                                    <input name="txtPonderacion" type="text" id="txtPonderacion" value="{txtPonderacion}" size="10" maxlength="5" />
                                                                                </td>
                                                                                <td>
                                                                                    <span id="msg_ponderacion" class="msg-danger-txt"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><div align="right"></div></td>
                                                                                <td><label>
                                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="9000000">
                                                                                        <input name="txtArchivoEnunciado" type="hidden" id="txtArchivoEnunciado" size="40"  />
                                                                                    </label></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <hr>
                                                                <div id="siteactions" class="siterow restrict_right">
                                                                    <a name="btnEnviar" id="btnEnviar" href="javascript:void(0);" onclick="ValidaActividad();" class="easyui-linkbutton icon_text icon ntooltip" style="display: inline-block;">
                                                                        <i class="fa fa-database fa-lg"></i>
                                                                        <span>&nbsp;&nbsp;Grabar Actividad</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <br>
                                                        </div>
                                                    </form>
                                                    <!-- END BLOCK : creaactividad -->

                                                    <!-- START BLOCK : listaactividades -->
                                                    <div id="sitebody">
                                                        <br>
                                                        <hr>
                                                        <div class="siterow"><span class="page_label">UNIVERSIDAD DE SAN CARLOS DE GUATEMALA</span></div>
                                                        <div class="siterow"><span class="page_label">FACULTAD DE MEDICINA VETERINARIA Y ZOOTECNIA</span></div>
                                                        <div class="siterow"><span class="page_label">DEPARTAMENTO DE CONTROL ACADÉMICO</span> <div style="display:inline; margin-left:170px;"></div></div>
                                                        <div class="siterow"><br/><div class="siterow-center"><span>INGRESO DE NOTAS DE ACTIVIDADES</span></div><br/></div>
                                                        <div class="siterow"><span class="page_label">Del curso: </span><span class="underline_label">{vCurso} - {vNombre}</span><span class="page_label">de la carrera: </span><span class="underline_label">{vCarrera}</span></div>
                                                        <div class="siterow"><span class="page_label">Correspondientes a: </span><span class="underline_label">{vPeriodo}</span><span class="page_label">de: </span><span class="underline_label">{vAnio}</span><span class="page_time_label"> Fecha: {vFecha}&nbsp;&nbsp;Hora:{vHora} </span></div>
                                                        <hr>
                                                        <div id="dynheader" class="restrict_right"></div>
                                                        <div id="dynbody" class="restrict_right ff">
                                                            <div id="notesrow2">
                                                                <!-- START BLOCK : tablalistaactividades -->
                                                                <table class='RAsig-table' align='center' width='100%' cellspacing='0' cellpadding='0'>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>FECHA</th>
                                                                        <th>NOMBRE</th>
                                                                        <th>TIPO</th>
                                                                        <th >DOCENCIA DIRECTA</th>
                                                                        <th align="center">PONDERACIÓN</th>
                                                                        <th align="center">ACCIONES</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <!-- START BLOCK : filatablalistaactividades -->
                                                                    <tr>
                                                                        <td>{Contador}&nbsp;</td>
                                                                        <td>{FechaRealizar}&nbsp;</td>
                                                                        <td>{Nombre}&nbsp;
                                                                            <label></label></td>
                                                                        <td>{Tipo}</td>
                                                                        <td>{PerteneceA}</td>
                                                                        <td align="center"><div align="right">{Ponderacion}&nbsp;</div></td>
                                                                        <td align="center">
                                                                            <input style="width: auto !important;" class="nbtn gbtn btn_midi" title="Clic para editar la actividad" name="Editar" type="{botoneditar}"  value="Editar" onclick="javascript:redireccionar('../notas-actividades/creaactividad.php?opcion=3&txtIdActividad={txtIdActividad}');" {BotonHabilitado}>
                                                                            <input style="width: auto !important;" class="nbtn gbtn btn_midi" title="Clic para cargar notas de la actividad mediante archivo" type="{BotonCargarArchivo}" name="CargarArchivo" id="CargarArchivo" value=" Cargar "  onClick="javascript:CargarNotas('{txtCurso}','{txtLaSeccion}','{txtPeriodo}','{txtAnio}',{txtIdActividad},{txtTipoActividad},{txtEsSuperActividad},{txtCarrera})" {BotonHabilitado}>
                                                                            <input style="width: auto !important;" class="nbtn gbtn btn_midi" title="Clic para realizar la asignación de secciones" type="{BotonAsignarSsecciones}" name="AsignarSecciones" id="AsignarSecciones" value="Asignar Secciones" onClick="javascript:AsignarSecciones('{txtCurso}','{txtLaSeccion}','{txtPeriodo}','{txtAnio}',{txtIdActividad},{txtTipoActividad},{txtCarrera})" {BotonHabilitado} >
                                                                            <input style="width: auto !important;" class="nbtn gbtn btn_midi" title="Clic para eliminar la actividad" type="{BotonBorrar}" name="Borrar" id="Borrar" value=" Borrar" onClick="BorrarActividad({txtIdActividad},{txtPosicion},'{txtCurso}','{txtSeccion}','{txtPeriodo}',{txtAnio},{txtCarrera})" {BotonHabilitado}>
                                                                            <input style="width: auto !important;" class="nbtn gbtn btn_midi" title="Clic para eliminar la actividad" type="{BotonMensajeBorrar}" name="Borrar" id="Borrar" value=" Borrar" onClick="{txtMensajeBorrar}" {BotonHabilitado}>
                                                                            {txtBotones}
                                                                        </td>
                                                                    </tr>
                                                                    <!-- END BLOCK : filatablalistaactividades -->
                                                                    </tbody>
                                                                </table>
                                                                <!-- END BLOCK : tablalistaactividades -->
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <hr>
                                                            <div id="siteactions" class="siterow restrict_right">
                                                                <!-- START BLOCK : enlaces -->
                                                                {nuevaactividad}
                                                                {listarcursoaprobado}
                                                                {cargarnotasactividad}
                                                                <!-- END BLOCK : enlaces -->
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <!-- START BLOCK : resumenactividades -->
                                                        <table class='RAsig-table' cellpadding="0" cellspacing="0" width="95%" align="center">
                                                            <thead>
                                                            <tr>
                                                                <th colspan="3"><div align="center" >RESUMEN DE ACTIVIDADES</th>
                                                            </tr>
                                                            <tr>
                                                                <td>Docencia Directa</td>
                                                                <td>Numero&nbsp;de actividades</td>
                                                                <td>Total Ponderaci&oacute;n&nbsp;</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>TEORIA&nbsp;</td>
                                                                <td>{NumeroActividadesClaseMagistral}&nbsp;</td>
                                                                <td>{PonderacionClaseMagistral}&nbsp;</td>
                                                            </tr>

                                                            <tr>
                                                                <td>PRACTICA&nbsp;</td>
                                                                <td>{NumeroActividadesLaboratorio}&nbsp;</td>
                                                                <td>{PonderacionLaboratorio}&nbsp;</td>
                                                            </tr>

                                                            <tr>
                                                                <td><div align="center"><label>TOTAL</label></td>
                                                                <td><label>{TotalNumero}</label></td>
                                                                <td><label>{TotalPonderacion}</label></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <!-- END BLOCK : resumenactividades -->
                                                    </div>
                                                    <!-- END BLOCK : listaactividades -->

                                                    <br>
                                                    <!-- START BLOCK : mensaje -->
                                                    {mensaje}
                                                    <!-- END BLOCK : mensaje -->
                                                    </div>
                                                    </div>
                                                </div>
                                                <div id="buttons">
                                                    {RegresarActividades}
                                                    <a href="../menu/D_CourseList.php">
                                                        <input id="Listado" name="Listado" type="button" class="nbtn rbtn btn_midi btn_exp_h okbutton" value="Regresar a listado de cursos">
                                                    </a>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contenido-pie"></div>
                                </div>
                                <!-- F: CONTENIDO PRINCIPAL -->
                                <script type="text/javascript">
                                    function cellStyler(value,row,index){
                                        var fontColor;
                                        switch (row.type) {
                                            case "CM":
                                                fontColor = "#3d3d3d";
                                                break;
                                            case "LB":
                                                fontColor = "#0000FF";
                                                break;
                                            case "PR":
                                                fontColor = "#008000";
                                                break;
                                            case "TT":
                                                fontColor = "#FF00CC";
                                                break;
                                        }

                                        if (index % 2 == 0){
                                            return 'background: #E0E0E0;color: ' + fontColor + 'text-transform: capitalize;font-weight: lighter';
                                        } else {
                                            return 'background: none repeat scroll 0% 0% rgba(255, 255, 255, 0.1);color: ' + fontColor + 'text-transform: capitalize;font-weight: lighter';
                                        }
                                    }

                                </script>
                                <script>
                                    $(function () {
                                        $('#nuevaactividad').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para agregar una nueva actividad</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#cmanual').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para ir a la carga de notas manual</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#lstactividades').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para ver las notas cargadas</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    $(function () {
                                        $('#btnEnviar').tooltip({
                                            position: 'right',
                                            content: '<span style="color:#fff">Clic para guardar la nueva actividad</span>',
                                            onShow: function(){
                                                getToolTipCss(this);
                                            }
                                        });
                                    });

                                    function getToolTipCss(element) {
                                        $(element).tooltip('tip').css({
                                            backgroundColor: '#666',
                                            color:'#848484',
                                            marginLeft:'8px',
                                            padding: '4px',
                                            border: '1px solid #a0a0a0',
                                            fontWeight:'bold',
                                            opacity: '.9',
                                            lineHeight:'14px',
                                            position: 'absolute',
                                            MozBorderRadius: '4px',
                                            borderRadius: '4px',
                                            MozBoxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                            WebkitBoxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                            boxShadow: '0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset',
                                            textShadow: '0 1px 0 rgba(255,255,255,.4)'
                                        });
                                    }
                                </script>
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