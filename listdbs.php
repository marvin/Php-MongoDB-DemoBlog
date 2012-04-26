<?php 

try {
	$mongo 		= new Mongo($server="mongodb://localhost:27017", $options=array('timeout' => 100));
	$databases	= $mongo->listDBs();
	echo '<pre>';
	print_r($databases);
	$mongo->close();
} catch (MongoConnectionException $e) {
	die($e->getMessage());
}

 ?>