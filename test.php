<?php
ob_start();

session_start();
$_SESSION['config'] = parse_ini_file("config.ini", true)['localhost'];

require_once('db/AwsUsersData.php');

$test = new AwsUsersData();
echo $test->Status;

// $test->GetInfo();

$fields = array('Login', 'Email');
$values = array('w1', 'w1@rfbuild.ru');
$compareOp = array('');

$test->FindItem($fields, $values, $compareOp);


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
