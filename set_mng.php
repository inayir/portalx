<?php
declare(strict_types=1);
namespace MongoDB\Examples\Bulk;
use MongoDB\Client;
use MongoDB\Driver\WriteConcern;
use function assert;
use function getenv;
use function is_object;
use function MongoDB\BSON\fromPHP;
use function MongoDB\BSON\toRelaxedExtendedJSON;
use function printf;
require __DIR__ . '/vendor/autoload.php';

include('get_ini.php');
if(isset($ini['MongoConnection'])){
	@$client = new Client($ini['MongoConnection']);
	$dbi=$ini['MongoDB']; 
	@$db=$client->$dbi; 
	//echo "Connection OK";
}
?>