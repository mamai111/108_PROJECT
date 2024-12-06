<?php

$user = "regular_user";
$pass = "regular";

$db = new PDO("pgsql:dbname=BUSINESSDB host=localhost", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);