<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Profstandart;
use App\ProfstandartKind;
use DateTime;


class ParseProfstandarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:profstandarts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $url = "https://profstandart.rosmintrud.ru/obshchiy-informatsionnyy-blok/natsionalnyy-reestr-professionalnykh-standartov/reestr-professionalnykh-standartov/CreateCSV4ALL.php";
        //$csv = array_map('str_getcsv', file($url));
        $data = file_get_contents($url);

        // $data = str_replace (array('\r\n', '\n', '\r'), ' ', $data);

        $data = str_getcsv(file_get_contents($url), "\n");
        $index = 0;
        foreach ($data as $row)
        {
            if ($index == 0) {$index++; continue;}
            $row = str_getcsv($row, ";");
            if (count($row) == 7)
            {
                $prikaz_parts = explode(' ', $row[6]);
                $code_parts = explode('.', $row[2]);

                $ps = Profstandart::firstOrNew(['nameCode' =>  $row[2] ]);
                $ps->nameText = $row[5];
                $ps->nameCode = $row[2];
                if (count($prikaz_parts) == 3)
                {
                    $ps->orderDate = DateTime::createFromFormat('d.m.Y', $prikaz_parts[2]);
                }
                $ps->orderNumber = $prikaz_parts[0];

                $name = $row[2].' '.$row[5].', утв. приказом Минтруда России от '.$prikaz_parts[2].' № '.$prikaz_parts[0];
                $ps->fullName = str_replace("№  ", "№ ", $name);
                $kind = ProfstandartKind::where('code', $code_parts[0])->first();
                if ($kind)
                {
                    $ps->kind_id = $kind->id;
                }

                $ps->save();
            }
            $index++;
        }
        print ("GOT IT ". date("F j, Y, g:i a").PHP_EOL);
    }
}
