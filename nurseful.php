<?php

function scrapingNurseful($url)
{
    //topページで地域のスクレイピング
    $html = file_get_contents($url);
    $top = phpQuery::newDocument($html);

    $areaItems = [];
    $i = 0;
    foreach ($top["#searchErea li.btnMap a"] as $areaLink) {
        if ($i >= 200) {
            break;
        }else{

            $href = pq($areaLink)->attr("href");
            $name = pq($areaLink)->find('img')->attr('alt');

            $areaItems[] =  array("name" => $name, "url"=>$href);
            $i ++;

        }
    }


    return $areaItems;
}


function scrapingArea($url,$id = null){
   // var_dump($url);
    //Listページのスクレイピング
    $html = file_get_contents('https://nurseful.jp'.$url);
    $doc = phpQuery::newDocument($html);
    $list=[];
    $i =0;
    foreach ($doc["#js_area_modal_content_input_area ul li"] as $link) {
        if ($i >= 200) {
            break;
        }else {
            if(pq($link)->find('a')->html()){
            $name = pq($link)->find('a')->text();
            $a = pq($link)->find('a')->attr("href");

            $list[] = array("name" => $name, "url" => "$a",'prefID' => $id);
            $i++;
            }

        }

    }

    return $list;

}

//list ページのアイテムのスクレイピング
function scrapingList($url, $id = null)
{
    $html = file_get_contents("https://nurseful.jp" . $url);
    $doc = phpQuery::newDocument($html);
    $detailList = [];
    $i= 0;


    foreach ($doc["div.facilities--content"] as $item) {
        if ($i >= 200) {
            break;
        }else {
           $table = pq($item)->find('table.tbl-data-04')->html();
         
            if($table){
                $name = pq($item)->find('h3.facilities--heading a')->text();
                $href = pq($item)->find('h3.facilities--heading a')->attr("href");
                $detailList[]=array("name" => $name, "url" => $href, 'suburbID' => $id);


            }

            $i++;
        }

    }
    return $detailList;

}

//詳細ページのアイテムのスクレイピング
function scrapingItem($url)
{
    $html = file_get_contents("https://nurseful.jp" . $url);
    $doc = phpQuery::newDocument($html);


    $pref = pq($doc['ul#topic-path'])->find('li:eq(1)')->text();
    $suburb = pq($doc['ul#topic-path'])->find('li:eq(2)')->text();
    // ">"が入ってるため削除
    $pref =substr($pref, 0, -1);
    $suburb = substr($suburb, 0, -1);


    //$item = array();
    pq($doc['h2.hdg-lev2-03'])->find('font')->remove();
    $title = pq($doc['h2.hdg-lev2-03'])->text();

    $doc['table.job-case-detail-03']->find("a")->remove();


    $tableItem=[];
    $tableItem[]=$title;
    $tableItem[]=$pref;
    $tableItem[]=$suburb;
    $labels=[];
    foreach ($doc['ul.list-icon-03 li'] as $icon ){

        $labels[] =  pq($icon)->text();

    }
    $labels= implode(",", $labels);

    $tableItem[] =$labels;

    foreach($doc['table.job-case-detail-03 tr'] as $items ) {

        //$items =pq($items)->find('td')->replaceWithPHP('/(\r?\n|\s)/g');
        $items= pq($items)->find('td')->html();



        // 複数スペースを一つへ
        $items = preg_replace('/\s{2,}/', "", $items);
        $items = str_replace("<br><br>", "<br>", $items);


        $tableItem[]=$items;

    }


    return $tableItem;


}
function createCSV($file_path,$itemList){


    $export_header = [];

// オブジェクト生成
    $file = new SplFileObject( $file_path, "w" );


// CSVに出力するタイトル行
    $export_csv_title = array(
        "タイトル",
        "都道府県",
        "市区町村",
        "ラベル",
        "勤務先住所/アクセス",
        "募集資格",
        "雇用形態",
        "勤務先・担当業務",
        "給与",
        "夜勤交代制・手当",
        "賞与",
        "勤務時間",
        "休日・休暇",
        "福利厚生・諸手当など",
        "社会保険",
        "業務について",
        "その他補足事項"
    );
// CSVに出力する内容

    foreach( $export_csv_title as $key => $val ) {
        $export_header[] = $val;
    }
// エンコードしたタイトル行を配列ごとCSVデータ化
    $file->fputcsv($export_csv_title);
    //var_dump($itemList);
    foreach ($itemList as $item) {
        $export_csv_body = [];
       foreach ($item as $val) {
           $export_csv_body[] = $val;
       }
        $file->fputcsv($export_csv_body);

    }

    return $file;
}
?>