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
            $table->string('pq_cgroup', 100)->default('');
            $table->string('pq_cans', 255)->default('');
            $table->integer('pq_cmatch')->default(0);
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
            $table->dropColumn('pq_cgroup');
            $table->dropColumn('pq_cans');
            $table->dropColumn('pq_cmatch');
        });
    }
}
