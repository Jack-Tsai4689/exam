<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPubquesTypec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pubsque', function (Blueprint $table) {
            $table->string('q_cgroup', 100)->default('');
            $table->string('q_cans', 255)->default('');
            $table->integer('q_cmatch')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pubsque', function (Blueprint $table) {
            $table->dropColumn('q_cgroup');
            $table->dropColumn('q_cans');
            $table->dropColumn('q_cmatch');
        });
    }
}
