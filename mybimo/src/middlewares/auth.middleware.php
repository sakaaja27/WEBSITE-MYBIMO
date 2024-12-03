<?php
    session_start();
    if($_SESSION["username"]==""||!isset($_SESSION["username"])||$_SESSION["username"]==null){
      header(header:"Location: /WEBSITE%20MYBIMO/mybimo/src/auth/login.php");
      exit();
    }
 ?>