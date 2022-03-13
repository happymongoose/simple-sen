<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_student', function (Blueprint $table) {

            //columns
            $table->id();
            $table->integer('tag_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->timestamps();

            // foreign keys
            /*
            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
*/

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_student');
    }
}
