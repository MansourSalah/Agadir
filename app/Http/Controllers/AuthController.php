<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Throwable;
use Validator;

class AuthController extends Controller
{
    private function redirect(){//return lien de redirection selon le role
        if (parent::isAdmin()){
            return response()->json(['flag'=>true,'message'=>"/admin/commandes"]);
        }else{
            return parent::response(false,"Problème de privilège","مشكلة في الإمتيازات"); 
        }
        
        /*
        else if(parent::isProf()){
            return response()->json(['flag'=>true,'message'=>"/prof"]);
        }*/
    }
    public function view_connexion(Request $rq){
        try {
            if(parent::has_s('userC'))//cette condition s'il est deja connecté
                return redirect("/admin/commandes");    
           
            return view("auth.connexion.".parent::get_s('lang').".app",['userC' => parent::userC()]);
        }catch(Throwable $e) {
            ErreurController::add('Auth','view_connexion',$e);
            return view("404",["titre"=>"Erreur","message"=>$e->getMessage()]);
        }
    }
    public function view_passeOublie(Request $rq){
        try {
            if(parent::has_s('userC'))//cette condition s'il est deja connecté
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
        
            return view("auth.pass_oublie.".parent::get_s('lang').".app",['userC' => parent::userC()]);
        }catch(Throwable $e) {
            ErreurController::add('Auth','view_passeOublie',$e);
            return view("404",["titre"=>"Erreur","message"=>$e->getMessage()]);
        }
    }
    public function view_inscription(Request $rq){
        try {
            if(parent::has_s('userC'))//cette condition s'il est deja connecté
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
        
            return view("auth.inscription.".parent::get_s('lang').".app",['userC' => parent::userC()]);
        }catch(Throwable $e) {
            ErreurController::add('Auth','view_inscription',$e);
            return view("404",["titre"=>"Erreur","message"=>$e->getMessage()]);
        }
    }
    public function view_newPass(Request $rq,$token){
        try {
            if(parent::isMax("nbr_newPass",5))
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
             
            if(parent::has_s('userC'))//cette condition s'il est deja connecté
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
            
            //ajouter condition si le token est validé
            if($token!= parent::get_s("auth_token_password2"))
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
            

            return view("auth.new_pass.".parent::get_s('lang').".app",['userC' => parent::userC()]);
        }catch(Throwable $e) {
            ErreurController::add('Auth','view_newPass',$e);
            return view("404",["titre"=>"Erreur","message"=>$e->getMessage()]);
        }
    }
    public function view_verifier(Request $rq){
        try{
            if(parent::has_s('userC'))//cette condition s'il est deja connecté
                return view('404',['titre'=>'Problème de privilège','message'=>"Désolé, vous n'avez pas l'autorisation d'accéder à cette page."]);
        
            if($rq->path()=="verifier/app"){$comp="app";}
            else if($rq->path()=="verifier/body"){$comp="body";}
        
            return view("auth.verifier.".parent::get_s('lang').".app",
            ['userC'=>parent::userC(),'typeToken'=>parent::get_s("typeToken")]);
        }catch(Throwable $e) {
            ErreurController::add('Auth','view_verifier',$e);
            return view("404",["titre"=>"Erreur","message"=>$e->getMessage()]);
        } 
    }
    public function add(Request $rq){
        try{
            //ajouter le nombre des fois d'ajoute = 2
            if(parent::isMax("nbr_inscription",5)){//2 fois
                return parent::response(false,"Problème de prévilège, Veuillez réessayer plus tard","ليس لك الحق في هذه العملية، الرجاء إعادة المحاولة في وقت لاحق");
            }
            /*
            if($rq->role==1 || $rq->role==2){//l'admin qui a le droit d'ajouter un admin ou prof
                if(!parent::isAdmin()){
                    return parent::response(false,"Problème de prévilège","ليس لك الحق في هذه العملية");
                }
            }
            */
            $validation = Validator::make($rq->all(), [
                'nom'=>'required|string|max:100',
                'prenom'=>'required|string|max:100',
                'email'=>'required|email|max:100',
                'phone'=>'string|max:15',
                'pass1'=>'required|string',
                'pass2'=>'required|string',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Veuillez saisir toutes les informations","الرجاء إدخال كافة المعلومات");
            } 
            $rq->email=strtolower($rq->email);
            if(User::where('email',$rq->email)->exists()){//tester si le nouveau email deja existe
                return parent::response(false,"Votre adresse email existe déjà","عنوان بريدك الإلكتروني تم استعماله من قبل");
            }
            //test password
            if($rq->pass1!=$rq->pass2)
                return parent::response(false,"Veuillez confirmer votre mot de passe","يرجى التأكد من صحة كلمة المرور");
            
            //stoquer le user dans les session
            parent::put_s("newUser",['nom'=>$rq->nom,'prenom'=>$rq->prenom,'phone'=>$rq->phone,
            'role'=>1,'password'=>password_hash($rq->pass1, PASSWORD_DEFAULT),
            'email'=>$rq->email]); 
            //generer le token
            if(!parent::has_s('auth_token_inscription'))
                parent::put_s("auth_token_inscription",rand(10000,99999));
            parent::put_s("typeToken",1);//1 pour inscription
            //envoyer un email pour completer l'inscription
            if(parent::get_s("lang")=="fr"){
                EmailController::send("email.inscription-fr",$rq->email,
                'Veuillez vérifier votre adresse e-mail',['token'=>parent::get_s("auth_token_inscription")]);
            }else{
                EmailController::send("email.inscription-ar",$rq->email,
                'يرجى التحقق من عنوان البريد الإلكتروني الخاص بك',['token'=>parent::get_s("auth_token_inscription")]);    
            }
            return parent::response(true,"Nous avons envoyé un code à l'adresse e-mail, si vous n'avez pas reçu d'email veuillez vérifier dans vos spams ou essayez de nouveau.","لقد أرسلنا رمزًا إلى عنوان البريد الإلكتروني ، إذا لم تستلمه ، فيرجى التحقق من البريد العشوائي أو المحاولة مرة أخرى");
        }catch(Throwable $e){
            ErreurController::add("Auth","add",$e);
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
    private function add_complete(){
        try{
            $newUser= parent::get_s("newUser");
            $user= new User();
            $user->nom=$newUser['nom'];
            $user->prenom=$newUser['prenom'];
            $user->email=$newUser['email'];
            $user->phone=$newUser["phone"];
            $user->password=$newUser['password'];
            $user->role=$newUser['role'];
            $user->save();//enregistrer le user dans la base de données
            /* Ajouter un objet *
            $etud = new Etudiant();
            $etud->id_user=$user->id;
            $etud->save();
            */
            parent::forget_s("newUser");
        }catch(Throwable $e){
            ErreurController::add("Auth","add_complete",$e);
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
    public function verifier(Request $rq){//verifier le token recus par email
        try{
            if(parent::isMax("nbr_verifier",5)){//2 fois
                return parent::response(false,"Problème de prévilège, Veuillez réessayer plus tard","ليس لك الحق في هذه العملية، الرجاء إعادة المحاولة في وقت لاحق");
            }
            $validation = Validator::make($rq->all(), [
                'typeToken'=>'required|string',
                'token'=>'required|string',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Veuillez saisir le code","يرجى إدخال الرمز");
            } 
            if(parent::get_s('typeToken')==1){//creer un compte
                if(parent::get_s("auth_token_inscription")==$rq->token && $rq->typeToken==1){
                    //fonction qui ajoute- le Compte
                    $this->add_complete();
                    parent::forget_s("auth_token_inscription");
                    parent::forget_s("typeToken");
                    parent::forget_s("nbr_verifier");
                    return response()->json(['flag'=>true,'url'=>'/moncompte']); 
                }else{
                    return parent::response(false,"Désolé, vous n'avez pas le droit de completer l'opération.","آسف ، ليس لديك الحق في إتمام العملية");
                }
            }else if(parent::get_s('typeToken')==2){//change le mot de passe (pass oublié)
                if(parent::get_s('auth_token_password')==$rq->token && $rq->typeToken==2){
                    parent::forget_s("typeToken");
                    parent::forget_s("nbr_verifier");
                    parent::forget_s("auth_token_password");
                    parent::put_s('auth_token_password2',rand(1111,9999));
                    return response()->json(['flag'=>true,'url'=>"/nouveau-mot-passe"."/".parent::get_s('auth_token_password2')]);
                }else{
                    return parent::response(false,"Désolé, vous n'avez pas le droit de completer l'opération.","آسف ، ليس لديك الحق في إتمام العملية");
                }
            }else{
                return parent::response(false,"Désolé, vous n'avez pas le droit de completer l'opération.","آسف ، ليس لديك الحق في إتمام العملية");
            }
        }catch(Throwable $e){
            ErreurController::add("Auth","verifier",$e);
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
    public function login(Request $rq){
        try{
            $validation = Validator::make($rq->all(), [
                'password'=>'required|string|max:255',
                'email'=>'required|email|max:100',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Veuillez saisir toutes les informations","الرجاء إدخال كافة المعلومات");
            }
            $user=User::where('email','=', $rq->email)->first();
            if($user===null)
                return parent::response(false,"Le nom d'utilisateur incorrect","اسم المستخدم غير صحيح");
            
            if(!$user->active)
                return parent::response(false,"Désolé!! Votre compte est désactivé","آسف!! تم إقاف حسابك");
                        
            if(!password_verify($rq->password,$user->password))
                return parent::response(false,"Le mot de passe incorrect","كلمة سر خاطئة");
            if(parent::isMax("nbr_login",5)){//5 fois
                return parent::response(false,"Problème de prévilège, Veuillez réessayer plus tard","ليس لك الحق في هذه العملية، الرجاء معاودة المحاولة في وقت لاحق");
            }
            parent::put_s('userC',['id'=>$user->id,'role'=>$user->role]);//save in session
            parent::forget_s('nbr_login');
            return parent::response(true,"","");//return le lien de redirection de back office
        }catch(Throwable $e){
            ErreurController::ajouter('Auth','login',$e->getMessage());
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
    public function logout(){
        if(parent::has_s('userC')){
            parent::forget_s('userC');
            return true;
        }else
            return false;
    }
    public function sendMail_changePassword(Request $rq){
        try{
            if(parent::isMax("nbr_newPass_send",5))
                return parent::response(false,"Problème de prévilège, Veuillez réessayer plus tard","ليس لك الحق في هذه العملية، الرجاء معاودة المحاولة في وقت لاحق");
            
            $validation = Validator::make($rq->all(), [
                'email'=>'required|email|max:100',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Format de l'email incorrect","البريد الإلكتروني غير صحيح");
            }
            if(!parent::has_s("auth_token_password")){
                parent::put_s("auth_token_password",rand(10000,99999));
            }
            parent::put_s("typeToken",2);//
            parent::put_s("email_change",$rq->email);
            //envyer message arabic
            if(parent::get_s("lang")=="fr"){
                EmailController::send("email.passe-oublie-fr",$rq->email,
                'Votre demande de réinitialisation de mot de passe Rancho-Academy',['token'=>parent::get_s("auth_token_password")]);
            }else{
                EmailController::send("email.passe-oublie-ar",$rq->email,
                'طلب إعادة تغيير كلمة المرور الخاصة بك في أكاديمية رانشو',['token'=>parent::get_s("auth_token_password")]);    
            }
            return parent::response(true,"Nous avons envoyé un code à l'adresse e-mail, si vous n'avez pas reçu d'email veuillez vérifier dans vos spams ou essayez de nouveau.","لقد أرسلنا رمزًا إلى عنوان البريد الإلكتروني ، إذا لم تستلمه ، فيرجى التحقق من البريد العشوائي أو المحاولة مرة أخرى");
        }catch(Throwable $e){
            ErreurController::ajouter('Auth','sendMail_changePassword',$e->getMessage());
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
    public function changePassword(Request $rq){
        try{
            if(!parent::has_s("email_change") && !parent::has_s("auth_token_password"))
                return parent::response(false,"Vous ne disposez pas de privilèges suffisants pour effectuer cette opération","ليس لديك امتيازات كافية لإجراء هذه العملية");
            $validation = Validator::make($rq->all(), [
                'pass1'=>'required|string',
                'pass2'=>'required|string',
            ]);
            if(!$validation->passes()){
                return parent::response(false,"Veuillez saisir toutes les informations","الرجاء إدخال كافة المعلومات");
            }
            if($rq->pass1!=$rq->pass2)
                return parent::response(false,"Veuillez confirmer votre mot de passe","يرجى التأكد من صحة كلمة المرور");
            
            $user=User::where('email','=', parent::get_s("email_change"))->first();
            $user->password=password_hash($rq->pass1, PASSWORD_DEFAULT);
            $user->save();
            parent::forget_s('email_change');
            parent::forget_s("auth_token_password2");
            parent::forget_s("nbr_newPass");
            return parent::response(true,"La modification a été effectué avec succès","تم تغيير كلمة المرور بنجاح");
        }catch(Throwable $e){
            ErreurController::ajouter('Auth','changePassword',$e->getMessage());
            return parent::response(false,"Une erreur est survenue. Veuillez réessayer plus tard","حدث خطأ. الرجاء معاودة المحاولة في وقت لاحق");
        }
    }
}
