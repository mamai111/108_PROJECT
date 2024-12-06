<?php

$user = "postgres";
$pass = "12345";

$db = new PDO("pgsql:dbname=BUSINESSDB host=localhost", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);