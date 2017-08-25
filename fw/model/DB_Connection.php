<?php

/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: db_pgsql.inc,v 1.9 2002/08/26 08:27:43 richardarcher Exp $
 *
 */
error_reporting(0); //evitar warrings de php.
<<<<<<< HEAD

=======
>>>>>>> DevNotasActividades
include("config/DB_Params.php");

class DB_Connection
{
    var $Host; // direccion IP del servidor de pruebas, desactivar cuando se publique el sitio
    var $cualbase;
    var $Database; // nombre de la BD de ingeniería
    var $Port; // puerto por el cual se conecta a la BD
    var $User; // se deja en blanco y se parametriza en las clases
    var $Password; // se deja en blanco y se parametriza en las clases
  
    var $Link_ID = 0;
    var $Query_ID = 0;
    var $Record = array();
    var $Row = 0;

    var $Seq_Table; // nombre de la secuencia en la base de datos (numinscripcion)

    var $Errno = 0;
    var $Error = "";
    var $ValorError = 0; // valor que envian los distintos triggers de la BD

    var $Debug = 0;

    var $Auto_Free = 1; # Set this to 1 for automatic pg_freeresult on
    # last record.
    var $PConnect = 0; ## Set to 1 to use persistent database connections

    function ifadd($add, $me)
    {
        if ("" != $add) return " " . $me . $add;
    }

    /* public: constructor */
    public function DB_Connection($query = "")
    {
        $this->Host = HOST_WEB;
        $this->Port = BD_PORT;
        $this->Database = DB_FMVZ;
        $this->User = USR_ROOT;
        $this->Password = PWD_ROOT;

        $this->query($query);
    }

    function connect()
    {
        if (0 == $this->Link_ID) {
            $cstr = "dbname=" . $this->Database .
                $this->ifadd($this->Host, "host=") .
                $this->ifadd($this->Port, "port=") .
                $this->ifadd($this->User, "user=") .
                $this->ifadd($this->Password, "password=");
            $this->cualbase = 1;
            restore_error_handler();
            set_error_handler(array(&$this, 'errores'));

            if (!$this->PConnect) {
                $this->Link_ID = pg_connect($cstr);
            } else {
                $this->Link_ID = pg_pconnect($cstr);
            }

            restore_error_handler();
            if (!$this->Link_ID) {
//        $this->halt("connect() failed.");
                return 100;
            }
        }
        return 1;
    }

    function connect2()
    {
        if (0 == $this->Link_ID) {
            $cstr = "dbname=" . $this->Database .
                $this->ifadd($this->Host2, "host=") .
                $this->ifadd($this->Port, "port=") .
                $this->ifadd($this->User, "user=") .
                $this->ifadd($this->Password, "password=");
            $this->cualbase = 2;
            restore_error_handler();
            set_error_handler(array(&$this, 'errores'));

            if (!$this->PConnect) {
                $this->Link_ID = pg_connect($cstr);
            } else {
                $this->Link_ID = pg_pconnect($cstr);
            }

            restore_error_handler();
            if (!$this->Link_ID) {
//        $this->halt("connect() failed.");
                return 100;
            }
        }
        return 1;
    }

    function query($Query_String)
    {
        /* No empty queries, please, since PHP4 chokes on them. */
        if ($Query_String == "")
            /* The empty query string is passed on from the constructor,
             * when calling the class without a query, e.g. in situations
             * like these: '$db = new DB_Sql_Subclass;'
             */
            return 0;
        if ($this->cualbase == 1)
            $this->connect();
        else
            $this->connect2();

        if ($this->Debug){
            //printf("<br>Debug: query = %s<br>\n", $Query_String);
	}


        restore_error_handler();
        set_error_handler(array(&$this, 'errores')); // es necesario para evitar los warnings
        $this->Query_ID = pg_Exec($this->Link_ID, $Query_String);

        $this->Error = pg_ErrorMessage($this->Link_ID);

        restore_error_handler();
        $this->Row = 0;

        if ($this->Link_ID)
            $this->Error = pg_ErrorMessage($this->Link_ID);
        $this->Errno = ($this->Error == "") ? 0 : 1;
        if (!$this->Query_ID) {
            // las siguientes 2 lineas se descomentarizan para debuguear errores
            //$this->halt("Invalid SQL: ".$Query_String);
            //$this->errores();
            //$valor = $this->Error[8] . $this->Error[9] . $this->Error[10];
            //$this->ValorError = $valor + 0;
        }

        return $this->Query_ID;
    }

