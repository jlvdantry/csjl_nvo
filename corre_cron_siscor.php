<?
set_time_limit(0);
require("siscor_cron_class.php");
include("ProcessHandler.class.php");
if(ProcessHandler::isActive())
{
   echo "Already running!\n";
}else
{
   ProcessHandler::activate();
   session_register("escron");
   $escron='si';
   require_once("conneccion.php");
   $v = new siscor_cron();
   $v->connection=$connection;
   $v->que_proceso();
   session_unregister("escron");
   unlink ("/tmp/pid.php");
 }
?>

