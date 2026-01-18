<?php //aggregate pipeline Denenecek
include("../set_mng.php"); 
@$collection=$db->Fixtures;
$cursor = $collection->aggregate([
	[
		'$match'=>[ 'state'=>'A']
	],
    ['$lookup'=>
		[
			'from'=>'personel',
			'localField'=>'username',
			'foreignField'=>'username',
			'let'=> [ 'username'=>'$username' ],
			'pipeline'=> [
				[
				  '$match'=> [
					'$expr'=> [ '$in'=> [['displayname'], ['department'] ]]
				  ]
				]
			],
			'as'=> 'persons'
		]
	],
	[
       '$addFields'=> [
			'displayname'=> '$persons.displayname',
			'department'=> '$persons.department',
		],
    ],
	[
		'$sort' => [
		  'code' => -1, 
		],
	],
]);//*/
$fsatir=[]; $fsay=0;
foreach ($cursor as $formsatir) {
	$satir=[]; 
	$satir['_id']=$formsatir->_id;  
	$satir['code']=$formsatir->code; 
	$satir['type']=$formsatir->type; 
	$satir['description']=$formsatir->description; 
	$satir['serialnumber']=$formsatir->serialnumber;
	$satir['place']=$formsatir->place;
	$satir['fixtaccrecord']=$formsatir->fixtaccrecord;
	$satir['state']=$formsatir->state; 
	$satir['username']=$formsatir->username;
	$satir['displayname']=$formsatir->displayname;
	$satir['department']=$formsatir->department;
	$fsatir[]=$satir;
	$fsay++;
}
echo "fsay: ".$fsay; //
var_dump($fsatir);
?>