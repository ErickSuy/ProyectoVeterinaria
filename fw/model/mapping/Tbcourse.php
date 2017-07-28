<?php
/**
 * "Visual Paradigm: DO NOT MODIFY THIS FILE!"
 *
 * This is an automatic generated file. It will be regenerated every time
 * you generate persistence class.
 *
 * Modifying its content may cause the program not work, or your work may lost.
 */

/**
 * Licensee: DuKe TeAm
 * License Type: Purchased
 */

/**
 * @orm TbCourse
 */
class TbCourse
{

    private $idcourse;

    private $name;

    private $description;

    private $idinstitution;

    private $duration;

    private $state;

    private $tbshedule;

    private $nameinstitution;

    private $cupo;

    private $section;

    private $periodo;

    private $anio;

    public function TbCourse($idcourse, $name, $description, $duration, $idinstitution, $nameinstitution, $section, $cupo, $periodo, $anio)
    {

        $this->idcourse = $idcourse;
        $this->name = $name;
        $this->description = $description;
        $this->duration = $duration;
        $this->idinstitution = $idinstitution;
        $this->nameinstitution = $nameinstitution;
        $this->section = $section;
        $this->periodo = $periodo;
        $this->anio = $anio;
    }

    public function &getIdCourse()
    {
        return $this->idcourse;
    }


    public function setIdCourse(&$idcourse)
    {
        $this->idcourse = $idcourse;
    }


    public function &getName()
    {
        return $this->name;
    }


    public function setName(&$name)
    {
        $this->name = $name;
    }


    public function &getDescription()
    {
        return $this->description;
    }


    public function setDescription(&$description)
    {
        $this->description = $description;
    }


    public function &getDuration()
    {
        return $this->duration;
    }


    public function setDuration(&$duration)
    {
        $this->duration = $duration;
    }


    public function &getState()
    {
        return $this->state;
    }


    public function setState(&$state)
    {
        $this->state = $state;
    }


    public function &getIdInstitution()
    {
        return $this->idinstitution;
    }


    public function setIdInstitution(&$idinstitution)
    {
        $this->idinstitution = $idinstitution;
    }

    public function &getNameInstitution()
    {
        return $this->nameinstitution;
    }


    public function &getSection()
    {
        return $this->section;
    }


    public function setSection(&$section)
    {
        $this->section = $section;
    }

    public function &getCupo()
    {
        return $this->cupo;
    }


    public function setCupo(&$cupo)
    {
        $this->cupo = $cupo;
    }

    public function &getPeriodo()
    {
        return $this->periodo;
    }


    public function setPeriodo(&$periodo)
    {
        $this->periodo = $periodo;
    }

    public function &getAnio()
    {
        return $this->anio;
    }


    public function setAnio(&$anio)
    {
        $this->anio = $anio;
    }

    public function setNameInstitution(&$idInstitution)
    {
        $this->nameinstitution = $nameinstitution;
    }

    public function getObjects($state)
    {
        return array('idcourse' => $this->idcourse, 'name' => $this->name, 'description' => $this->description, 'duration' => $this->duration, 'state' => $state);
//   	return array('idcourse'=>$this->idcourse,'name'=>$this->name,'duration'=>$this->duration,'state'=>$state);
    }

    public function get()
    {
        $parameters = array($this->name, $this->description, $this->idinstitution, $this->duration);

        return $parameters;
    }

    public function getInsert()
    {
        //echo $this->name.$this->description.$this->duration;
        $parameters = array($this->name, $this->description, $this->duration);

        return $parameters;
    }

    public function getUpdate()
    {
        $parameters = array($this->idcourse, $this->name, $this->description, $this->idinstitution, $this->duration, 'ACTUALIZAR', $this->state);

        return $parameters;
    }

    public function getDelete()
    {
        $parameters = array($this->idcourse, $this->name, $this->description, $this->idinstitution, $this->duration, 'ELIMINAR', $this->state);

        return $parameters;
    }

}

?>
