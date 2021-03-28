<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorie;
use App\Models\Produit;

use Throwable;
use Validator;
use File;
//*********************************** */
// Veuillez me contacter si vous rencontrez des problème au niveau du code
//Nom: MANSOUR Salah Eddine 
//Phone: 0603048331
//Email: 19mansour94@gmail.com
//*********************************** */
class CategController extends Controller
{
    public function first($id){
        try{
            $categ=Categorie::findOrFail($id);
            $categ->image=asset("img/categorie")."/".$categ->image;
            return $categ;
        }catch(Throwable $e) {
            ErreurController::add('Categorie','first',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function liste(){
        try{
            return Categorie::all();
        }catch(Throwable $e) {
            ErreurController::add('Categorie','liste',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function ajouter(Request $rq){
        try{
            //authentifiction
            $validation = Validator::make($rq->all(), [
                'nom'=> 'required','description'=>'required','image'=>'required',
            ]);
            if(!$validation->passes()){
                return response()->json(['flag'=>false,'message'=>"Veuillez entrer toutes les informations."]);
            }
            if(Categorie::where('nom',$rq->nom)->exists()){
                return response()->json(['flag'=>false,'message'=>"Veuillez changer le titre de la catégorie."]);
            }
            $categ = new Categorie();
            $categ->nom=$rq->nom;
            $categ->description=$rq->description;
            $categ->save();
            $nomfile=$categ->id.rand(1111,9999).".".$rq->image->getClientOriginalExtension();
            $rq->image->move(public_path("img\categorie"),$nomfile);
            $categ->image=$nomfile;
            $categ->save();
            return response()->json(['flag'=>true,'message'=>"L'ajout a ete effectué avec success"]);
        }catch(Throwable $e) {
            ErreurController::add('Categorie','ajouter',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function supprimer(Request $rq){
        try{
            //authentifiction
            $categ=Categorie::where('id',$rq->categ_id);
            if(!$categ->exists()){
                return response()->json(['flag'=>false,'message'=>"Impossible!! Cette catégorie n'existe pas."]);
            }
            $categ=$categ->first();
            //supprimer l'image de catégorie
            if(File::exists(public_path()."\img\categorie\\".$categ->image))
                File::delete( public_path()."\img\categorie\\".$categ->image);
            $categ->first()->delete();//supprimer la categorie
            //remplacer cette categorie par categorie 'autre' (cate_id=0)
            $produits=Produit::where('categ_id',$rq->categ_id)->get();
            foreach($produits as $prod){
                $prod->categ_id=0;
                $prod->save();
            }
            return response()->json(['flag'=>true,'message'=>"La suppression a ete effectué avec success"]);
        }catch(Throwable $e) {
            ErreurController::add('Categorie','supprimer',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function modifier(Request $rq){
        try{
            //authentifiction
            $validation = Validator::make($rq->all(), [
                'nom'=> 'required','description'=>'required','categ_id'=>'required',
            ]);
            if(!$validation->passes()){
                return response()->json(['flag'=>false,'message'=>"Veuillez entrer toutes les informations."]);
            }
            $categ=Categorie::where('id',$rq->categ_id);
            if(!$categ->exists()){
                return response()->json(['flag'=>false,'message'=>"Impossible!! Cette catégorie n'existe pas."]);
            }
            $categ=$categ->first();
            if($rq->nom!=$categ->nom){//tester est ce que le client chnage le nom de categorie
                if(Categorie::where('nom',$rq->nom)->exists()){//verifier s'il existe déja dans le table
                    return response()->json(['flag'=>false,'message'=>"Impossible!! Veuillez entrer un autre nom de catégorie."]); 
                }
            }
            $categ->nom=$rq->nom;
            $categ->description = $rq->description;
            if($rq->has('image')){//si l'utilisateur change l'image de catégorie
                File::delete( public_path()."\img\categorie\\".$categ->image);
                $rq->image->move(public_path("\img\categorie"),$categ->image); 
            }            
            $categ->save();
            return response()->json(['flag'=>true,'message'=>"La modification a été effectué avec success",'imageLink'=>asset("img/categorie")."/".$categ->image]);
        }catch(Throwable $e) {
            ErreurController::add('Categorie','modifier',$e);//enregistrer l'erreur dans la base de données
            return response()->json(['flag'=>false,'message'=>$e->getMessage()]);
        }
    }
}
