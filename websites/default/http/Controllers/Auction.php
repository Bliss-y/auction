<?php
namespace http\Controllers;

use Framework\DatabaseTable;
use http\Model\Category;

class Auction
{
    public \http\Model\Auction $auction;
    public function __construct()
    {
        $this->auction = new \http\Model\Auction();
    }
    public function index()
    {
    }

    public function list()
    {
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data' => $this->auction->getAll(),
                'data_title' => 'Auction Item',
                'headers' => ['title', 'view/edit', 'delete'],
                'cors' => ['title'],
                'actions' => [['/editAuction', 'edit/view'], ['/deleteAuction', 'delete']]
            ]
        ];
    }
    public function lotItems()
    {
        $lotname = (new DatabaseTable('lot', 'id'))->find('id', $_GET['id'])[0]->title;
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data' => $this->auction->find('lot', $_GET['id']),
                'data_title' => 'Items in ' . $lotname,
                'headers' => ['title', 'Set Status', 'edit/view'],
                'cors' => ['title'],
                'actions' => [['/setStatus', 'Set status'], ['/editAuction', 'edit/view']]
            ]
        ];

    }
    public function setSold()
    {
        $users = (new DatabaseTable('client'))->findAll();
        $auction = $this->auction->getById($_GET['id']);
        $bid = (new DatabaseTable('bids'))->find('auction', $auction->id);
        return [
            'template' => 'auctionSell.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'auction' => $auction,
                'buyers' => $users,
                'bid' => $bid
            ]
        ];


    }

    public function bid()
    {
        $user = (new DatabaseTable('users'))->find('id', $_SESSION['id'])[0];
        $user_client = (new DatabaseTable('client'))->find('id', $user->client)[0];
        $_POST['client'] = $user_client->id;
        $_POST['auction'] = $_GET['id'];
        (new DatabaseTable('bids'))->insert($_POST);
        header('Location: /catalogue/' . $_GET['id']);
    }
    public function setSoldS()
    {
        $id = $_GET['id'];
        $_POST['id'] = $id;
        foreach ($_POST as $p => $a) {
            if ($a == '') {
                $_POST[$p] = null;
            }
        }
        $this->auction->database->update($_POST);
        header("Location: /setStatus/" . $id);
    }

    public function delete()
    {
        $this->auction->database->delete($_GET['id']);
        header('Location: /admin/auctions');
    }

    public function deleteLot()
    {
        (new DatabaseTable('lot'))->delete($_GET['id']);
        header('Location: /admin/lots');
    }

    public function search()
    {
        $keywords = $_GET['q'];
        $keywords = explode(' ', $keywords);

        global $pdo;

        $sql = "SELECT * FROM auctions WHERE ";
        $conditions = [];
        foreach ($keywords as $keyword) {
            $conditions[] = "title LIKE :keyword{$keyword} OR description LIKE :keyword{$keyword}";
        }
        $sql .= implode(" OR ", $conditions);
        $stmt = $pdo->prepare($sql);
        foreach ($keywords as $keyword) {
            $stmt->bindValue(":keyword{$keyword}", '%' . $keyword . '%');
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = ['results' => $results, $query = $stmt];
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();

    }

    public function advanceSearch()
    {
        return [
            'template' => 'searchform.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
            ]
        ];
    }

    public function advanceSearchRes()
    {
        // Define conditions based on provided parameters
        global $pdo;
        $sql = "SELECT * FROM auctions WHERE 1";
        $conditions = [];

        ['title' => $title, 'date' => $date, 'artist_name' => $artist, 'price' => $price, 'category' => $category, 'classification' => $classification] = $_POST;
        if (!empty($title)) {
            $conditions[] = "title LIKE :title";
        }

        if (!empty($date)) {

            $conditions[] = "date = :date";
        }

        if (!empty($artist)) {
            $conditions[] = "artist_name LIKE :artist";
        }

        if (!empty($price)) {
            $conditions[] = "price = :price";
        }

        if (!empty($category)) {
            $conditions[] = "category = :category";
        }

        if (!empty($classification)) {
            $conditions[] = "classification = :classification";
        }

        // Combine conditions using AND
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters with non-empty values
        if (!empty($title)) {
            $stmt->bindValue(':title', '%' . $title . '%', \PDO::PARAM_STR);
        }

        if (!empty($date)) {

            $stmt->bindValue(':date', $date, \PDO::PARAM_STR);
        }

        if (!empty($artist)) {
            $stmt->bindValue(':artist', $artist, \PDO::PARAM_STR);
        }

        if (!empty($price)) {
            $stmt->bindValue(':price', $price, \PDO::PARAM_INT);
        }

        if (!empty($category)) {
            $stmt->bindValue(':category', $category, \PDO::PARAM_STR);
        }

        if (!empty($classification)) {
            $stmt->bindValue(':classification', $classification, \PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();

        // Fetch the results as an associative array
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);
        return [
            'template' => 'searchform.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'results' => $results,
                'categories' => Category::getCategories(),

            ]
        ];

    }

    public function lotList()
    {
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data' => (new DatabaseTable('lot', 'id'))->findAll(),
                'data_title' => 'Auctions',
                'add' => '/addLot',
                'headers' => ['title', 'Manage', 'view/edit', 'delete'],
                'cors' => ['title'],
                'actions' => [['/admin/lot/items', 'manage items'], ['/editLot', 'edit/view'], ['/deleteLot', 'delete']]
            ]
        ];
    }

    public function addLot()
    {
        return [
            'template' => 'lotForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
            ]
        ];

    }

    public function catalogue()
    {
        global $pdo;
        $auction = $this->auction->getById($_GET['id']);
        $lot = (new DatabaseTable('lot'))->find('id', $auction->lot)[0];
        if (isset($_SESSION['loggedIn']) && isset($_SESSION['client']) && $_SESSION['client']) {
            $user = (new DatabaseTable('users'))->find('id', $_SESSION['id'])[0];
            $user_client = (new DatabaseTable('client'))->find('id', $user->client);
            // var_dump($user);
        }
        $sql = "SELECT
            b.id AS bid_id,
            b.amount AS bid_amount,
            c.id AS client_id,
            c.name AS client_name
            FROM
                bids AS b
            INNER JOIN
                client AS c ON b.client = c.id
            WHERE
                b.auction = " . $auction->id . " ORDER BY b.amount DESC LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $bid = $stmt->fetchAll(\PDO::FETCH_CLASS, 'stdclass', []);

        return [
            'template' => 'catalogue.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'auction' => $auction,
                'lot' => $lot,
                'bid' => $bid,
                'client' => $user_client ?? null
            ]
        ];
    }
    public function addLotS()
    {
        $lot = new DatabaseTable('lot', 'id');
        if ($_POST['date'] == '') {
            $_POST['date'] = null;
        }
        $lot->insert($_POST);
        header('Location: /admin/lots');
        exit;
    }
    public function itemsListLot()
    {
        $id = $_GET['id'];
        $lot = (new DatabaseTable('lot'))->find("id", $id)[0];
        $auctions = $this->auction->database->find('lot', $id);
        return [
            'template' => 'items.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'lot' => $lot,
                'auctions' => $auctions
            ]
        ];

    }
    public function editLot()
    {
        $lot = new DatabaseTable('lot', 'id');
        return [
            'template' => 'lotForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'lot' => $lot->find('id', $_GET['id'])[0]
            ]
        ];
    }
    public function editLotS()
    {
        $lot = new DatabaseTable('lot', 'id');
        $_POST['id'] = $_GET['id'];
        $lot->update($_POST);
        header("Location: /admin/lots");
        exit;
    }


    public function add()
    {
        $categories = Category::getCategories();
        if (!isset($categories[$_GET['id']])) {
            return [
                'template' => 'notFound.html.php',
                'title' => 'Fotheby\'s Home',
                'variables' => [
                ]
            ];
        }
        return [
            'template' => 'auctionForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'buyers' => (new DatabaseTable('client'))->findAll(),
                'lots' => (new DatabaseTable('lot'))->findAll(),
                'category' => $categories[$_GET['id']]
            ]
        ];
    }
    public function save()
    {
        global $pdo;
        $_POST['category'] = $_GET['id'];
        $this->auction->add($_POST);
        $lastid = $pdo->lastInsertId();
        $this->auction->saveImageFromPost('./images/auctions', 'file', '' . $lastid . '.jpg', );
        header('Location: /addAuction/' . $_GET['id']);
        exit;
    }

    public function edit()
    {
        $categories = Category::getCategories();
        $auction = $this->auction->getById($_GET['aid']);
        $category = $categories[$auction->category];
        if ($auction == null) {
            return [
                'template' => 'notFound.html.php',
                'title' => 'Fotheby\'s Home',
                'variables' => [
                    'buyers' => (new DatabaseTable('client'))->findAll(),
                    'category' => $category,
                    'lots' => (new DatabaseTable('lot'))->findAll(),
                ]
            ];
        }
        return [
            'template' => 'auctionForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'buyers' => (new DatabaseTable('client'))->findAll(),
                'category' => $category,
                'lots' => (new DatabaseTable('lot'))->findAll(),
                'auction' => $auction
            ]
        ];
    }

    public function editS()
    {
        $auction = $this->auction->getById($_GET['aid']);
        $_POST['category'] = $auction->category;
        $_POST['id'] = $_GET['aid'];
        $_GET['id'] = $_POST['category'];
        $this->auction->edit($_POST);
        $this->auction->saveImageFromPost('./images/auctions', 'file', '' . $_POST['id'] . '.jpg', );
        header('Location: /editAuction/' . $_GET['aid']);
        exit;
    }
}