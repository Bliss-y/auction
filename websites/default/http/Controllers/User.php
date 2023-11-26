<?php
namespace http\Controllers;

use Framework\DatabaseTable;
use http\Model\Auction;

class User
{
    public DatabaseTable $table;
    public function __construct()
    {
        $this->table = new DatabaseTable('client', 'id');
    }

    public function delete()
    {
        $this->table->delete($_GET['id']);
        header('Location: /admin/categories');
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
                'add' => '/addClient',
                'data' => (new DatabaseTable('client', 'id'))->findAll(),
                'data_title' => 'Clients ',
                'headers' => ['name', 'view/edit', 'sales', 'purchases'],
                'cors' => ['name'],
                'actions' => [['/editClient', 'edit/view'], ['/sales', 'sales'], ['/purchases', 'purchaes']]
            ]
        ];
    }

    public function soldItem()
    {
        $id = $_GET['id'];
        $user = $this->table->find('id', $id)[0];
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data_title' => 'Sales by: ' . $user->name,
                'headers' => ['name', 'status', 'view'],
                'actions' => [['/catalogue', 'view']],
                'cors' => ['title', 'sold'],
                'data' => (new DatabaseTable('auctions'))->find('seller', $id),

            ]
        ];
    }

    public function boughtItem()
    {
        $id = $_GET['id'];
        $user = $this->table->find('id', $id)[0];
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data_title' => 'Purchases of: ' . $user->name,
                'headers' => ['name', 'price', 'status', 'view'],
                'cors' => ['title', 'price', 'sold'],
                'actions' => [['/catalogue', 'view']],
                'data' => (new DatabaseTable('auctions'))->find('buyer', $id),
            ]
        ];
    }

    public function edit()
    {
        return [
            'template' => 'userForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'client' => $this->table->find('id', $_GET['id'])[0]
            ]
        ];
    }

    public function editS()
    {
        $_POST['id'] = $_GET['id'];
        $this->table->update($_POST);
        header("Location: /admin/users");
    }

    public function add()
    {
        return [
            'template' => 'userForm.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
            ]
        ];
    }

    public function addS()
    {
        global $pdo;
        $uid = uniqid();

        $this->table->insert($_POST);
        (new DatabaseTable('users'))->insert(['username' => $uid, 'password' => $uid, 'client' => $pdo->lastInsertId()]);
        echo "generated username: " . $uid . " password is same as the username. this is the only time it will be shown so please note it down.";
        exit;
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function login()
    {
        return [
            'template' => 'login.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
            ]
        ];
    }
    public function loginS()
    {
        if ($_POST['username'] == "admin" && $_POST['password'] == "adminpassword") {
            $_SESSION['admin'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['client'] = null;
            $_SESSION['loggedIn'] = true;
            header("Location: /");
            exit;
        }

        $user = (new DatabaseTable('users'))->find('username', $_POST['username']);

        if (count($user) > 0 && $user[0]->password == $_POST['password']) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['id'] = $user[0]->id;
            $_SESSION['username'] = $user[0]->username;
            $_SESSION['admin'] = $user[0]->admin;
            if ($user[0]->admin == 0) {
                $_SESSION['client'] = $user[0]->client;
            }
            header("Location: /");
            exit;
        }
        header("Location: /login");
        exit;

    }
    public function listAuctions()
    {

        $user = (new DatabaseTable('user', 'id'))->find('id', $_GET['id']);
        $user = $user[0];
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data' => (new Auction())->find('seller', $_GET['id']),
                'data_title' => 'Items on Sell By: ' . $user->name,
                'headers' => ['name', 'view/edit', 'delete'],
                'cors' => ['name'],
                'actions' => [['/editUser', 'manage items'], ['/deleteUser', 'delete']]
            ]
        ];
    }



    public function listBought()
    {

        $user = (new DatabaseTable('user', 'id'))->find('id', $_GET['id']);
        $user = $user[0];
        return [
            'template' => 'list.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'data' => (new Auction())->find('buyer', $_GET['id']),
                'data_title' => 'Items Bought By: ' . $user->name,
                'headers' => ['name', 'view/edit', 'delete'],
                'cors' => ['name'],
                'actions' => [['/editUser', 'manage items'], ['/deleteUser', 'delete']]
            ]
        ];
    }
}