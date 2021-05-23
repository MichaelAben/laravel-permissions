<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class MabenDevPermissionMigration
 *
 * @author Michael Aben
 */
class MabenDevPermissionMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('MabenDevPermissions.database.prefix') . 'permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('permission');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create(config('MabenDevPermissions.database.prefix') . 'permissionable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('permissionable', 'permissionable_index');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on(config('MabenDevPermissions.database.prefix') . 'permissions');
        });

        Schema::create(config('MabenDevPermissions.database.prefix') . 'roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create(config('MabenDevPermissions.database.prefix') . 'roleable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('roleable', 'roleable_index');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on(config('MabenDevPermissions.database.prefix') . 'roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('MabenDevPermissions.database.prefix') . 'roleable');
        Schema::dropIfExists(config('MabenDevPermissions.database.prefix') . 'roles');
        Schema::dropIfExists(config('MabenDevPermissions.database.prefix') . 'permissionable');
        Schema::dropIfExists(config('MabenDevPermissions.database.prefix') . 'permissions');
    }
}
