<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sucursales', 'telefono')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->string('telefono', 30)->nullable();
            });
        }

        if (! Schema::hasColumn('trabajadores', 'documento')) {
            Schema::table('trabajadores', function (Blueprint $table) {
                $table->string('documento', 30)->nullable()->unique();
            });
        }

        if (! Schema::hasColumn('trabajadores', 'cargo')) {
            Schema::table('trabajadores', function (Blueprint $table) {
                $table->string('cargo', 100)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('trabajadores', 'cargo')) {
            Schema::table('trabajadores', function (Blueprint $table) {
                $table->dropColumn('cargo');
            });
        }

        if (Schema::hasColumn('trabajadores', 'documento')) {
            Schema::table('trabajadores', function (Blueprint $table) {
                $table->dropUnique(['documento']);
                $table->dropColumn('documento');
            });
        }

        if (Schema::hasColumn('sucursales', 'telefono')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->dropColumn('telefono');
            });
        }
    }
};
