<?php
    require_once('./config/config.php');

    function getAllSeries() {
        $dbh = dbconnect();
        $query = "SELECT * FROM articles";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $articles = $stmt->fetchAll();
        return $articles;

    }

?>