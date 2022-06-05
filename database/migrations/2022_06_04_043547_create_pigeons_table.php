<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pigeons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->integer('speed');
            $table->integer('range');
            $table->integer('cost');
            $table->integer('downtime');
            $table->dateTime('latest_delivery_at', 0);
        });

        DB::table('pigeons')->insert(
            array(
                [
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                    'name'      => 'Antonio',
                    'speed'     => '70',
                    'range'     => '600',
                    'cost'      => '2',
                    'downtime'  => '2',
                    'latest_delivery_at'  => Carbon::now()
                ],
                [
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                    'name'      => 'Bonito',
                    'speed'     => '80',
                    'range'     => '500',
                    'cost'      => '2',
                    'downtime'  => '3',
                    'latest_delivery_at'  => Carbon::now()
                ],
                [
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                    'name'      => 'Carillo',
                    'speed'     => '65',
                    'range'     => '1000',
                    'cost'      => '2',
                    'downtime'  => '3',
                    'latest_delivery_at'  => Carbon::now()
                ],
                [
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                    'name'      => 'Alejandro',
                    'speed'     => '70',
                    'range'     => '800',
                    'cost'      => '2',
                    'downtime'  => '2',
                    'latest_delivery_at'  => Carbon::now()
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pigeons');
    }
};
