<?php
session_start();
use http\Model\Category;

require '../autoload.php';
require '../http/Routes.php';
require '../database.php';
const DEVELOPMENT_SERVER = true;
$categories = Category::getCategories();
$entryPoint = new \Framework\EntryPoint();

$entryPoint->run();