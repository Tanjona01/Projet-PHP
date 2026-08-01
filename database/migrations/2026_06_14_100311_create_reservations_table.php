<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->string('idreserv')->primary();
            $table->string('idvoit');
            $table->unsignedBigInteger('idcli');
            $table->integer('place');
            $table->dateTime('date_reserv');
            $table->date('date_voyage');
            $table->enum('payement', ['sans avance', 'avec avance', 'tout payé']);
            $table->integer('montant_avance')->default(0);
            $table->foreign('idvoit')->references('idvoit')->on('voitures')->onDelete('cascade');
            $table->foreign('idcli')->references('idcli')->on('client')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
