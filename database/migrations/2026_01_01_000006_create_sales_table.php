<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::create('sales',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->restrictOnDelete();$t->string('sale_number')->unique();$t->decimal('total',14,2)->default(0);$t->enum('status',['completed','cancelled'])->default('completed');$t->dateTime('sold_at');$t->timestamps();$t->index(['sold_at','status']);});}public function down():void{Schema::dropIfExists('sales');}};
