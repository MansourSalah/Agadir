<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function ajouter(Request $rq){
        try {
            $validation = Validator::make($rq->all(), [
                'nom'=> 'required','prix'=>'required',
                'categ_id' => 'required','image'=>'required',
                'description'=>'required',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Veuillez saisir toutes les informations","الرجاء إدخال كافة المعلومات");
            }
            /*
            //verifier l'existence du titre
            if(Produit::where('titre',$rq->titre)->exists()){
                return parent::response(false,"Le titre déja existe, veuillez le changer","العنوان موجود بالفعل ، يرجى تغييره");
            }
            //verfifier la catédorie
            
            if(!Categ::where('id',$rq->categ)->exists()){
                return parent::response(false,"Cette catégorie n'existe pas","هذه الفئة غير موجودة");
            }
            //ajouter les images
            $rq->image1->getClientOriginalExtension();
            $prod = new Produit();
            $prod->nom=$rq->nom;
            $prod->prix=$rq->prix;
            $prod->categ=$rq->categ;
            $prod->description=$rq->description;
            $prod->save();
            $nomfile=$prod->id.rand(1111,9999).$rq->image1->getClientOriginalExtension();
            $rq->image1->move(public_path("assets\img\produit"),$nomfile);
            $prod->image1=$nomfile;
            $prod->save();
            */
            return parent::response(true,"Le produit a été ajouté avec succès","تمت إضافة المنتوج بنجاح");
        }catch(Throwable $e) {
            ErreurController::add('Prod','ajouter',$e);
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
}
