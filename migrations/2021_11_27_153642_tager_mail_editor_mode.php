<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagerMailEditorMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tager_mail_templates', function (Blueprint $table) {
            $table->string('editor_mode')->after('body')->default('VISUAL');
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
            $table->dropColumn('editor_mode');
        });
    }
}
