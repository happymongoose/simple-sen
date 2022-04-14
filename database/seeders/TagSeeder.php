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

        $tags = [

          //Pupil status
          [ "ehcp", "Pupil has EHCP" ],
          [ "ehcp_rejected", "Pupil has had an EHCP application rejected" ],
          [ "lac", "Pupil is looked after" ],
          [ "fsm", "Pupil is entitled to free school meals" ],
          [ "send", "Pupil is formally supported for SEND" ],
          [ "attendance", "Pupil has attendance issues" ],
          [ "summer", "Pupil was born in the summer" ],
          [ "tutor", "Pupil receives additional tutoring" ],

          //Diagnoses / needs
          [ "adhd", "Pupil has a diagnosis of ADHD" ],
          [ "autism", "Pupil has a diagnosis of autism" ],
          [ "dyslexia", "Pupil has a diagnosis of dyslexia" ],
          [ "dyspraxia", "Pupil has a diagnosis of dyspraxia" ],
          [ "dyscalculia", "Pupil has a diagnosis of dyscalculia"],
          [ "apd", "Pupil has diagnosis of auditory processing disorder" ],
          [ "medical", "Pupil has medical needs" ],
          [ "semh", "Pupil has social, emotional or mental health needs" ],
          [ "learning", "Pupil has learning needs" ],
          [ "mld", "Pupil has moderate learning difficulties" ],
          [ "sld", "Pupil has severe learning difficulties" ],
          [ "pmld", "Pupil has profound and multiple learning difficulties" ],
          [ "sensory", "Pupil has multi-sensory impairments" ],
          [ "physical", "Pupil has physical needs" ],
          [ "hearing", "Pupil has hearing impairment" ],
          [ "visual", "Pupil has visual impairment" ],
          [ "speech_and_language", "Pupil has speech and language needs" ],
          [ "cp", "Pupil has diagnosis of Cerebral Palsy" ],
          [ "cf", "Pupil has diagnosis of Cystic Fibrosis"],
          [ "global", "Pupil has diagnosis of global developmental delay" ],
          [ "odd", "Pupil has diagnosis of Oppositional Defiant Disorder"],
          [ "ocd", "Pupil has diagnosis of Obbsesive Compulsive Disorder" ],
          [ "fasd", "Pupil has diagnosis of Fetal Alcohol Spectrum Disorder"],
          [ "pdd", "Pupil has diagnosis of Pervasive Developmental Disorder" ],
          [ "pda", "Pupil has diagnosis of Pathological Demand Avoidance" ],

          //Support agencies
          [ "ep", "Pupil is referred to the EP" ],
          [ "salt", "Pupil is referred to Speech and Language therapist" ],
          [ "ot", "Pupil is referred to Occupational Therapy" ],
          [ "pediatrics", "Pupil is referred to Pediatrics" ],
          [ "physio", "Pupil is referred to physiotherapist" ],

        ];

        //Iterate over tags array and insert into database one by one
        foreach($tags as $tag) {
          DB::table("tags")->insert([
              'tag' => $tag[0],
              'description' => $tag[1],
              'colour' => $tag_controller->getNewColour(),
          ]);
        }

    }
}
