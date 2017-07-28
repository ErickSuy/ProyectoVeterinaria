// Valores del laboratorio
// MinLab = 61  MaxLab = 100
//   MinLab = 61;
//   MaxLab = 100;
// Valores de la zona
// MinZona =36  MinCong = 45 MaxZona=75
// MaxExamen = 25 

// 8 es retroceso
// 0,9 es tabulador
// 13 es enter
// 18 es el alt
// 45 o 95 es menos

// si lleva saltarlab 0 entonces se debe de verificar el valor del laboratorio

function Derecha(tecla)
{
    if(tecla == 39)
        return true;
    else
        return false;
}

function Izquierda(tecla)
{
    if(tecla == 37)
        return true;
    else
        return false;
}

function Arriba(tecla)
{
    if(tecla == 38)
        return true;
    else
        return false;
}

function Abajo(tecla)
{
    if(tecla == 40)
        return true;
    else
        return false;
}

function EsAlt(tecla)
{
    if(tecla == 18)
        return true;
    else
        return false;
}

function EsRetroceso(tecla)
{
    if(tecla == 8)
        return true;
    else
        return false;
}

function EsTabulador(tecla)
{
    if(tecla == 9 || tecla == 0)
        return true;
    else
        return false;
}

function EsEnter(tecla)
{
    if(tecla == 13)
        return true;
    else
        return false;
}

function EsDelete(tecla)
{
    if(tecla == 46)
        return true;
    else
        return false;
}


function EsDigito(tecla)
{
    if(tecla >=  48 && tecla <= 57)
//    if((tecla < 48 || tecla > 57) && (tecla < 96 || tecla > 105))
    {
        tecla = 7;  // codigo para identificar a los numeros
    }
    else tecla = 6;  //numero sin sentido para el default, rango fuera de los digitos

    return tecla;
}


// con onkeypress el codigo del menos es 45 o 95
// el 109 es deplano para el onkeydown
function EsMenos(tecla)
{
    if(tecla == 45 || tecla == 95)
        return true;
    else
        return false;
}

// Retorna verdadero si el contenido de la celda es NSP o SDE
function EsNSPoSDE(posicion)
{
    ErrorNSPoSDE = false;
    //alert(document.Bloque.elements[posicion].value);
    if(isNaN(document.Bloque.elements[posicion].value))
    {
        examen = new String(document.Bloque.elements[posicion].value);
        //alert('es= '+examen);
        if(examen == 'NSP' || examen=='SDE')
            ErrorNSPoSDE = true;
    }
    return ErrorNSPoSDE;
}

//Agregada por Pancho L�pez el 09/10/2012 para el control de los nuevos c�digos de problema en la asignacion
function esCursoCongelado(problemas) {

    if (problemas.length == 0)
        return false;
    vectorProblemas = problemas.split(",");
    if (vectorProblemas.length == 0)
        return false;
    for(i = 0; i < vectorProblemas.length; i++)
        if (vectorProblemas[i] == 3 || vectorProblemas[i] == 17)
            return true;
    return false;
}

