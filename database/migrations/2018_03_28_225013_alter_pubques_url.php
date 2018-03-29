<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPubquesUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pubsque', function (Blueprint $table) {
            $table->string('pq_qm_url', 300)->default('');
            $table->string('pq_qs_url', 300)->default('');
            $table->string('pq_am_url', 300)->default('');
            $table->string('pq_as_url', 300)->default('');
            $table->string('pq_av_url', 300)->default('');
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
            $table->dropColumn('pq_qm_url');
            $table->dropColumn('pq_qs_url');
            $table->dropColumn('pq_am_url');
            $table->dropColumn('pq_as_url');
            $table->dropColumn('pq_av_url');
        });
    }
}
