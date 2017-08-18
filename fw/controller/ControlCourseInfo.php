<?php

include_once("../../path.inc.php");
include_once("$dir_biblio/biblio/SysConstant.php");

class ControlCourseInfo
{
    private $objCourses;

    public function ControlCourseInfo($listaCursos)
    {
        $this->objCourses = $listaCursos;
    }

    private function getResult($result)
    {
        if ($result == 'OK') {
            echo json_encode(array('success' => TRUE, 'uno' => 'nuevo'));
        } else {
            echo json_encode(array('msg' => $result));
        }
    }
    public function getInfoCurso($idcourse)
    {
        foreach ($this->objCourses as $value) {
            if($value['idcourse']==$idcourse){
                return $value;
            }
        }
        return NULL;
    }
    
    public function getMinutos($hora)
    {
        $totalMinutos=0;
        if($hora!=""){
            $vectorHora=explode(":",$hora);
            $totalMinutos=($vectorHora[0]*60)+$vectorHora[1];
        }
        
        return $totalMinutos;
    }
    public function getDiferencia($inicio, $fin){
        return $fin-$inicio;
    }
    public function getDuracion($idcourse){
         $infoCurso=$this->getInfoCurso($idcourse);
         $tmpTiempo=$this->getMinutos($infoCurso['fin'])-$this->getMinutos($infoCurso['inicio']);
         
         if($infoCurso){
            return round(($tmpTiempo/60));
         }else{
            return 0; 
         }

    }
}
?>