// Funcion que devuelve 1 si el laboratorio es un error y 0 lo contrario
function AceptarLaboratorio(posicion,destino,saltarlab)
{
//	alert('El periodo es: '+Periodo); 
// se consulta si el curso del estudiante tiene algun problema
    problemas = document.Bloque.elements[posicion+4].value;

    nota = new Number(document.Bloque.elements[posicion].value);
//  alert('problema '+problema);
    // si saltarlab = 1 se omite el laboratorio si es = 0 se verifica
    if(saltarlab == 0) // si hay que verficar la nota del laboratorio
    {

        if(nota >= 0 && nota <= MaxLab)
        {

            if( Laboratorio !=6 ) // si es el laboratorio normal es 2, si es 6 es practica tipo laboratorio
            {


                if(nota < MinLab)
                {
                    document.Bloque.elements[posicion+1].value = 0; //zona
                    document.Bloque.elements[posicion+2].value = 'SDE'; //examenfinal
                    document.Bloque.elements[posicion+3].value = 'SDE'; //notafinal
                }
                else
                {
                    if(!EsNSPoSDE(posicion+2)) // sino es NSP o SDE el examen final
                    {
                        zona   = new Number(document.Bloque.elements[posicion+1].value);
                        examen = new Number(document.Bloque.elements[posicion+2].value);
                        document.Bloque.elements[posicion+3].value = examen + zona;
                    }
                    else
                    {
                        zona   = new Number(document.Bloque.elements[posicion+1].value);
//Comentarizada y modificada por Pancho L�pez el 09/10/2012 para el control de los nuevos c�digos de problema en la asignaci�n
//                if(problema == 3 || problema == 17) // si es curso congelado
                        if(esCursoCongelado(problemas)) // si es curso congelado
                        {
                            if(Periodo != 1 && Periodo != 5)
                            {
                                if(zona >= MinCong) // si es mayor o igual que la minima de congelamiento
                                {
                                    document.Bloque.elements[posicion+2].value = 0;
                                    document.Bloque.elements[posicion+3].value = zona + 0;
                                }
                            }
                        }
                        else
                        if(zona >= MinZona)
                        {
                            document.Bloque.elements[posicion+2].value = 0;
                            document.Bloque.elements[posicion+3].value = zona + 0;
                        }
                    }
                }
                //alert('valores '+MaxLab+' '+MinLab);
                //alert('posicion '+destino);
                ErrorLab = 0;
//            document.Bloque.elements[destino].select();
            }
            else {
//Agregado el 26/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                if (Escuela==1 || Escuela==5) { //S�lo para los cursos de las escuelas de Ingenier�a Civil e Ingenier�a Qu�mica
                    zona  = new Number(document.Bloque.elements[posicion+1].value);
                    if (zona>0 && zona < MinZona)
                        document.Bloque.elements[posicion].value=0;
                }
//Finaliza c�digo agregado el 26/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                ErrorLab = 0;
            }

        }
        else
        {
            ErrorLab = 1;
            alert('Laboratorio NO valido');
        }
    }
    else ErrorLab = 0;  // se omite el laboratorio
    return ErrorLab;

} // fin de Aceptarlaboratorio



// Se ejecutaria con la tecla tabulador o con un cambio con el mouse
// verifica si el contenido de la celda es valido segun su tipo
function EsValido(indice,tipo,saltarlab)
{
    //alert('esvalido, tipo '+tipo+' indice '+indice);
    valido = true;

    nota = new Number(document.Bloque.elements[indice].value);
    if(nota == 0 ) document.Bloque.elements[indice].value = 0;

    switch(tipo)
    {
        case 1: // es un laboratorio
            ErrorLab = AceptarLaboratorio(indice,indice+1,saltarlab);
            //alert('verifica en esvalido');
            if(ErrorLab == 1) // hubo error en el ingreso del laboratorio
            {
                valido = false;

            }
            break;
        case 2: // es una zona
            ErrorZona = AceptarZona(indice,indice+1,saltarlab);
            if(ErrorZona == 1) // hubo error en el ingreso de la zona
            {
                valido = false;
            }
            break;
        case 3: ErrorExamen = AceptarExamen(indice,indice+1,saltarlab);
            if(ErrorExamen == 1) // hubo error en el ingreso del examen final
                valido = false;
            break;
        case 4: ErrorExamen = AceptarExamen_2(indice,indice+1,saltarlab);
            if(ErrorExamen == 1) // hubo error en el ingreso del examen final
                valido = false;
            break;
    }

    return valido;
}



// Se realiza un ingreso en un input de laboratorio
function IngresoEnLaboratorio(e,posicion,destino,saltarlab)
{

//   alert('uno '+e.keyCode);
//   alert('dos '+e.which);
// document.captureEvents(e.KEYPRESS);   
    if(document.all)
    {
        navegador = 1;
        tecla = e.keyCode;
    }
    else
    {
        document.captureEvents(e.KEYPRESS);
        navegador = 2;
        tecla = e.which;
    }

//alert('en laboratorio '+tecla); // identificar el alt u otras mas

    if(!EsEnter(tecla)   && !EsTabulador(tecla) && !EsRetroceso(tecla) &&
        !EsAlt(tecla)     && !EsDelete(tecla)    && !Derecha(tecla)     &&
        !Izquierda(tecla) && !Arriba(tecla)      && !Abajo(tecla)        )
    {
        tecla = EsDigito(tecla);
    }

    switch(tecla)
    {
        case 7: // Es un numero
            break;

        case 8: // Es retroceso
            break;

        case 0:
        case 9:  // es tabulador
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0)
            {
                document.Bloque.elements[posicion].value = 0;
            }
            ErrorLab = AceptarLaboratorio(posicion,destino,saltarlab);
            if(ErrorLab == 1) // hubo error en el ingreso del laboratorio
            {
                //document.Bloque.elements[posicion].focus();
                return false;
            }
            else  return true;
            break;

        case 13: // es enter
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0)
            {
                document.Bloque.elements[posicion].value = 0;
            }
            ErrorLab = AceptarLaboratorio(posicion,destino,saltarlab);
            if(ErrorLab == 0) // no hubo error en el ingreso del laboratorio
            {
                document.Bloque.elements[destino].focus();
            }
            else   {
                // document.Bloque.elements[posicion].focus();
            }
            return true;
            break;

        case 18: // Es el alt
            break;

