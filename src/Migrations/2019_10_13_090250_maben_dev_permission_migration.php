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
            $table->string('permissionable_type');
            $table->unsignedBigInteger('permissionable_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('permission_id')
                ->references('id')
                ->on(config('MabenDevPermissions.database.prefix') . 'permissions');

            $table->unique(['permission_id', 'permissionable_type', 'permissionable_id'],'permissionable_unique');
        });

        Schema::create(config('MabenDevPermissions.database.prefix') . 'roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create(config('MabenDevPermissions.database.prefix') . 'roleable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('roleable_type');
            $table->unsignedBigInteger('roleable_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on(config('MabenDevPermissions.database.prefix') . 'roles');

            $table->unique(['role_id', 'roleable_type', 'roleable_id'], 'rolable_unique');
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
