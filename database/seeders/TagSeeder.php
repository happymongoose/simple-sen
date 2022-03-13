<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\TagsController;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create a tag controller
        $tag_controller = new TagsController();

        //Add tags
        DB::table("tags")->insert([
            'tag' => "ehcp",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has EHCP",
        ]);

        DB::table("tags")->insert([
            'tag' => "lac",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil is looked-after",
        ]);

        DB::table("tags")->insert([
            'tag' => "adhd",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has diagnosis of ADHD",
        ]);

        DB::table("tags")->insert([
            'tag' => "autism",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has diagnosis of autism",
        ]);

        DB::table("tags")->insert([
            'tag' => "communication",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has communication needs",
        ]);

        DB::table("tags")->insert([
            'tag' => "learning",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has learning delays",
        ]);

        DB::table("tags")->insert([
            'tag' => "semh",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has additional SEMH needs",
        ]);

        DB::table("tags")->insert([
            'tag' => "fsm",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil entitled to free school meals",
        ]);

        DB::table("tags")->insert([
            'tag' => "physical",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has additional physical needs",
        ]);

        DB::table("tags")->insert([
            'tag' => "medical",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Pupil has additional medical needs",
        ]);

        DB::table("tags")->insert([
            'tag' => "something",
            'colour' => $tag_controller->getNewColour(),
            'description' => "Something",
        ]);

    }
}
