<?php 

try {
	$mongodb = new Mongo();
	$articleCollection = $mongodb->myblogsite->articles;
} catch (MongoConnectionException $e) {
	die('Failed to connect to MongoDB ' . $e->getMessage());
}

$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
$articlesPerPage = 5;
$skip = ($currentPage - 1 ) * $articlesPerPage;
$cursor = $articleCollection->find(array(), array('title','saved_at'));
$totalArticles = $cursor->count();
$totalPages = (int) ceil($totalArticles / $articlesPerPage);
$cursor->sort(array('saved_at' => -1))->skip($skip)->limit($articlesPerPage);

 ?>
 <html>
 <head>
 	<link rel="stylesheet" href="style.css">
 	<style type="text/css" media="screen">
		body { font-size: 13px; }
		div#contentarea { width : 650px; }
	</style>
 	<title>Dashboard</title>
 </head>
 <body>
 	<div id="contentarea">
 		<div id="innercontentarea">
 			<h1>Dashboard</h1>
 			<table class="articles" cellspacing="0" cellpadding="0">
 				<thead>
 					<tr>
 						<th width="55%">Title</th>
 						<th width="27%">Created at</th>
 						<th width="*">Action</th>
 					</tr>
 				</thead>
 				<tbody>
 					<?php 
 						while ($cursor->hasNext()): 
 							$article = $cursor->getNext();
 					?>
 					<tr>
 						<td><?php echo substr($article['title'], 0, 35). '...'; ?></td>
 						<td><?php print date('g:i a, F j', $article['saved_at']->sec); ?></td>
 						<td class="url">
 							<a href="blog.php?id=<?php echo $article['_id']; ?>">View</a>
 							<a href="edit.php?id=<?php echo $article['_id']; ?>">Edit</a>
 						</td>
 					</tr>
 				<?php endwhile; ?>
 				</tbody>
 			</table>
 		</div><!-- / -->
 		<div id="navigation">
			<div class="prev">
				<?php if($currentPage !== 1): ?>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage - 1); ?>">Previous </a>
				<?php endif; ?>
			</div>
			<div class="page-number">
				<?php echo $currentPage; ?>
			</div>
			<div class="next">
				<?php if($currentPage !== $totalPages): ?>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage + 1); ?>">Next</a>
				<?php endif; ?>
			</div>
			<br class="clear"/>
		</div>
 	</div><!-- / -->
 </body>
 </html>