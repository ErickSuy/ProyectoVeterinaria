<?php
/**
 * Created by PhpStorm.
 * User: EdwinMac-donall
 * Date: 21/09/14
 * Time: 05:56 AM
 */

class General_SQL {

    function end() {
        return "END";
    }

    function begin() {
        return "BEGIN";
    }

    function begin_transaction() {
        return "BEGIN TRANSACTION;";
    }

    function begin_work() {
        return "BEGIN WORK";
    }

    function commit() {
        return "COMMIT";
    }

    function commit_transaction() {
        return "COMMIT TRANSACTION;";
    }

    function rollback() {
        return "ROLLBACK";
    }

    function gconnect($user,$password,$dbname,$host,$port){
        return "user=" . $user . " password=" . $password . " dbname=" . $dbname . " host=" . $host . " port=" . $port;
    }

}
?>