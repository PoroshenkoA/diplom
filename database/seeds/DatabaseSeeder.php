<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('universities')->insert([
            'name' => "Харківський національний університет радіоелектроніки",
        ]);
        DB::table('departments')->insert([
            'name' => "Комп'ютерної інженерії та управління",
            'universityID' => 1,
        ]);
        DB::table('groups')->insert([
            'name' => "Керівники",
            'status' => "",
            'departmentID' => 1,
        ]);
        DB::table('groups')->insert([
            'name' => "КІ-15-2",
            'status' => "Денне",
            'departmentID' => 1,
        ]);
        DB::table('userTypes')->insert([
            'type' => "студент",
        ]);
        DB::table('userTypes')->insert([
            'type' => "руководитель",
        ]);
        DB::table('userTypes')->insert([
            'type' => "член ЭК",
        ]);
        DB::table('userTypes')->insert([
            'type' => "админ",
        ]);
        DB::table('userTypes')->insert([
            'type' => "руководитель + член ЭК",
        ]);
        DB::table('users')->insert([
            'name' => "Адмін",
            'email' => "admin@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 4,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Іващенко Георгій Станіславович",
            'email' => "heorhii.ivashchenko@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 5,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Керівник 2",
            'email' => "leader2@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 2,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Керівник 3",
            'email' => "leader3@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 5,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Керівник 4",
            'email' => "leader4@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 2,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Член ЕК 1",
            'email' => "examiner1@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 3,
            'groupID' => 1,
        ]);
        DB::table('users')->insert([
            'name' => "Студент 1",
            'email' => "stud1@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 1,
            'groupID' => 2,
        ]);
        DB::table('users')->insert([
            'name' => "Студент 2",
            'email' => "stud2@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 1,
            'groupID' => 2,
        ]);
        DB::table('users')->insert([
            'name' => "Студент 3",
            'email' => "stud3@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 1,
            'groupID' => 2,
        ]);
        DB::table('users')->insert([
            'name' => "Студент 4",
            'email' => "stud4@nure.ua",
            'password' => "$2y$10\$wZmBWscM6/pLlFc/DAbZ8eY9EZRUmDe7YaHIYmTlS3cKmBbIm1HLm",
            'userTypeID' => 1,
            'groupID' => 2,
        ]);

    }
}
