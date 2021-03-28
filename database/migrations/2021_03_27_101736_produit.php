<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Produit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom',50)->unique();
            $table->string('description');
            $table->float('prix');
            $table->string('image')->nullable();
            $table->bigInteger('categ_id')->unsigned();//c'est un clé étrangère de table Categorie
            $table->timestamps();
            
            $table->foreign('categ_id')
                    ->references('id')
                    ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
