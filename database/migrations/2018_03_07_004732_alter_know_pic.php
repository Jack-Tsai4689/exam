<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKnowPic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knows', function (Blueprint $table) {
            $table->string('k_picpath', 100)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knows', function (Blueprint $table) {
            $table->dropColumn('k_picpath');
        });
    }
}
