<?php
/**
 * Created by PhpStorm.
 * User: escuelavacaciones
 * Date: 31/10/2014
 * Time: 07:46 PM
 */
include_once("General_SQL.php");

Class EnrollmentManager_SQL extends General_SQL {

    /**
     * begin en @línea   #78
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     *  -   VALUES('%s','%s','%s','%s','%d');
     * @functionOrigen  Inscribir
     * @ref #1
     * @línea   #92
     */
    function Inscribir_insert1($mUsuario, $mCarrera, $mFechains, $mCiclo, $mTransaccion) {
        return sprintf("INSERT INTO inscripcioncarrera (usuarioid,carrera,fecha,anio,transaccion)
                            VALUES('%s','%s','%s','%s','%d');", $mUsuario, $mCarrera, $mFechains, $mCiclo, $mTransaccion);
    }

    /**
     * rollback en @línea   #98
     */

    /**
     * commit en @línea   #104
     */

    /**
     * end en @línea   #114
     */

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerInscripcion
     * @ref #2
     * @línea   #132
     */
    function VerInscripcion_select1($mUsuario, $mCarrera, $mCiclo) {
        return sprintf("SELECT e.usuarioid, e.nombre, e.apellido, i.carrera, i.fecha, i.anio, i.transaccion
                        FROM inscripcioncarrera i, estudiante e " .
            "WHERE e.usuarioid=i.usuarioid
                            AND i.usuarioid='%s'
                            AND i.carrera='%s'
                            AND i.anio='%s'
                        ", $mUsuario, $mCarrera, $mCiclo);
    }

    /**
     * @detalleBD BD_portal2.html
     * @diagramaBD BD_portal2.pdf
     * @cambios
     * @functionOrigen  VerInscripcionCiclo
     * @ref #3
     * @línea   #185
     */
    function VerInscripcionCiclo_select1($mUsuario, $mCarrera, $mCiclo) {
        return sprintf("SELECT e.usuarioid, e.nombre, e.apellido, i.carrera, i.fecha, i.anio, i.transaccion
                        FROM inscripcioncarrera i, estudiante e ".
            "WHERE e.usuarioid=i.usuarioid
                            AND i.usuarioid='%s'
                            AND i.carrera='%s'
                            AND i.anio='%s'
                            ",$mUsuario, $mCarrera, $mCiclo);
    }


    /**
     * @functionOrigen  VerHistorialInscripcion
     * @ref #4
     * @línea   #241
     */
    function VerHistorialInscripcion_select1($mUsuario, $mCarrera) {
        return sprintf("SELECT t1.idstudent,t3.name as namee,t3.surname,t1.idcareer,t4.name as namec,t1.year,t1.idcurriculum, t1.enrollmentdate
                    FROM tbenrollment t1 join tbstudentcareer t2 on t1.idstudent=t2.idstudent and t1.idcareer=t2.idcareer join tbstudent t3 on t2.idstudent=t3.idstudent join tbcareer t4 on t2.idcareer=t4.idcareer
                    WHERE t1.idstudent=%d and t1.idcareer=%d order by year", $mUsuario, $mCarrera);
    }

}
?>