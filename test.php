<?php


ob_start();

session_start();
$_SESSION['config'] = parse_ini_file("config.ini", true)['localhost'];

require_once('db/AwsUsersData.php');


echo $argc.PHP_EOL;
var_dump($argv);
echo PHP_EOL;


$test = new AwsUsersData();
echo 'Connect status : '.$test->GetConnectionStatus().PHP_EOL;

echo PHP_EOL.$test->RemoveRecoveryToken('w1').PHP_EOL;
var_dump($test->GetData());

// $test->GetInfo();

$fields = array('MainCity', 'Email', 'VerifiedEmail');
$values = array('asdas', 'm', false);
$compareOp = array('CONTAINS', 'CONTAINS');




//TODO
// Add enums for comprasions

// print_r($test->FindItem($fields, $values, $compareOp));

// if ($argv[1]=='d')
// echo PHP_EOL.'Deleted: '.$test->DeleteItem($argv[2]);
// elseif ($argv[1]=='a')
// echo PHP_EOL.'Insert status = '.$test->AddItem($argv[2], $fields, $values);

// echo PHP_EOL.'Insert status = '.$test->AddItem('aa1', $fields, $values);

// echo PHP_EOL.'Deleted: '.$test->DeleteItem('aa1');

// echo $test->GetItem('w1');

// echo PHP_EOL.$test->UpdateItem('w2',['Email', 'MainCity', 'Cities'], ['new@123', 'Mancester', ['Spb', 'Msk']]);

// echo PHP_EOL.$test->RemoveFields('w2',['Email', 'Password']);







// $start = microtime(true);
// print_r($test->CheckPrimaryValue('w1'));
// echo 'Время выполнения скрипта QUERY exists: '.round(microtime(true) - $start, 4).' сек.'.PHP_EOL;

// $start = microtime(true);
// $test->GetItem('w1');
// echo 'Время выполнения скрипта GetIetm exists: '.round(microtime(true) - $start, 4).' сек.'.PHP_EOL;

// $start = microtime(true);
// $test->GetItem('wsssss1');
// echo 'Время выполнения скрипта GetIetm not exists: '.round(microtime(true) - $start, 4).' сек.'.PHP_EOL;

// $start = microtime(true);
// $test->CheckPrimaryValue('wwwwwww1');
// echo 'Время выполнения скрипта QueryNotExists: '.round(microtime(true) - $start, 4).' сек.'.PHP_EOL;





?>
