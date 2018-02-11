<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('e_id');
            $table->string('e_epno',50)->default('');
            $table->string('e_epname',50)->default('');
            $table->char('e_ident',1);
            $table->integer('e_groupid')->default(0);
            $table->string('e_pwd',32)->default('');
            $table->integer('e_sex')->default(1);
            $table->string('e_email', 200)->default('');
            $table->integer('e_webid')->default(0);
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
