<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerEmailsTemplateServiceTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tager_mail_templates', function (Blueprint $table) {
            $table->string('service_template')->nullable();
        });

        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->string('subject')->nullable()->change();
            $table->text('body')->nullable()->change();
            $table->string('service_template')->nullable();
            $table->text('service_template_params')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tager_mail_templates', function (Blueprint $table) {
            $table->dropColumn('service_template')->nullable();
        });

        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->dropColumn('service_template');
            $table->dropColumn('service_template_params');
        });
    }
}
