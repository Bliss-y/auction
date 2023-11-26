<?php
namespace http\Model;

use Framework\DatabaseTable;

class Category
{
    private $category_fields_array;
    private static $categories;
    public $category_fields;
    public $id;
    public $name;
    public $description;
    public DatabaseTable $database;

    public static function getCategories()
    {
        if (!isset($categories)) {
            self::$categories = (new Category)->database->findAll();
            $arr = [];
            foreach (self::$categories as $c) {
                $arr[$c->id] = $c;
            }
        }
        self::$categories = $arr;
        return self::$categories;
    }
    public function __construct()
    {
        $this->database = new DatabaseTable('categories', 'id', self::class);
    }

    public function edit($data)
    {
        $this->database->save($data);
    }
    public function addField($data)
    {
        global $pdo;
        $this->getFieldsArray();
        array_push($this->category_fields_array, $data['field']);
        $stmt = $pdo->prepare("update categories SET category_fields ='" . implode(' ', $this->category_fields_array) . "' where id=" . $this->id);
        $stmt->execute();
    }

    public function removeField($field)
    {
        global $pdo;
        $this->getFieldsArray();
        array_splice($this->category_fields_array, array_search($field, $this->category_fields_array), 1);
        $stmt = $pdo->prepare("delete from category_fields where category=" . $this->id . " field='" . $field . "'");
        $stmt = $pdo->prepare("update categories SET category_fields ='" . implode(' ', $this->category_fields_array) . "' where id=" . $this->id);
        $stmt->execute();
    }
    public function editFields(array $data, string $prev)
    {
        global $pdo;
        $this->getFieldsArray();
        if (in_array($prev, $this->category_fields_array)) {

            $stmt = $pdo->prepare("update category_fields SET field ='" . $data['field'] . "' where category=" . $this->id . " AND field='" . $prev . "'");
            $stmt->execute();
        }
        $this->category_fields_array[array_search($prev, $this->category_fields_array)] = $data['field'];
        $stmt = $pdo->prepare("update categories SET category_fields ='" . implode(' ', $this->category_fields_array) . "' where id=" . $this->id);
        $stmt->execute();
    }


    public function getFieldsArray()
    {
        if (!isset($this->category_fields_array)) {
            $this->category_fields_array = explode(' ', $this->category_fields);
        }
        return $this->category_fields_array;
    }
}