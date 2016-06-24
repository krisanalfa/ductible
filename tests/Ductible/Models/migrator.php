<?php

use Illuminate\Database\Schema\Blueprint;

Schema::dropIfExists('husbands');
Schema::dropIfExists('wives');
Schema::dropIfExists('children');
Schema::dropIfExists('toys');
Schema::dropIfExists('child_toy');

Schema::create('husbands', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});

Schema::create('wives', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->integer('husband_id')->unsigned();
    $table->timestamps();
});

Schema::create('children', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->integer('father_id')->unsigned();
    $table->integer('mother_id')->unsigned();
    $table->timestamps();
});

Schema::create('toys', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});

Schema::create('child_toy', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('child_id')->unsigned();
    $table->integer('toy_id')->unsigned();
});
