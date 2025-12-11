<?php
$cnx=new Mysqli("localhost","root","","digitalGarden");
if($cnx->connect_error){
    die("Erreur de connexion: ".$cnx->connect_error);
}
?>