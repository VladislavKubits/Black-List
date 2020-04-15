<?php

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $carTypes = [
        //     'Тонар' => 47,
        //     'Бортовой' => 49,
        //     'Щука' => 51,
        //     'Полуприцеп бортовой' => 53,
        //     'Тралл' => 57,
        //     'Сельхозник' => 59
        // ];


        // $clients = DB::table('drivers')->get();
        // foreach($clients as $client) {
        //     $client = (array) $client;

        //     $status = '';
        //     if ($client['Проверенный водитель'] == 'нет') {
        //         $status = 'новый';
        //     } elseif ($client['Водитель в черном списке'] == 'нет') {
        //         $status = 'надёжный';
        //     } else {
        //         $status = 'ненадёжный';
        //     }

        //     $car = [];
        //     if($client['Тип ТС'] != null || $client['Тип ТС'] != "") {
        //         $typeNames = explode(", ", $client['Тип ТС']);
            
        //         foreach($typeNames as $name) {
        //             array_push($car, $carTypes[$name]);
        //         }
        //         $car = join(",", $car);
        //     } else {
        //         $car = "";
        //     }

        //     $person = [
        //         'car' => $car,
        //         'region' => $client['Регион водителя'],
        //         'initials' => $client['Имя'] . ' ' . $client['Фамилия'] . ' ' . $client['Отчество'],
        //         'birth_date' => $client['Дата рождения'],
        //         'record_type' => mb_strtolower($client['Тип контакта']),
        //         'phone' => $client['Рабочий телефон'],
        //         'card' => $client['Компания'] . ' ' . $client['Должность'],
        //         'status' => $status
        //     ];
            
        //     $dbPerson = new Person($person);
        //     $dbPerson->save();
        // }

        $clients = DB::table('clients')->get();

        foreach($clients as $client) {
            $client = (array) $client;

            $status = 'новый';

            $person = [
                'initials' => $client['Название компании'],
                'record_type' => 'клиент',
                'phone' => $client['Рабочий телефон'].', '.$client['Мобильный телефон'],
                'email' => $client['Корпоративный сайт'] . ', ' . $client['Рабочий e-mail'],
                'card' => $client['Банковские реквизиты'] . ' ' . $client['Город'] . ' ' . $client['Регион'] . ' ' . $client['Адрес'],
                'status' => $status
            ];
            
            $dbPerson = new Person($person);
            $dbPerson->save();
        }
    }
}
