<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorie;
use App\Models\Produit;

use Throwable;
use Validator;
use File;
//*********************************** */
// Veuillez me contacter si vous rencontrez des problèmes au niveau du code
//Nom: MANSOUR Salah Eddine 
//Phone: 0603048331
//Email: 19mansour94@gmail.com
//*********************************** */
class ProduitController extends Controller
{
    public function where($categ_id){//cette fonction pour filter les produit selon le catégorie
        try{
            return Produit::where('categ_id',$categ_id)->get();
        }catch(Throwable $e) {
            ErreurController::add('Produit','first',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        } 
    }
    public function first($id){//return le produit qui'a un ID sélectionné et le nom de catégorie
        try{
            $prod=Produit::findOrFail($id);
            $prod->image=asset("img/produit")."/".$prod->image;
            return ['produit'=>$prod,'categorie'=>Categorie::findOrFail($prod->categ_id)->nom];
        }catch(Throwable $e) {
            ErreurController::add('Produit','first',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function liste(Request $rq){//return la liste des produits
        try{
            return Produit::all();
        }catch(Throwable $e) {
            ErreurController::add('Produit','liste',$e);
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function ajouter(Request $rq){
        try {
            //authentifiction
            if(!parent::isAdmin()){//si l'utilisateur n'est pas authentifié
                return response()->json(['flag'=>false,'message'=>"Vous n'avez pas le droit d'effectuer cette opération"]);
            }
            $validation = Validator::make($rq->all(), [//les régles des attribus reçus
                'nom'=> 'required','prix'=>'required',
                'categ_id' => 'required','image'=>'required',
                'description'=>'required',
            ]);
            if(!$validation->passes()){//si les entrées ne respectent pas les règles
                return response()->json(['flag'=>false,'message'=>"Veuillez saisir toutes les informations"]);
            }
            //verifier l'existence du nom de produit
            if(Produit::where('nom',$rq->nom)->exists()){
                return response()->json(['flag'=>false,'message'=>"Le titre du déja existe, veuillez le changer"]);
            }
            //verfifier la catédorie
            if(!Categorie::where('id',$rq->categ_id)->exists()){
                return response()->json(['flag'=>false,'message'=>"Cette catégorie n'existe pas"]);
            }
            //ajouter le produit 
            $prod = new Produit();
            $prod->nom=$rq->nom;
            $prod->prix=$rq->prix;
            $prod->categ_id=$rq->categ_id;
            $prod->description=$rq->description;
            $prod->save();
            //charger l'image de produit dan le dossier public/img/produit
            $nomfile=$prod->id.rand(1111,9999).".".$rq->image->getClientOriginalExtension();
            $rq->image->move(public_path("img\produit"),$nomfile);
            $prod->image=$nomfile;
            $prod->save();
            return response()->json(['flag'=>true,'message'=>"Le produit a été ajouté avec succès"]);
        }catch(Throwable $e) {
            ErreurController::add('Produit','ajouter',$e);
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function supprimer(Request $rq){
        try{
            //authentifiction
            if(!parent::isAdmin()){//si l'utilisateur n'est pas authentifié
                return response()->json(['flag'=>false,'message'=>"Vous n'avez pas le droit d'effectuer cette opération"]);
            }
            $prod=Produit::where('id',$rq->prod_id);
            if(!$prod->exists()){//verfifier l'exsitance du produit
                return response()->json(['flag'=>false,'message'=>"Impossible!! Ce produit n'existe pas."]);
            }
            $prod=$prod->first();
            //supprimer l'image de produit si'il existe
            if(File::exists(public_path()."\img\produit\\".$prod->image))
                File::delete( public_path()."\img\produit\\".$prod->image);
            $prod->first()->delete();//Puis supprimer le produit
            return response()->json(['flag'=>true,'message'=>"La suppression a ete effectué avec success"]);
        }catch(Throwable $e) {
            ErreurController::add('Produit','supprimer',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function modifier(Request $rq){
        try{
            //authentifiction
            if(!parent::isAdmin()){//si l'utilisateur n'est pas authentifié
                return response()->json(['flag'=>false,'message'=>"Vous n'avez pas le droit d'effectuer cette opération"]);
            }
            $validation = Validator::make($rq->all(), [//Définir les régles des entrées
                'nom'=> 'required','description'=>'required','prix'=>'required','prod_id'=>'required',
            ]);
            if(!$validation->passes()){//verfier si les régles sont respectées
                return response()->json(['flag'=>false,'message'=>"Veuillez entrer toutes les informations."]);
            }
            $prod=Produit::where('id',$rq->prod_id);
            if(!$prod->exists()){
                return response()->json(['flag'=>false,'message'=>"Impossible!! Cette catégorie n'existe pas."]);
            }
            $prod=$prod->first();
            if($rq->nom!=$prod->nom){//tester est ce que le client chnage le nom de categorie
                if(Produit::where('nom',$rq->nom)->exists()){//verifier s'il existe déja dans le table
                    return response()->json(['flag'=>false,'message'=>"Impossible!! Veuillez entrer un autre nom de produit."]); 
                }
            }
            $prod->nom=$rq->nom;
            $prod->prix=$rq->prix;
            $prod->description = $rq->description;
            if($rq->has('image')){//si l'utilisateur change l'image de produit
                File::delete( public_path()."\img\produit\\".$prod->image);
                $rq->image->move(public_path("\img\produit"),$prod->image); 
            }            
            $prod->save();
            return response()->json(['flag'=>true,'message'=>"La modification a été effectué avec success",'imageLink'=>asset("img/produit")."/".$prod->image]);
        }catch(Throwable $e) {
            ErreurController::add('Categorie','modifier',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
}
