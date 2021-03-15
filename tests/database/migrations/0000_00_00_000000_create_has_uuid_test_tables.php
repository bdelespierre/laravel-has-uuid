<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasUuidTestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
            $table->primary('id');
        });

        DB::table('users')->insert([
            [
                'id'    => '1d4579a2-85b1-4872-931e-031eefab974b',
                'name'  => 'Nathalie PORTMAN',
                'email' => 'nathalie.portman@example.com',
            ],
            [
                'id'    => 'd449075e-1f6d-4464-8c82-9a403dfcc9fd',
                'name'  => 'Jack BLACK',
                'email' => 'jack.black@example.com',
            ],
            [
                'id'    => 'af7d82e7-1dc6-42c7-8e4f-57a508b3a402',
                'name'  => 'Leonardo DICAPRIO',
                'email' => 'leonardo.dicaprio@oexample.com',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterprise_phone_number');
    }
}
