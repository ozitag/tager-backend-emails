<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EmailTemplatePrioritiesAndCcBcc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tager_mail_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('priority')->nullable()->after('id');
            $table->string('bcc')->nullable()->after('recipients');
            $table->string('cc')->nullable()->after('recipients');
        });

        $templates = DB::table('tager_mail_templates')->get();
        foreach ($templates as $ind => $template) {
            DB::table('tager_mail_templates')->where('id', $template->id)->update(['priority' => $ind + 1]);
        }

        Schema::table('tager_mail_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('priority')->change();
        });

        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->string('bcc')->nullable()->after('recipient');
            $table->string('cc')->nullable()->after('recipient');
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
            $table->dropColumn('priority');
            $table->dropColumn('cc');
            $table->dropColumn('bcc');
        });

        Schema::table('tager_mail_logs', function (Blueprint $table) {
            $table->dropColumn('cc');
            $table->dropColumn('bcc');
        });
    }
}
