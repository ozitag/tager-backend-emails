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
        Schema::create('tager_email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_id');
            $table->string('name');
            $table->string('value');
            $table->string('subject');
            $table->string('recipients');
        });

        Schema::create('tager_email_logs', function (Blueprint $table) {
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
        Schema::dropIfExists('tager_email_templates');
        Schema::dropIfExists('tager_email_logs');
    }
}
