<?php

$user = "business_owner";
$pass = "owner";

$db = new PDO("pgsql:dbname=BUSINESSDB host=localhost", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);