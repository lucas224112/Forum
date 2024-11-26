<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "chat_db";

    $connect = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($connect->connect_error) {
        die("Erro de conexão: " . $connect->connect_error);
    }
?>