    function errores($no, $str, $file, $line, $ctx)
    {
//------
        /*
          // las siguientes lineas se descomentarizan para debuguear errores
           echo '<pre>';
                               echo 'no  : ' . $no . "\n";       //  no sirve de nada
                               echo 'str  : ' . $str . "\n";
                               echo 'file : ' . $file . "\n";
                               echo 'line : ' . $line . "\n";
                               echo 'ctx  : ';
                               print_r($ctx);
                               echo '</pre>';

            $valor =  $this->Error[8].$this->Error[9].$this->Error[10];
            $this->ValorError = $valor + 0;
            printf("<br>ERRores en pagina %s %d<br>",$valor,$this->ValorError);
        //    printf("<br>ERRores en pagina<br>");
        //---*/
    }


    function next_record()
    {
        $this->Record = @pg_fetch_array($this->Query_ID, $this->Row++);

        $this->Error = pg_ErrorMessage($this->Link_ID);
        $this->Errno = ($this->Error == "") ? 0 : 1;

        $stat = is_array($this->Record);
        if (!$stat && $this->Auto_Free) {
            pg_freeresult($this->Query_ID);
            $this->Query_ID = 0;
        }
        return $stat;
    }

    function seek($pos)
    {
        $this->Row = $pos;
    }

    function lock($table, $mode = "write")
    {
        if ($mode == "write") {
            $result = pg_Exec($this->Link_ID, "lock table $table");
        } else {
            $result = 1;
        }
        return $result;
    }

    function unlock()
    {
        return pg_Exec($this->Link_ID, "commit");
    }

    /* public: sequence numbers */
    function nextid($seq_name)
    {
        $this->connect();

        if ($this->lock($this->Seq_Table)) {
            /* get sequence number (locked) and increment */
            $q = sprintf("select nextid from %s where seq_name = '%s'",
                $this->Seq_Table,
                $seq_name);
            $id = @pg_Exec($this->Link_ID, $q);
            $res = @pg_Fetch_Array($id, 0);

            /* No current value, make one */
            if (!is_array($res)) {
                $currentid = 0;
                $q = sprintf("insert into %s values('%s', %s)",
                    $this->Seq_Table,
                    $seq_name,
                    $currentid);
                $id = @pg_Exec($this->Link_ID, $q);
            } else {
                $currentid = $res["nextid"];
            }
            $nextid = $currentid + 1;
            $q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
                $this->Seq_Table,
                $nextid,
                $seq_name);
            $id = @pg_Exec($this->Link_ID, $q);
            $this->unlock();
        } else {
            $this->halt("cannot lock " . $this->Seq_Table . " - has it been created?");
            return 0;
        }
        return $nextid;
    }

    function metadata($table = "")
    {
        $count = 0;
        $id = 0;
        $res = array();

        if ($table) {
            $this->connect();
            $id = pg_exec($this->Link_ID, "select * from $table");
            if ($id < 0) {
                $this->Error = pg_ErrorMessage($id);
                $this->Errno = 1;
                $this->halt("Metadata query failed.");
            }
        } else {
            $id = $this->Query_ID;
            if (!$id) {
                $this->halt("No query specified.");
            }
        }

        $count = pg_NumFields($id);

        for ($i = 0; $i < $count; $i++) {
            $res[$i]["table"] = $table;
            $res[$i]["name"] = pg_FieldName($id, $i);
            $res[$i]["type"] = pg_FieldType($id, $i);
            $res[$i]["len"] = pg_FieldSize($id, $i);
            $res[$i]["flags"] = "";
        }

        if ($table) {
            pg_FreeResult($id);
        }

        return $res;
    }

    function affected_rows()
    {
        return pg_cmdtuples($this->Query_ID);
    }

    function num_rows()
    {
        return pg_numrows($this->Query_ID);
    }

    function num_fields()
    {
        return pg_numfields($this->Query_ID);
    }

    function nf()
    {
        return $this->num_rows();
    }

    function np()
    {
        print $this->num_rows();
    }

    function f($Name)
    {
        return $this->Record[$Name];
    }

    function p($Name)
    {
        print $this->Record[$Name];
    }

    function r()
    {
        return $this->Record;
    }

    function halt($msg)
    {
        printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
        printf("<b>PostgreSQL Error</b>: %s (%s)<br>\n",
            $this->Errno,
            $this->Error);
        $cstr = "dbname=" . $this->Database .
            $this->ifadd($this->Host, "host=") .
            $this->ifadd($this->Port, "port=") .
            $this->ifadd($this->User, "user=") .
            $this->ifadd($this->Password, "password=");
        if ($this->PConnect) {
            pg_close($this->Link_ID);
        } else {
            pg_close($this->Link_ID);
        }
    }

    function table_names()
    {
        $this->query("select relname from pg_class where relkind = 'r' and not relname like 'pg_%'");
        $i = 0;
        while ($this->next_record()) {
            $return[$i]["table_name"] = $this->f(0);
            $return[$i]["tablespace_name"] = $this->Database;
            $return[$i]["database"] = $this->Database;
            $i++;
        }
        return $return;
    }

    function cerrar()
    {
        pg_close($this->Link_ID);
    }
}

?>
