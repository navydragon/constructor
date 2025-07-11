<?php

namespace App\Console\Commands;

use PHPHtmlParser\Dom;
use App\Fgos;
use Illuminate\Console\Command;

class ParseFgos
{
    public $url;
    public $level_id;
}

class ParseFgoses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:fgoses';

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
        $bak = new ParseFgos();
        $bak->url = 'https://obrnadzor.gov.ru/gosudarstvennye-uslugi-i-funkczii/7701537808-gosfunction/acts_list2021/mandatory_requirements_2021/fgos_bakalavriat/';
        $bak->level_id = 2;
        $spec = new ParseFgos();
        $spec->url = 'https://obrnadzor.gov.ru/gosudarstvennye-uslugi-i-funkczii/7701537808-gosfunction/acts_list/mandatory_requirements_2021/fgos_specialitet/';
        $spec->level_id = 3;
        $mag = new ParseFgos();
        $mag->url = 'https://obrnadzor.gov.ru/gosudarstvennye-uslugi-i-funkczii/7701537808-gosfunction/acts_list/mandatory_requirements_2021/fgos_magistratura/';
        $mag->level_id = 4;
        $sp = new ParseFgos();
        $sp->url = 'https://obrnadzor.gov.ru/gosudarstvennye-uslugi-i-funkczii/7701537808-gosfunction/acts_list2021/mandatory_requirements_2021/fgos_spo/';
        $sp->level_id = 5;

        $pages = array($bak, $spec,$mag,$sp);
       //$pages = array($sp);
        foreach ($pages as $page)
        {
            $data = file_get_contents($page->url);
            $level_id = $page->level_id;
            $dom = new Dom();
            $dom->loadStr($data);
            $rows = $dom->find('tr');
            
            $arr = [];
            foreach($rows as $row)
            {
                $cols = $row->find('td');


                if (count($cols) < 3 || $cols[2]->innerHtml() == "")
                {
                  //  continue;
                }else{
                    
                    $code = $cols[0]->text;
                    $name = $cols[1]->text;
                    $hrefs = $cols[2]->find('a');
                    
                    if ($level_id == 5)
                    {
                        $last_href = $hrefs[0];
                    }else{
                        $last_href = $hrefs[count($hrefs)-1];
                    }
                    $requsites = str_replace('N','№',$last_href->text);
                    $requsites = str_replace('Приказ',', утв. приказом',$requsites);
                    $link = $last_href->getAttribute('href');

                    $fgos = Fgos::firstOrNew([
                        'code' => $code
                    ]);
                    $fgos->name = $name;
                    $fgos->link = $link;
                    $fgos->requsites = $requsites;
                    $fgos->fgos_level_id = $level_id;
                    if ($code == '01.03.04')
                    {
                        $fgos->requsites = ', утв. приказом Минобрнауки '.$fgos->requsites;
                    }
                    $fgos->save();
                }
                
            }
        }
        print ("GOT IT ". date("F j, Y, g:i a").PHP_EOL );
    }
}
