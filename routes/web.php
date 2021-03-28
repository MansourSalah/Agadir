<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

//*Categorie*//
//views
Route::get('/admin/categories', function () {return view('admin');});
Route::get('/admin/categories/ajout', function () {return view('admin');});
Route::get('/admin/categorie/detail/{id}',function () {return view('admin');});
//action
Route::get("/api/admin/categorie/first/{id}",[CategController::class,'first']);
Route::get("/api/admin/categorie/liste",[CategController::class,'liste']);
Route::post("/api/admin/categorie/ajouter",[CategController::class,'ajouter']);
Route::post("/api/admin/categorie/modifier",[CategController::class,'modifier']);
Route::post("/api/admin/categorie/supprimer",[CategController::class,'supprimer']);

//*Produit*//
//views
Route::get('/admin/produits', function () {return view('admin');});
Route::get('/admin/produits/ajout', function () {return view('admin');});
Route::get('/admin/produit/detail/{id}',function () {return view('admin');});
//action
Route::get("/api/admin/produit/first/{id}",[ProduitController::class,'first']);
Route::get("/api/admin/produit/liste",[ProduitController::class,'liste']);
Route::get("/api/admin/produit/where/{categ_id}",[ProduitController::class,'where']);
Route::post("/api/admin/produit/ajouter",[ProduitController::class,'ajouter']);
Route::post("/api/admin/produit/modifier",[ProduitController::class,'modifier']);
Route::post("/api/admin/produit/supprimer",[ProduitController::class,'supprimer']);

//*Interface client*//
Route::get('/', function () {return view('client');});//page des catégorie
Route::get('/produits/{categ_id}', function () {return view('client');});//pages des produits
Route::get('/produit/detail/{id}', function () {return view('client');});//detail de produit