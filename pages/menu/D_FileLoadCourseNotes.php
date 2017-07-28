<?php
/**
 * Created by PhpStorm.
 * User: emsaban
 * Date: 9/10/14
 * Time: 03:24 PM
 */

include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");
include_once("$dir_portal/fw/model/DB_Connection.php");
include_once("$dir_portal/fw/model/mapping/TbUser.php");
include_once("$dir_portal/fw/view/lib/TemplatePower.php");
include_once("$dir_portal/fw/view/msg/D_LoadNotesMsgs.php");
include_once("$dir_portal/fw/view/validator/ManejoString.php");
include_once("$dir_portal/fw/controller/manager/D_LoadNotesScheduleManager.php");
include_once("$dir_portal/fw/controller/manager/D_FileLoadCourseNotesManager.php");

session_start();

//Verificacion de sesión
$objuser = unserialize($_SESSION['usuario']);
if (!$objuser) {
    header("Location: ../../pages/LogOut.php");
}

// Verifica que se este entre el rango de fechas para ingreso de notas
if ($_SESSION["sObjNotas"]->mHabilitado == 1) {
    // Verifica si el estado en horario es aprobado o aprobado en espera no realice ningun proceso
    if (($_SESSION["sObjNotas"]->mEstado != 3)
        && ($_SESSION["sObjNotas"]->mEstado != 4)
        && ($_SESSION["sObjNotas"]->mEstado != 2)
    )
    {
        header("Location:../../pages/menu/D_ApprovedActList.php");
        die;
    } else {
        //Creacion de las instancias
        $obj_cad = new ManejoString; //Para manejo de strings

        //Verifica si ya ingreso por lo menos una vez para no volver a crear la instancia de nuevo
        if ($btnEnviarArchivo != 1) {
            //Creacion de las instancias
            $_SESSION["sIngresoArchivo"] = new D_FileLoadCourseNotesManager(); //Para manejo de ingreso de notas por archivo

            $_SESSION["sIngresoArchivo"]->mUsuarioid = $objuser->getId();
            $_SESSION["sIngresoArchivo"]->mCurso = $_SESSION["sObjNotas"]->mCurso;
            $_SESSION["sIngresoArchivo"]->mIndex = $_SESSION["sObjNotas"]->mIndex;
            $_SESSION["sIngresoArchivo"]->mSeccion = $_SESSION["sObjNotas"]->mSeccion;
            $_SESSION["sIngresoArchivo"]->mCarrera = $_SESSION["sObjNotas"]->mCarrera;
            $_SESSION["sIngresoArchivo"]->mPeriodo = $_SESSION["sObjNotas"]->mPeriodo;
            $_SESSION["sIngresoArchivo"]->mZona = $_SESSION["sObjNotas"]->mZona;
            $_SESSION["sIngresoArchivo"]->mFinal = $_SESSION["sObjNotas"]->mFinal;
            $_SESSION["sIngresoArchivo"]->mAnio = $_SESSION["sObjNotas"]->mAnio;
            $_SESSION["sIngresoArchivo"]->mNombreCorto = $_SESSION["sObjNotas"]->mNombreCorto;
            $_SESSION["sIngresoArchivo"]->mHorario = $_SESSION["sObjNotas"]->mHorario;
            $_SESSION["sIngresoArchivo"]->mAsignados = $_SESSION["sObjNotas"]->mAsignados;
            $_SESSION["sIngresoArchivo"]->mEstado = $_SESSION["sObjNotas"]->mEstado;
            $_SESSION["sIngresoArchivo"]->mLaboratorio = $_SESSION["sObjNotas"]->mLaboratorio;
            $_SESSION["sIngresoArchivo"]->mHabilitado = $_SESSION["sObjNotas"]->mHabilitado;
            $_SESSION["sIngresoArchivo"]->mCursoSinNota = $_SESSION["sObjNotas"]->mCursoSinNota;
            $_SESSION["sIngresoArchivo"]->mBloquearLabZona = $_SESSION["sObjNotas"]->mBloquearLabZona;
            $_SESSION["sIngresoArchivo"]->retrasadaUnica = $_SESSION["sObjNotas"]->retrasadaUnica;
            $_SESSION["sIngresoArchivo"]->mEscuela = $_SESSION["sObjNotas"]->mEscuela;
            $_SESSION["sIngresoArchivo"]->gsql = $_SESSION["sObjNotas"]->gsql;
        }

        $tamanioPermitido = MAX_FILE_SIZE / 1024; //Tamaño permitido del archivo a cargar

        $tpl = new TemplatePower("D_FileLoadCourseNotes.tpl");
        $tpl->assignInclude("ihead", "../includes/head.php");
        $tpl->assignInclude("iheader", "../includes/header.php");
        $tpl->assignInclude("isessioninfo", "../includes/sessioninfo.php");
        $tpl->assignInclude("imenu", "../includes/menu.php");
        $tpl->assignInclude("ifooter", "../includes/footer.php");

        $tpl->prepare();

        $tpl->assign("vFecha", Date("d-m-Y"));
        $tpl->assign("vHora", Date("H:i"));
        $tpl->assign("vCurso", $_SESSION["sIngresoArchivo"]->mCurso);
        $tpl->assign("vNombre", $_SESSION["sIngresoArchivo"]->mNombreCorto);
        $tpl->assign("vPeriodo", $obj_cad->funTextoPeriodo($_SESSION["sIngresoArchivo"]->mPeriodo));
        $tpl->assign("vAnio", $_SESSION["sIngresoArchivo"]->mAnio);
        $tpl->assign("periodo", $_SESSION["sIngresoArchivo"]->mPeriodo);
        $tpl->assign("vCarrera", $obj_cad->StringCarrera('0' . $_SESSION["sIngresoArchivo"]->mCarrera));

        $tpl->assign("Nombre", ".txt");
        $tpl->assign("Tamanio", "0&nbsp;KB");
        $tpl->assign("Tipo", "texto");

        if (isset($_POST['btnEnviarArchivo'])) {
            $txtArchivo = $_FILES["txtArchivo"]["name"];
            if (isset($txtArchivo)) {
                $txtArchivo_size = $_FILES["txtArchivo"]["size"];
                $txtArchivo_type = $_FILES["txtArchivo"]["type"];
                $txtArchivo_size = ceil($txtArchivo_size / 1024);
                $tamanioArchivo = $txtArchivo_size + 0;

                if (($txtArchivo_type != "text/plain" && $txtArchivo_type != "application/vnd.ms-excel") || $txtArchivo_size == 0 || $txtArchivo_size > 50) {
                    $tpl->assign("Msg", "<div class=\"alert alert-warning\"><h4><i class=\"fa fa-warning\"></i> IMPORTANTE</h4>".$msg[303]."</div>");
                } else {
                    $txtArchivo_name = $_FILES["txtArchivo"]["name"];
                    $tpl->assign("Nombre", $txtArchivo_name);
                    $tpl->assign("Tamanio", $txtArchivo_size . "&nbsp;KB");
                    $tpl->assign("Tipo", "texto");

                    $_SESSION["sIngresoArchivo"]->mNombreArchivo = $txtArchivo_name;
                    $txtArchivo_name = $_SESSION["sIngresoArchivo"]->mIndex."_".$_SESSION["sIngresoArchivo"]->mNombreCorto . $_SESSION["sIngresoArchivo"]->mCarrera .
                        $_SESSION["sIngresoArchivo"]->mPeriodo . $_SESSION["sIngresoArchivo"]->mAnio . ".txt";
                    $final_path = $file_dir . "/" . $txtArchivo_name;
                    if (!move_uploaded_file($_FILES["txtArchivo"]["tmp_name"], $final_path)) {
                        // Codificar el mensaje para cuando no puede trasladar el archivo
                    } else {
                        chmod($final_path, 0646); # cambia privilegios, para lectura de otros usuarios.
                        $notas = file($final_path);
                        $num = count($notas);
                        if ($_SESSION["sIngresoArchivo"]->mCursoSinNota == 0) { // para los cursos que llevan notas
                            if ($_SESSION["sIngresoArchivo"]->ValidaArchivo($num, $notas, $tamanioArchivo,
                                    $_SESSION["sIngresoArchivo"]->mBloquearLabZona) == 0
                            ) {
                                $tpl->assign("Msg", "<div class=\"alert alert-warning\"><h4><i class=\"fa fa-warning\"></i> IMPORTANTE</h4>".$_SESSION["sIngresoArchivo"]->mStringMensaje ."</div>");
                            } else {
                                $tpl->assign("Msg", "<div class=\"alert alert-warning\"><h4><i class=\"fa fa-warning\"></i> IMPORTANTE</h4>".$_SESSION["sIngresoArchivo"]->mStringMensaje ."</div>" .
                                    $_SESSION["sIngresoArchivo"]->mEnlaceManual . "" .
                                    $_SESSION["sIngresoArchivo"]->mEnlaceAprobacion . "");
                            }
                            //Fin de if ValidaArchivo
                        } else { // para los cursos que no llevan nota las practicas
                            if ($_SESSION["sIngresoArchivo"]->ValidaArchivo_2($num, $notas, $tamanioArchivo) == 0) {
                                $tpl->assign("Msg", "<div class=\"alert alert-warning\"><h4><i class=\"fa fa-warning\"></i> IMPORTANTE</h4>".$_SESSION["sIngresoArchivo"]->mStringMensaje ."</div>");
                            } else {
                                $tpl->assign("Msg", "<div class=\"alert alert-warning\"><h4><i class=\"fa fa-warning\"></i> IMPORTANTE</h4>".$_SESSION["sIngresoArchivo"]->mStringMensaje ."</div>" .
                                    $_SESSION["sIngresoArchivo"]->mEnlaceManual . "" .
                                    $_SESSION["sIngresoArchivo"]->mEnlaceAprobacion . "");
                            }
                            //Fin de if ValidaArchivo

                        }
                    }
                    //Fin de mover archivo
                }
                //Fin de if tipo y tamaño de archivo
            }
            //Fin de isset Archivo
        }
        //Fin de boton activado

        $tpl->printToScreen();
        unset($tpl);
        unset($obj_cad);
    }
    //Fin de if verifica estado en horario
} else {
    $destino = "Location: ../../pages/menu/D_CourseInformationReview.php?curso=" . $_SESSION["sObjNotas"]->mCurso . "&carrera=" . $_SESSION["sObjNotas"]->mCarrera . "&index=" . $_SESSION["sObjNotas"]->mIndex;
    header($destino);
}