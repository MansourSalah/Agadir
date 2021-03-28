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

//*authentification*//
//view
Route::get("/login",function () {return view('client');});
Route::get("/mot-passe-oublie",function () {return view('client');});
Route::get("/inscrire",function () {return view('client');});
Route::get("/nouveau-mot-passe/{token}",function () {return view('client');});
Route::get("/sendMail",function () {return view('client');});
Route::get("/verifier",function () {return view('client');});
//action
Route::post('/api/logout',[AuthController::class,'logout']);
Route::post("/api/verifier",[AuthController::class,'verifier']);
Route::post("/api/compte/add",[AuthController::class,'add']);
Route::post("/api/compte/login",[AuthController::class,'login']);
Route::post("/api/mot-passe-oublie/sendMail",[AuthController::class,'sendMail_changePassword']);
Route::post("/api/mot-passe-oublie/change",[AuthController::class,'changePassword']);