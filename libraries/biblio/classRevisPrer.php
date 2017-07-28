<?
class pilaClass
{ var $valorToken;
    var $puntero=0;

    function insertaPila($valor)
    { $this->puntero++;
        $this->valorToken[$this->puntero]=$valor;
    } // fin de insertar pila

    function sacaPila()
    { $tmpValor = $this->valorToken[$this->puntero];
        $this->puntero--;
        return $tmpValor;
    } // fin sacaPila

    function mostrarValorPila()
    { return  $this->valorToken[$this->puntero];  }

    function mostrarValor($indice)
    { return  $this->valorToken[$indice]; }

    function muestraPosicion()
    { return $this->puntero; }

    function Destroy()
    {  }

} // fin clase pila

class pilaEvalua extends pilaClass
{ var $valorAprobado;

    function modAprobado($valor)
    { $this->valorAprobado[$this->puntero]=$valor;}

    function muestraAprobacion()
    { return $this->valorAprobado[$this->puntero]; }
} // fin de clase pila de Evaluacion

function DestroyObject ($name)
{
    $theobject = $name;
    if (method_exists ($theobject,"Destroy"))
    { $theobject->Destroy (); }
//  unset($name);
}
?>