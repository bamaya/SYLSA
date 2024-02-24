<?php

if (!empty($_POST["btningresar"])){
    if (empty($_POST["usuario"]) and !empty($_POST["password"])){
    } else{
            echo "Los Campos estan Vacios";
        }
}

?>