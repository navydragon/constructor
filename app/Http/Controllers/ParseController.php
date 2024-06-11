<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPHtmlParser\Dom;
use App\Fgos;
use App\Profstandart;
use App\ProfstandartKind;
use DateTime;

class ParseFgos
{
    public $url;
    public $level_id;
}

class ParseController extends Controller
{
    public function fgoses()
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
            
            //dd($rows[2]->innerHtml());
            $arr = [];
            foreach($rows as $row)
            {
                $cols = $row->find('td');
                // array_push($arr, count($cols));
                // array_push($arr, $row->innerHtml());
                // array_push($arr,$cols[0]->text);

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
                    $requsites = str_replace('Приказ','утв. приказом',$requsites);
                    $link = $hrefs->getAttribute('href');

                    $fgos = Fgos::firstOrNew([
                        'code' => $code
                    ]);
                    $fgos->name = $name;
                    $fgos->link = $link;
                    $fgos->requsites = $requsites;
                    $fgos->fgos_level_id = $level_id;
                    $fgos->save();
                }
                
            }
        }
    }

    public function profstandarts()
    {
        $url = "https://profstandart.rosmintrud.ru/obshchiy-informatsionnyy-blok/natsionalnyy-reestr-professionalnykh-standartov/reestr-professionalnykh-standartov/CreateCSV4ALL.php";
        //$csv = array_map('str_getcsv', file($url));
        $data = file_get_contents($url);
        
        // $data = str_replace (array('\r\n', '\n', '\r'), ' ', $data);
        
        $data = str_getcsv(file_get_contents($url), "\n"); 
        
        foreach ($data as $row)
        {
            $row = str_getcsv($row, ";"); 
            //dd($row);
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
                $ps->fullName = $row[2].' '.$row[5].', утв. приказом Минтруда России №'.$row[6];
                $kind = ProfstandartKind::where('code', $code_parts[0])->first();
                if ($kind)
                {
                    $ps->kind_id = $kind->id;
                }
                
                $ps->save();
            }
        }
    }
}
