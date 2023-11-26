<?php
namespace http\Model;

use Framework\DatabaseTable;

class Auction
{
    public DatabaseTable $database;
    public int $id;
    public array $category_fields;
    public $title;
    public $description;
    public $artist_name;
    public $higher_est;
    public $lower_est;
    public $lot;
    public $date;
    public $classification;
    public $sold;
    public $price;
    public $buyer, $seller, $comment;
    public $category;
    public $auction_date;
    public static $fields = ['id', 'title', 'description', 'seller', 'classification', 'artist_name', 'higher_est', 'lower_est', 'date', 'category', 'lot'];
    public function __construct()
    {
        $this->database = new DatabaseTable('auctions', 'id', self::class);
    }

    public function getById($id)
    {
        $a = $this->database->find('id', $id);
        if (!count($a))
            return null;
        $a = $a[0];
        $a->getCategoryFields();
        return $a;
    }
    public function getAll()
    {
        return $this->database->findAll();
    }
    public function find($field, $id)
    {
        return $this->database->find($field, $id);
    }

    public function upcoming()
    {
        global $pdo;
        $stmt = $pdo->prepare("select * from auctions where auction_date > " . date("y-m-d h:i:sa") . " order by auction_date asc limit 5");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetchAll();
    }

    public function add($data)
    {
        global $pdo;
        $category_feilds = Category::getCategories()[$data['category']]->getFieldsArray();
        $category_table = new DatabaseTable('category_fields', 'id');
        $auction_data = [];
        foreach (self::$fields as $f) {
            if ($f == 'id')
                continue;
            $auction_data[$f] = $data[$f];
        }
        $this->database->insert($auction_data);
        $id = $pdo->lastInsertId();
        foreach ($category_feilds as $cf) {
            $category_feilds = [];
            $category_table->insert(['field' => $cf, 'value' => $data[$cf], 'category' => $data['category'], 'auction' => $id]);
        }
    }
    public function edit($data)
    {

        $category_feilds = Category::getCategories()[$data['category']]->getFieldsArray();
        global $pdo;
        try {
            if ($data['date'] == '') {
                $data['date'] = null;
            }
            $auctiondata = [];
            foreach (self::$fields as $f) {
                $auctiondata[$f] = $data[$f];
            }
            $category_table = new DatabaseTable('category_fields', 'id');
            if (isset($auctiondata['id'])) {
                $stmt = $pdo->prepare("delete from category_fields where auction =" . $auctiondata['id']);
                $stmt->execute();
            }
            foreach ($category_feilds as $cf) {
                $category_feilds = [];
                $category_table->save(['field' => $cf, 'value' => $data[$cf], 'category' => $data['category'], 'auction' => $auctiondata['id']]);
            }
            $this->database->save($auctiondata);
        } catch (\Exception $e) {
            return "error";
        }
    }
    function saveImageFromPost($uploadDir, $fileInputName, $newfilename)
    {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $tmpFileName = $_FILES[$fileInputName]['tmp_name'];
            $uploadPath = $uploadDir . '/' . $newfilename;

            if (move_uploaded_file($tmpFileName, $uploadPath)) {
                return $uploadPath;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getCategoryFields()
    {
        global $pdo;
        $stmt = $pdo->prepare("select * from category_fields where auction = " . $this->id);
        $stmt->execute();
        $data = $stmt->fetchAll();
        foreach ($data as $d) {
            $this->category_fields[$d['field']] = $d['value'];
        }
        return $data;
    }

}