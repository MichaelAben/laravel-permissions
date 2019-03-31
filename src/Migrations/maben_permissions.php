<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class MabenPermissions
 *
 * @author Michael Aben <m.aben@live.nl>
 */
class MabenPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('permissions.tables.roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create(config('permissions.tables.permissions'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('permission');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create(config('permissions.tables.model_roles'), function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index('model_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_type', 'model_id']);
        });

        Schema::create(config('permissions.tables.model_permissions'), function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index('model_id');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('model_roles');
        Schema::dropIfExists('model_permissions');
    }
}
