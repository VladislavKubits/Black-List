<?php

use App\Models\Person;
use Illuminate\Database\Seeder;

class DeleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Person::where([['record_type', '=', 'водитель']])->get();
        foreach($drivers as $driver) {
            $phone = substr($driver->phone, 1);
            $people = Person::where([['phone', 'like', "%$phone"]])->get();
            if(count($people) > 1) {
                Person::remove($people[0]->id);
            }
        }
    }
}
