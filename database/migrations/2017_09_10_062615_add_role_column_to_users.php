<?php

use App\DataAccess\Eloquent\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('password')->nullable();
            $table->string('api_token')->after('role')->nullable();
        });

        User::whereNull('role')->update([
            'role' => 'user',
            'api_token' => str_random(60),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable(false)->change();
            $table->string('api_token')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'api_token']);
        });
    }
}
