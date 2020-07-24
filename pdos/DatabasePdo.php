<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "13.124.159.68";
        $DB_NAME = "zigzag";
        $DB_USER = "judy";
        $DB_PW = "50525j573^^";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
