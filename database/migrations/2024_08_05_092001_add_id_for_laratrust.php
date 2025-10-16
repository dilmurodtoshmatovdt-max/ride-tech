<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('role_user', function (Blueprint $table) {
            $table->id();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->id();
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->bigInteger('id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropTimestamps();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropTimestamps();
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropTimestamps();
        });
    }
};
