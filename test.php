<?php
require('phpQuery/phpQuery.php');
require_once 'nurseful.php';
?>

<?php
$start = microtime(true);
sleep(1);

// ファイル名
$file_path = "export-".date("ymd-gis").".csv";

$url = "https://nurseful.jp/career/";

//item -> itemlist -> suburb-> area -> all
$areaList = scrapingNurseful($url);
//var_dump($areaList);

$suburbList=[];
foreach ($areaList as $area){
    $suburb = scrapingArea($area["url"]);
    $suburbList = array_merge($suburbList,$suburb);
}



$detailList =[];
foreach ($suburbList as $suburb){

    $detail = scrapingList($suburb["url"]);
    $detailList = array_merge($detailList,$detail);

}


$itemList =[];

foreach ($detailList as $item){

    var_dump($item["url"]);
    $itemList[] = scrapingItem($item["url"]);

}

createCSV($file_path,$itemList);

$end = microtime(true);

echo "スクレイピング完了しました<br>";
echo "処理時間：" . ($end - $start) . "秒";

?>

