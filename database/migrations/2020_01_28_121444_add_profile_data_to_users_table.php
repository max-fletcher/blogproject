<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileDataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->default('')->nullable();
            $table->string('about_me')->default('')->nullable();
            $table->string('interests')->default('')->nullable();
            $table->string('favourite_books')->default('')->nullable();
            $table->string('favourite_shows')->default('')->nullable();
            $table->string('favourite_movies')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('about_me');
            $table->dropColumn('interests');
            $table->dropColumn('favourite_books');
            $table->dropColumn('favourite_shows');
            $table->dropColumn('favourite_movies');
        });
    }
}
