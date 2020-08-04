<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerEmailsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->string('template')->nullable();
            $table->text('body')->change();
            $table->dropColumn('debug');
            $table->text('service_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->dropColumn('template');
            $table->boolean('debug')->default(false);
            $table->dropColumn('service_response');
        });
    }
}
