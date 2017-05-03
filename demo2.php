<?php
require('phpQuery/phpQuery.php');
require_once 'nurseful.php';
require_once  'sqlAccess.php';
?>

<?php
$start = microtime(true);
sleep(1);
$areaList = accessSQL('suburb');

$i = 0;
$suburbList = [];
foreach ($areaList as $item){

    $id = $item['id'];
    $url = $item['url'];

    $suburb = scrapingList($url,$id);
        $suburbList = array_merge($suburbList,$suburb);
    $i ++;


}
var_dump($suburbList);

//var_dump($areaList);

//insertSQL($suburbList);




$end = microtime(true);


echo "処理時間：" . ($end - $start) . "秒";

?>

