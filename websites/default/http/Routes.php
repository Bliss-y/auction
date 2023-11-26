<?php
namespace http\Controllers;

use Framework\Routes;

/**
 * Routes::get or Routes::post route=string, $params= [string], $controller = [$classname, functionname=string], $middleware=function/callback
 */

$loginrequired = function () {
	if (!isset($_SESSION['loggedIn']) || $_SESSION['admin'] != 1) {
		header("Location: /");
		exit;
	}
	return true;
};

$clientrequired = function () {
	if (!isset($_SESSION['loggedIn']) || $_SESSION['admin'] != 0) {
		header("Location: /");
		exit;
	}
	return true;
};

$difaultmid = function () {
	return true;
};

Routes::get("/home", [], [Home::class, 'index'], function () {
	return true;
});

Routes::get("/", [], [Home::class, 'index'], function () {
	return true;
});

Routes::get("/addCategory", [], [Category::class, 'add'], $loginrequired);
Routes::get("/editCategory", ['id'], [Category::class, 'edit'], $loginrequired);
Routes::post("/editCategory", ['id'], [Category::class, 'editS'], $loginrequired);
Routes::post("/addCategory", [], [Category::class, 'save'], $loginrequired);
Routes::post("/addAuction", ['id'], [Auction::class, 'save'], $loginrequired);
Routes::get("/addAuction", ['id'], [Auction::class, 'add'], $loginrequired);
Routes::post("/editAuction", ['aid'], [Auction::class, 'editS'], $loginrequired);
Routes::get("/editAuction", ['aid'], [Auction::class, 'edit'], $loginrequired);
Routes::get("/addCategoryField", ['id'], [Category::class, 'addField'], $loginrequired);
Routes::post("/addCategoryField", ['id'], [Category::class, 'addFieldSave'], $loginrequired);
Routes::get("/editCategoryField", ['id', 'prev'], [Category::class, 'editField'], $loginrequired);
Routes::post("/editCategoryField", ['id', 'prev'], [Category::class, 'editFieldSave'], $loginrequired);
Routes::get("/removefield", ['id', 'field'], [Category::class, 'removeField'], $loginrequired);
Routes::get("/admin/categories", [], [Category::class, 'list'], $loginrequired);
Routes::get("/admin/auctions", [], [Auction::class, 'list'], $loginrequired);
Routes::get("/admin/lots", [], [Auction::class, 'lotList'], $loginrequired);

Routes::get("/addLot", [], [Auction::class, 'addLot'], $loginrequired);
Routes::post("/addLot", [], [Auction::class, 'addLotS'], $loginrequired);
Routes::get("/editLot", ['id'], [Auction::class, 'editLot'], $loginrequired);
Routes::post("/editLot", ['id'], [Auction::class, 'editLotS'], $loginrequired);
Routes::get("/admin/lot/items", ['id'], [Auction::class, 'lotItems'], $loginrequired);
Routes::get("/addClient", [], [User::class, 'add'], $loginrequired);
Routes::post("/addClient", [], [User::class, 'addS'], $loginrequired);
Routes::get("/admin/users", [], [User::class, 'list'], $loginrequired);
Routes::get("/editClient", ['id'], [User::class, 'edit'], $loginrequired);
Routes::post("/editClient", ['id'], [User::class, 'editS'], $loginrequired);

Routes::get("/setStatus", ['id'], [Auction::class, 'setSold'], $loginrequired);
Routes::post("/setStatus", ['id'], [Auction::class, 'setSoldS'], $loginrequired);

Routes::get("/sales", ['id'], [User::class, 'soldItem'], $clientrequired);
Routes::get("/purchases", ['id'], [User::class, 'boughtItem'], $clientrequired);

Routes::get("/search", [], [Auction::class, 'search'], $difaultmid);
Routes::get("/search/advanced", [], [Auction::class, 'advanceSearch'], $difaultmid);
Routes::post("/search/advanced", [], [Auction::class, 'advanceSearchRes'], $difaultmid);
Routes::get("/catalogue", ['id'], [Auction::class, "catalogue"], $difaultmid);
Routes::get("/login", [], [User::class, 'login'], $difaultmid);
Routes::post("/login", [], [User::class, 'loginS'], $difaultmid);
Routes::get("/items", ['id'], [Auction::class, 'itemsListLot'], $difaultmid);
Routes::get("/logout", [], [User::class, 'logout'], $difaultmid);
Routes::get("/deleteAuction", ['id'], [Auction::class, 'delete'], $loginrequired);
Routes::get("/deleteLot", ['id'], [Auction::class, 'deleteLot'], $loginrequired);
Routes::get("/deleteCategory", ['id'], [Category::class, 'delete'], $loginrequired);
Routes::post("/bid", ['id'], [Auction::class, 'bid'], $clientrequired);