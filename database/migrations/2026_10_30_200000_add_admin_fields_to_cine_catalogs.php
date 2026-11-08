<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trabajadores')) {
            Schema::table('trabajadores', function (Blueprint $table) {
                if (! Schema::hasColumn('trabajadores', 'tipo_documento')) {
                    $table->string('tipo_documento', 30)->nullable();
                }

                if (! Schema::hasColumn('trabajadores', 'fecha_contratacion')) {
                    $table->date('fecha_contratacion')->nullable();
                }

                if (! Schema::hasColumn('trabajadores', 'estado')) {
                    $table->string('estado', 20)->default('activo');
                }
            });
        }

        if (Schema::hasTable('sucursales')) {
            Schema::table('sucursales', function (Blueprint $table) {
                if (! Schema::hasColumn('sucursales', 'email')) {
                    $table->string('email', 150)->nullable();
                }

                if (! Schema::hasColumn('sucursales', 'estado')) {
                    $table->string('estado', 20)->default('activo');
                }
            });
        }
    }

    public function down(): void
    {
        // Keep added catalog data when rolling back; dropping these columns would lose user data.
    }
};
