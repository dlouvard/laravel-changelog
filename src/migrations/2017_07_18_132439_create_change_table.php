<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('context_id')->nullable();
            $table->string('context_model')->nullable();
            $table->string('interface', 127)->nullable();
            $table->longText('colsaved')->nullable();
            $table->string('notes', 255)->nullable();
            $table->enum('status', ['pending', 'complete', 'failed'])->default('pending');
            $table->index('status');
        });

        foreach (config('changelog.table') as $t):
            Schema::table($t, function (Blueprint $table) {
                $table->unsignedInteger('change_id')->nullable();
            });
        endforeach;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('changes');
        foreach (config('changelog.table') as $t):
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('change_id');
            });
        endforeach;
    }
}
