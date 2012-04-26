<?php 

$id = $_GET['id'];

try {
	$connection = new Mongo();
	$database 	= $connection->selectDB('myblogsite');
	$collection	= $database->selectCollection('articles');
} catch (MongoConnectionException $e) {
	die("Failled to connect " . $e->getMessage());
}
$article = $collection->findOne(array('_id' => new MongoId($id)));

 ?>

 <html>
 <head>
 	<link rel="stylesheet" href="style.css">
 	<title>My Blog Site</title>
 </head>
 <body>
 	<div id="contentarea">
 		<div id="innercontentarea">
 			<h2><?php echo $article['title']; ?></h2>
 			<p>
 				<?php echo $article['content']; ?>
 			</p>
 		</div><!-- / -->
 	</div><!-- / -->
 </body>
 </html>