//    case 0:
        case 39: // a la derecha
            alert('derecha');
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0)
            {
                document.Bloque.elements[posicion].value = 0;
            }
            document.Bloque.elements[destino].focus();
            //document.Bloque.elements[destino+1].select();
            break;

        case 46: // es delete
            break;

        default:
            if(navegador == 1)
            {
                event.returnValue = false;
            }
            else
            {

                return false;
            }

    } // fin del switch
//alert('fin');
} // fin del IngresoEnLaboratorio


function AceptarZona(posicion,destino,saltarlab)
{
    nota = new Number(document.Bloque.elements[posicion].value);
    if(!EsNSPoSDE(posicion+1)) // si es un valor el examen
    {
        examen = new Number(document.Bloque.elements[posicion+1].value);
        // document.Bloque.elements[posicion+2].value = examen + nota;
    }
    else // el valor del examen es SDE o NSP
    {
        examen = 0;
    }
// se consulta si el curso del estudiante tiene algun problema
    problemas = document.Bloque.elements[posicion+3].value;

    if(nota >= 0 && nota <= MaxZona)
    {
        // casos 3 y 17 unicamente, zona congelada
        if(esCursoCongelado(problemas))
        {
            if(Periodo != 1 && Periodo != 5) // el periodo son retrasadas
            {
                if(saltarlab==0 && Laboratorio==2) // el curso lleva laboratorio
                {
                    valorlab = new Number(document.Bloque.elements[posicion-1].value);
                    if(valorlab < MinLab) // si perdio laboratorio
                    {
                        document.Bloque.elements[posicion].value = 0;	 // se anula la zona
                        document.Bloque.elements[posicion+1].value = 'SDE'; // examen final
                        //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                        document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                    }
                    else   // si gano el laboratorio
                    {
                        if(nota < MinCong) // si es menor que el 60% de la zona
                        {
                            document.Bloque.elements[posicion+1].value = 'SDE';
                            //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                            document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                        }
                        else
                        {
                            document.Bloque.elements[posicion+1].value = examen;
                            document.Bloque.elements[posicion+2].value = nota + examen;
                        }
                    }
                }
                else // el curso no lleva laboratorio
                {
                    if(nota < MinCong) // si es menor que el 60% de la zona
                    {
                        document.Bloque.elements[posicion+1].value = 'SDE';
                        //document.Bloque.elements[posicion+2].value = 'SDE';
                        document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                    }
                    else
                    {
                        document.Bloque.elements[posicion+1].value = examen;
                        document.Bloque.elements[posicion+2].value = nota + examen;
                    }
                }
            }
            else // el periodo es finales, en periodo de finales no se ingresa examen final
            {
                if(saltarlab==0 && Laboratorio==2) // el curso lleva laboratorio
                {
                    valorlab = new Number(document.Bloque.elements[posicion-1].value);
                    if(valorlab < MinLab) // si perdio laboratorio
                    {
                        document.Bloque.elements[posicion].value = 0;	 // la zona se anula
                    }
                }
                document.Bloque.elements[posicion+1].value = 'SDE';//'SDE'; // examen final
                //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
            }
        }
        // sino es zona normal
        else
        {
            document.Bloque.elements[posicion+1].value = examen;
            if(saltarlab==0 && Laboratorio==2) // el curso lleva laboratorio
            {
                valorlab = new Number(document.Bloque.elements[posicion-1].value);
                if(valorlab < MinLab) // si perdio laboratorio
                {
                    document.Bloque.elements[posicion].value = 0;	// la zona se anula				  
                    document.Bloque.elements[posicion+1].value = 'SDE'; // examen final
                    //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                    document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                }
                else   // si gano el laboratorio
                {
                    if(nota < MinZona)
                    {
//Agregado el 26/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                        if (Escuela==1 || Escuela==5) //S�lo para los cursos de las escuelas de Ingenier�a Civil e Ingenier�a Qu�mica
                            document.Bloque.elements[posicion-1].value=0;
//Finaliza c�digo agregado el 26/06/2013 para el requerimiento del acta de J.D. No. 22-2012; 11-07-12/Punto Unico
                        document.Bloque.elements[posicion+1].value = 'SDE';
                        //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                        document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                    }
                    else document.Bloque.elements[posicion+2].value = nota + examen;
                }
            }
            else // el curso no lleva laboratorio
            {
                if(nota < MinZona)
                {
                    document.Bloque.elements[posicion+1].value = 'SDE';
                    //document.Bloque.elements[posicion+2].value = 'SDE'; // nota final
                    document.Bloque.elements[posicion+2].value = document.Bloque.elements[posicion].value;
                }
                else document.Bloque.elements[posicion+2].value = nota + examen;
            }
        } // fin del else si es zona normal
        ErrorZona = 0;
//    document.Bloque.elements[destino].select();
    }
    else
    {
        ErrorZona = 1;
        alert('Zona NO valida');
    }
    return ErrorZona;
} // fin de AceptarZona





// Se realiza un ingreso en un input de la zona
function IngresoEnZona(e,posicion,destino,saltarlab)
{
    // tecla = (document.all) ? e.keyCode : e.which;
    if(document.all)
    {
        navegador = 1;
        tecla = e.keyCode;
    }
    else
    {
        document.captureEvents(e.KEYPRESS);
        navegador = 2;
        tecla = e.which;
    }

    if(!EsEnter(tecla) && !EsTabulador(tecla) && !EsRetroceso(tecla) && !EsAlt(tecla) && !EsDelete(tecla))
    {
        tecla = EsDigito(tecla);
    }

    switch(tecla)
    {
        case 7: // Es un numero
            break;

        case 8: // Es retroceso
            break;

        case 0:
        case 9: // es tabulador
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0 ) document.Bloque.elements[posicion].value = 0;
            ErrorZona = AceptarZona(posicion,destino,saltarlab);
            if(ErrorZona == 1) // si hay error en el ingreso de la zona
            {
                return false;
            }
            return true;
            break;

        case 13:
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0 ) document.Bloque.elements[posicion].value = 0;
            ErrorZona = AceptarZona(posicion,destino,saltarlab);
            if(ErrorZona == 0)  // sino hay error en el ingreso de la zona
            {
                document.Bloque.elements[destino].focus();
            }
            //else  {  alert('sigue aqui'); }
            return true;
            break;

        case 18: // Es el alt
            break;

        case 46: // Es delete
            break;

        default:
            if(navegador == 1)
            {
                event.returnValue = false;
            }
            else
            {

                return false;
            }

    } // fin del switch
    //return true;
}   // fin de la funcion


function EsValidoLab(posicion)
{

    nota = new Number(document.Bloque.elements[posicion].value);

    if(nota >= 0 && nota <= MaxLab)
    {
        if( Laboratorio == 6 ) return true; // practica tipo laboratorio
        if(nota < MinLab) // si es menor que el 61
            return false;
        return true;
    }
    else
    {
        return false;
    }
}


function EsValidaZona(posicion)
{

    nota = new Number(document.Bloque.elements[posicion].value);

// se consulta si el curso del estudiante tiene algun problema
    problemas = document.Bloque.elements[posicion+3].value;

    if(nota >= 0 && nota <= MaxZona)
    {
        // casos 3 y 17 unicamente, zona congelada
        if(esCursoCongelado(problemas))
        {
            if(nota < MinCong) // si es menor que el 60% de la zona
                return false;

        }
        // sino es zona normal
        else
        {
            if(nota < MinZona)
                return false;

        }
        return true;
    }
    else
    {
        return false;
    }
}


function AceptarExamen(posicion,destino,saltarlab)
{

    if(EsNSPoSDE(posicion))
    {
        // no hay error son NSP y SDE
        ErrorExamen = 0;
    }
    else
    {
        examen = new Number(document.Bloque.elements[posicion].value);

        if((examen >= 0 && examen <= MaxExamen) || examen == -1 || examen == -2)
        {
            if(examen==-1)
            {
                document.Bloque.elements[posicion].value = 'NSP';
                //document.Bloque.elements[posicion+1].value = 'NSP';
                document.Bloque.elements[posicion+1].value = document.Bloque.elements[posicion-1].value;
            }
            else
            {
                if(examen==-2)
                {
                    document.Bloque.elements[posicion].value = 'SDE';
                    //document.Bloque.elements[posicion+1].value = 'SDE';
                    document.Bloque.elements[posicion+1].value = document.Bloque.elements[posicion-1].value;
                }
                else
                {
                    examen = new Number(document.Bloque.elements[posicion].value);
                    zona = new Number(document.Bloque.elements[posicion-1].value);

                    labpermitido = true;
                    if(saltarlab==0) // verificar la nota del laboratorio
                        labpermitido = EsValidoLab(posicion-2);

//             if( EsValidaZona(posicion-1) && EsValidoLab(posicion-2) )
                    if( EsValidaZona(posicion-1) && labpermitido )
                        document.Bloque.elements[posicion+1].value =  zona + examen;
                    else
                    {
                        //document.Bloque.elements[posicion].value = 'SDE';
                        //document.Bloque.elements[posicion+1].value = 'SDE';
                        document.Bloque.elements[posicion].value = 'SDE';
                        document.Bloque.elements[posicion+1].value = document.Bloque.elements[posicion-1].value;
                    }

                } // fin del if(examen==-2)

            } // fin del if(examen==-1)
            ErrorExamen = 0;
            //document.Bloque.elements[destino].select();
        }
        else
        {

            ErrorExamen = 1;
            alert('Valor de examen no valido');
            //document.Bloque.elements[posicion].select();
        }
    }
    return ErrorExamen;
} // fin de aceptar examen



// Se realiza un ingreso en un input del examen
function IngresoEnExamen(e,posicion,destino,saltarlab)
{

//  tecla = (document.all) ? e.keyCode : e.which;
    if(document.all)
    {
        navegador = 1;
        tecla = e.keyCode;
    }
    else
    {
        document.captureEvents(e.KEYPRESS);
        navegador = 2;
        tecla = e.which;
    }


    if(!EsEnter(tecla) && !EsTabulador(tecla) && !EsRetroceso(tecla) && !EsMenos(tecla) && !EsDelete(tecla))
    {
        tecla = EsDigito(tecla);
    }

    switch(tecla)
    {
        case 7: // Es un numero
            break;

        case 8: // Es retroceso
            break;
        case 0:
        case 9: // es tabulador
            if(EsNSPoSDE(posicion))
            {
                // no hay error son NSP y SDE
            }
            else
            {
                nota = new Number(document.Bloque.elements[posicion].value);
                if(nota == 0) document.Bloque.elements[posicion].value = 0;
                ErrorExamen = AceptarExamen(posicion,destino,saltarlab);
                if(ErrorExamen == 1)
                {
                    //document.Bloque.elements[posicion].value = 0;
                    return false;
                }
                return true;
            }
            break;

        case 13: // es el Enter
            if(EsNSPoSDE(posicion))
            {
                //alert('posicion '+posicion+' destino '+destino);
                document.Bloque.elements[destino].focus();
                // no hay error son NSP y SDE
            }
            else
            {
                ErrorExamen = AceptarExamen(posicion,destino,saltarlab);
                if(ErrorExamen == 0) // sin error en el ingreso del examen
                {
                    // alert('posicion '+posicion+' destino '+destino);
                    document.Bloque.elements[destino].focus();
//			      document.Bloque.elements[destino].select();
                }
                else
                {
                    //document.Bloque.elements[posicion].focus();
                    //	document.Bloque.elements[posicion].select();
                }
            }
            return true;
            break;

        case 18: // es el alt
            break;
        case 45:
        case 95: // es el menos, justificado por el -1=NSP y -2=SDE
            break;

        case 46: // Es delete
            break;

        default:// alert('Error tecla '+tecla);
            if(navegador == 1)
            {
                event.returnValue = false;
            }
            else
            {
                return false;
            }
    } // fin del switch

}   // fin de la funcion ingresoenexamen


// Retorna verdadero si el contenido de la celda es REP o APR
function EsREPoAPR(posicion)
{
    ErrorREPoAPR = false;
    examen = new String(document.Bloque.elements[posicion].value);
    //alert('es....= '+examen);
    //alert(document.Bloque.elements[posicion].value);
    if(isNaN(document.Bloque.elements[posicion].value))
    {
        examen = new String(document.Bloque.elements[posicion].value);
        //alert('es= '+examen);
        if(examen == 'REP' || examen=='APR' || examen=='SDE')
            ErrorREPoAPR = true;
    }
    return ErrorREPoAPR;
}

function AceptarExamen_2(posicion,destino,saltarlab)
{
// se consulta si el curso del estudiante tiene algun problema
//   problema = new Number(document.Bloque.elements[posicion+4].value);


    if(EsREPoAPR(posicion))
    {
        // no hay error son APR o REP
        ErrorExamen = 0;
    }
    else
    {
        ErrorExamen = 0;
        examen = new Number(document.Bloque.elements[posicion].value) * 1;
        //alert("q es "+examen);
        switch( examen )
        {
            case 1: // aprobo la practica
                document.Bloque.elements[posicion].value = 'APR';
                document.Bloque.elements[posicion+1].value = 'APR';
                break;
            case 2: // reprobo la practica
                document.Bloque.elements[posicion].value = 'REP';
                document.Bloque.elements[posicion+1].value = 'REP';
                break;
            default:
                ErrorExamen = 1;
                alert('Valor de examen no valido');
        }
    }
    return ErrorExamen;
} // fin de aceptar examen_2

function IngresoEnExamen_2(e,posicion,destino,saltarlab)
{

//  tecla = (document.all) ? e.keyCode : e.which;
    if(document.all)
    {
        navegador = 1;
        tecla = e.keyCode;
        //alert('Error tecla '+tecla);
    }
    else
    {
        // document.captureEvents(e.KEYPRESS);

        //var tipo = e.type;


        navegador = 2;
        tecla = e.which;
    }


//var tecla = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;  




    if(!EsEnter(tecla) && !EsTabulador(tecla) && !EsRetroceso(tecla) && !EsMenos(tecla) && !EsDelete(tecla))
    {
        tecla = EsDigito(tecla);
    }


    switch(tecla)
    {
        case 7: // Es un numero
            break;

        case 8: // Es retroceso
            break;
        case 0:
        case 9: // es tabulador
            //alert('entro por el tabulador');
            nota = new Number(document.Bloque.elements[posicion].value);
            if(nota == 0) document.Bloque.elements[posicion].value = 0;
            ErrorExamen = AceptarExamen_2(posicion,destino,saltarlab);
            if(ErrorExamen == 1)
            {
                //document.Bloque.elements[posicion].value = 0;
                return false;
            }
            return true;
            /*
             if(EsREPoAPR(posicion))
             {
             // no hay error son NSP y SDE
             }
             else
             {
             nota = new Number(document.Bloque.elements[posicion].value);
             if(nota == 0) document.Bloque.elements[posicion].value = 0;
             ErrorExamen = AceptarExamen_2(posicion,destino,saltarlab);
             if(ErrorExamen == 1)
             {
             //document.Bloque.elements[posicion].value = 0;
             return false;
             }
             return true;
             }
             */
            break;

        case 13: // es el Enter
            ErrorExamen = AceptarExamen_2(posicion,destino,saltarlab);
            if(ErrorExamen == 0) // sin error en el ingreso del examen
            {
                //alert('posicion '+posicion+' destino '+destino);
                document.Bloque.elements[destino].focus();
                //document.Bloque.elements[destino].select();
            }
            else
            {
                document.Bloque.elements[posicion].focus();
                //	document.Bloque.elements[posicion].select();
            }

            /*

             if(EsREPoAPR(posicion))
             {
             //alert('posicion '+posicion+' destino '+destino);
             document.Bloque.elements[destino].focus();
             // no hay error son NSP y SDE
             }
             else
             {
             ErrorExamen = AceptarExamen_2(posicion,destino,saltarlab);
             if(ErrorExamen == 0) // sin error en el ingreso del examen
             {
             alert('posicion '+posicion+' destino '+destino);
             document.Bloque.elements[destino].focus();
             //			      document.Bloque.elements[destino].select();
             }
             else
             {
             document.Bloque.elements[posicion].focus();
             //	document.Bloque.elements[posicion].select();
             }
             }   */
            return true;
            break;

        case 18: // es el alt
            break;
        case 45:
        case 95: // es el menos, justificado por el -1=NSP y -2=SDE
            break;

        case 46: // Es delete
            break;

        default:// alert('Error tecla '+tecla);
            if(navegador == 1)
            {
                event.returnValue = false;
            }
            else
            {
                return false;
            }
    } // fin del switch

}   // fin de la funcion ingresoenexamen_2
