<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Throwable;
use App\Models\Erreur;
class ErreurController extends Controller
{
    public static function add($controller,$function,$exception){
        try{
            $er= new Erreur();
            $er->controller=$controller;
            $er->function=$function;
            $er->exception=$exception;
            $er->save();
        }catch(Throwable $e){
            throw new \Exception("Désolé, erreur originale");
        }
    }
}
