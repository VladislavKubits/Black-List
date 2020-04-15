<?php

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class PeopleCompareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$people = DB::table('people')->get();
        $drivers = DB::table('drivers')->get();
        $count = 0;
        $array = [];

        foreach($drivers as $driver) {
            $driver = (array) $driver;
            $person = DB::table('people')->where([['phone', '=', $driver['Рабочий телефон']]])->first();
            if($person != null) {
                $person->NDS = $driver['НДС'] == 'да';
                //++$count;
                Person::where('id', $person->id)->update((array) $person);
                //array_push($array, ["phone" => $person->phone, "initials" => $person->initials]);
            }
        }
    }
}
