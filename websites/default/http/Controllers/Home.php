<?php
namespace http\Controllers;

class Home
{
    public function __construct()
    {

    }
    public function index()
    {
        global $pdo;
        $sql = "SELECT *
            FROM lot
            WHERE date >= CURDATE()
            ORDER BY date ASC
            LIMIT 10;
            ";
        $stm = $pdo->prepare($sql);
        $stm->execute();
        $auctions = $stm->fetchAll(\PDO::FETCH_CLASS, "stdclass", []);
        return [
            'template' => 'home.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'auctions' => $auctions
            ]
        ];
    }
}