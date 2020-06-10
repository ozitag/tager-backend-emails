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
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('recipient');
            $table->string('subject');
            $table->string('body');
            $table->string('status');
            $table->boolean('debug')->default(false);
            $table->text('error')->nullable();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('tager_mail_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tager_mail_logs');
        Schema::dropIfExists('tager_mail_templates');
    }
}
