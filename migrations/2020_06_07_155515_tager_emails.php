<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tager_mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template');
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->string('recipients')->nullable();
            $table->boolean('changed_by_admin')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tager_mail_logs', function (Blueprint $table) {
            $table->id();
            $table->string('template')->nullable();
            $table->string('email');
            $table->string('subject');
            $table->string('body');
            $table->string('status');
            $table->boolean('debug')->default(false);
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
        Schema::dropIfExists('tager_mail_templates');
        Schema::dropIfExists('tager_mail_logs');
    }
}
