<?php
namespace http\Controllers;

use Framework\DatabaseTable;

class Category
{
    public $table;
    public \http\Model\Category $category;
    public function __construct()
    {
        global $pdo;
        $this->table = new DatabaseTable('categories', 'id');
        $this->category = new \http\Model\Category();
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
                'add' => '/addCategory',
                'data' => \http\Model\Category::getCategories(),
                'data_title' => 'Categories',
                'headers' => ['name', 'view/edit', 'delete', 'add Auction'],
                'cors' => ['name'],
                'actions' => [['/editCategory', 'edit/view'], ['/deleteCategory', 'delete'], ['/addAuction', 'add auction']]
            ]
        ];
    }

    public function add()
    {

        return [
            'template' => 'categoryform.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
            ]
        ];
    }
    public function save()
    {
        $this->category->database->insert($_POST);
        header("Location:/addCategory");
        exit;
    }

    public function editS()
    {
        $_POST['id'] = $_GET['id'];
        $this->category->database->update($_POST);
        header("Location: /admin/categories");
    }
    public function edit()
    {
        $category = $this->category->database->find("id", $_GET['id']);
        if (count($category) == 0) {
            return [
                'template' => 'notFound.html.php',
                'title' => '404!',
                'variables' => [

                ]
            ];

        }

        return [
            'template' => 'categoryform.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'category' => $category[0]
            ]
        ];

    }
    public function editField()
    {
        $prev = $_GET['prev'];
        $category = $this->category->database->find('id', $_GET['id'])[0];
        $catfield = $category->getFieldsArray();
        if (!in_array($prev, $catfield)) {
            header('Location: /admin/categories');
            exit;
        }

        return [
            'template' => 'editField.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'field' => $prev,
                'category' => $category
            ]
        ];
    }
    public function editFieldSave()
    {
        $prev = $_GET['prev'];
        $category = $this->category->database->find('id', $_GET['id'])[0];
        $catfield = $category->getFieldsArray();
        if (!in_array($prev, $catfield)) {
            header('Location: /admin/categories');
            exit;
        }
        $category->editFields($_POST, $prev);
        header('Location: /editCategory/' . $category->id);
    }

    public function addField()
    {
        $category = $this->category->database->find('id', $_GET['id'])[0];
        return [
            'template' => 'editField.html.php',
            'title' => 'Fotheby\'s Home',
            'variables' => [
                'category' => $category
            ]
        ];

    }

    public function addFieldSave()
    {
        $category = $this->category->database->find('id', $_GET['id'])[0];
        $category->addField($_POST);
        header("Location: /editCategory/" . $_GET['id']);
    }
    public function removeField()
    {
        $category = $this->category->database->find('id', $_GET['id'])[0];
        $category->removeField($_GET['field']);
        header("Location: /editCategory/" . $_GET['id']);
    }
}