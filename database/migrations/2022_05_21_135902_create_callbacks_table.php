<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained();
            $table->enum('type', ['callback', 'manual']);
            $table->longText('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('callbacks');
    }
};
