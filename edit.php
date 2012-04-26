<?php 

$action = (!empty($_POST['btn_submit']) && ($_POST['btn_submit'] === 'Save')) ? 'save_article' : 'show_form';

$id = $_REQUEST['id'];

try {
	$mongodb = new Mongo();
	$articleCollection = $mongodb->myblogsite->articles;
} catch (MongoConnectionException $e) {
	die('Failed to connect to MongoDB ' . $e->getMessage());
}

switch ($action) {
	case 'save_article':
		$article 				= array();
		$article['title']		= $_POST['title'];
		$article['content']		= $_POST['content'];
		$article['saved_at']	= new MongoDate();
		$articleCollection->update(array('_id' => new MongoId($id)), $article);
		break;
	
	case 'show_form':
	default:
		$article = $articleCollection->findOne(array('_id' => new MongoId($id)));
}
 ?>
 <html>
 <head>
 	<link rel="stylesheet" href="style.css">
 	<title>Blog Post Editor</title>
 </head>
 <body>
 	<div id="contentarea">
 		<div id="innercontentarea">
 			<h1>Blog Post Creator</h1>
 			<?php if ($action === 'show_form'): ?>
 				<form action="edit.php" method="post">
 					<h3>Title</h3>
 					<p><input type="text" name="title" value="<?php echo $article['title']; ?>" id="title"></p>
 					<h3>Content</h3>
 					<p><textarea name="content" rows="20"><?php echo $article['content']; ?></textarea></p>
 					<input type="hidden" name="id" value="<?php echo $article['_id']; ?>" />
 					<p><input type="submit" name="btn_submit" value="Save"></p>
 				</form>
 			<?php else: ?>
 				<p>
 					Article saved. _id: <?php echo $id; ?>.
 					<a href="blog.php?id=<?php echo $id; ?>">Read it.</a>
 				</p>
 			<?php endif; ?>
 		</div><!-- / -->
 	</div><!-- / -->
 </body>
 </html>
