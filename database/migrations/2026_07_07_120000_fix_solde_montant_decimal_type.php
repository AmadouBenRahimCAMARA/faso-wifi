<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Changer solde de VARCHAR à DECIMAL dans la table soldes
        Schema::table('soldes', function (Blueprint $table) {
            $table->decimal('solde', 12, 2)->default(0)->change();
        });

        // Changer montant de VARCHAR à DECIMAL dans la table retraits
        Schema::table('retraits', function (Blueprint $table) {
            $table->decimal('montant', 12, 2)->default(0)->change();
        });

        // Changer montant de VARCHAR à DECIMAL dans la table tarifs
        Schema::table('tarifs', function (Blueprint $table) {
            $table->decimal('montant', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soldes', function (Blueprint $table) {
            $table->string('solde')->default('0')->change();
        });

        Schema::table('retraits', function (Blueprint $table) {
            $table->string('montant')->default('0')->change();
        });

        Schema::table('tarifs', function (Blueprint $table) {
            $table->string('montant')->change();
        });
    }
};