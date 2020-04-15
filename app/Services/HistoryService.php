<?php
namespace App\Services;

class HistoryService {
    private static $deterministicCommands = [
        'add' => [
            'person' => "Добавление человека.\n",
            'task' => "Добавление задачи.\n",
            'car' => " -Добавлена информация о машине\n (",
            'region' => " -Добавлена информация о регионе\n (",
            'initials' => " -Добавлены инициалы\n (",
            'birth_date' => " -Добавлена дата рождения\n (",
            'pasport_data' => " -Добавлены паспортные данные\n (",
            'receipt_info' => " -Добавлена информация о выдачи паспорта\n (",
            'phone' => " -Добавлен номер телефона\n (",
            'email' => " -Добавлена электронная почта\n (",
            'card' => " -Добавлена информация о банковской карте\n (",
            'notation' => " -Добавлено примечание о человеке\n (",
            'status' => " -Добавлен новый статус\n (",
            'record_type' => " -Добавлен новый тип записи\n (",
            'NDS' => " - Добавлена информация об НДС\n (",
            'call' => "Добавлена запись: звонок с клиентом",
            'verification' => "Добавлена запись: проверка клиента",
            'title' => "Добавлена запись: заголовок\n (",
            'description' => "Добавлена запись: описание\n (",
            'author' => "Добавлена запись: автор\n (",
            'responsible' => "Добавлена запись: отвечающий\n (",
            'started_at' => "Добавлена запись: дата и время начала задачи\n (",
            'finished_at' => "Добавлена запись: дата и время окончания задачи\n (",
            'people' => "Добавлена запись: список клиентов\n (",
            'type' => "Добавлена запись: тип\n ("
        ],
        'update' => [
            'person' => "Обновление информации о человеке.\n",
            'task' => "Обновление задачи.\n",
            'car' => " -Обновлена информация о машине\n (",
            'region' => " -Обновлена информация о регионе\n (",
            'initials' => " -Обновлены инициалы\n (",
            'birth_date' => " -Обновлена дата рождения\n (",
            'pasport_data' => " -Обновлены паспортные данные\n (",
            'receipt_info' => " -Обновлена информация о выдачи паспорта\n (",
            'phone' => " -Обновлён номер телефона\n (",
            'email' => " -Обновлена электронная почта\n (",
            'card' => " -Обновлена информация о банковской карте\n (",
            'notation' => " -Обновлено примечание о человеке\n (",
            'status' => " -Обновлён статус\n (",
            'record_type' => " -Обновлён тип записи\n (",
            'NDS' => " - Обновлена информация об НДС\n (",
            'title' => "Обновлена запись: заголовок\n (",
            'description' => "Обновлена запись: описание\n (",
            'author' => "Обновлена запись: автор\n (",
            'responsible' => "Обновлена запись: отвечающий\n (",
            'started_at' => "Обновлена запись: дата и время начала задачи\n (",
            'finished_at' => "Обновлена запись: дата и время окончания задачи\n (",
            'people' => "Обновлена запись: список клиентов\n (",
            'type' => "Обновлена запись: тип\n ("
        ]
    ];

    public static function getHistoryDescription($model, $commandString) {
        $commandArray = explode(",", $commandString);
        $historyDescription = "";
        if ($commandArray[0] === "add person" || $commandArray[0] === "update person" || $commandArray[0] === "add task" || $commandArray[0] === "update task") {
            $command = explode(" ", $commandArray[0]);
            $historyDescription .= HistoryService::$deterministicCommands[$command[0]][$command[1]]."\nИзменения:\n";

            unset($commandArray[0]);
            foreach($commandArray as $value) {
                $command = explode(" ", $value);
                $keys = explode("_", $command[1]);
                if($command[1] != 'car') {
                    if(in_array("id", $keys)) {
                        $historyDescription .= HistoryService::$deterministicCommands[$command[0]][$command[1]].$model[$keys[0]]['name'].")\n";
                    } elseif($command[1] != 'author' && $command[1] != 'responsible' && is_array(json_decode($model[$command[1]]))) {
                        $peopleNames = "";
                        foreach($model[$command[1]] as $item) {
                            $peopleNames .= "$item->initials ";
                        }
                        $historyDescription .= HistoryService::$deterministicCommands[$command[0]][$command[1]].$peopleNames.")\n";
                    } else {
                        $historyDescription .= HistoryService::$deterministicCommands[$command[0]][$command[1]].$model[$command[1]].")\n";
                    }
                }
            }
        } else {
            foreach($commandArray as $value) {
                $command = explode(" ", $value);
                $historyDescription .= HistoryService::$deterministicCommands[$command[0]][$command[1]];

            }
        }
        return $historyDescription;
    }
}
?>
