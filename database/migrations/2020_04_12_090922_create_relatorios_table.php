<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('cpf')->nullable();
            $table->boolean('teve_febre')->nullable(false);
            $table->boolean('teve_tosse')->nullable(false);
            $table->boolean('teve_dor_garganta')->nullable(false);
            $table->boolean('teve_dificuldade_respirar')->nullable(false);
            $table->boolean('teve_contato')->nullable(false);
            $table->string('anotacao')->nullable();
            $table->string('logradouro')->nullable();
            $table->double('latitude', 10, 8)->nullable();
            $table->double('longitude', 11, 8)->nullable();
            $table->integer('tipo_caso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
}
