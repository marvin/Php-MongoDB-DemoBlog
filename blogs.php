<?php 

try {
	$connection = new Mongo();
	$collection	= $connection->myblogsite->articles;
} catch (MongoConnectionException $e) {
	die("Failed to connect to db " . $e->getMessage());
}

$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
$articlesPerPage = 10;
$skip = ($currentPage - 1 ) * $articlesPerPage;
$cursor = $collection->find();
$totalArticles = $cursor->count();
$totalPages = (int) ceil($totalArticles / $articlesPerPage);
$cursor->sort(array('saved_at' => -1))->skip($skip)->limit($articlesPerPage);

?>

<html>
<head>
	<link rel="stylesheet" href="style.css">
	<title>My Blog Site</title>
</head>
<body>
	<div id="contentarea">
		<div id="innercontentarea">
			<h1>My Blogs</h1>
			<?php 
				while ($cursor->hasNext()): 
					$article = $cursor->getNext();
			?>
				<h2><?php echo $article['title']; ?></h2>
				<p>
					<?php echo substr($article['content'], 0,200).'...'; ?>
				</p>
				<a href="blog.php?id=<?php echo $article['_id']; ?>">Read more</a>
			<?php endwhile; ?>
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