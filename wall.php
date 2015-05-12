<?php
	session_start();
	require_once('./include/connection.php');
	if(!isset($_SESSION["logged_in"])){
		session_destroy();
		header("Location: index.php");
		die();
	}

	$messages = fetch_all("SELECT messages.*, users.first_name, users.last_name
								FROM messages
								LEFT JOIN users
								ON users.id = messages.user_id
								ORDER BY id DESC");

	$comments = fetch_all("SELECT comments.*, users.first_name, users.last_name 
								FROM comments
								LEFT JOIN users
								ON users.id = comments.user_id");
?>
<html>
	<head>
		<title>The Wall</title>
		<style type="text/css">
			.message{
				background-color: blue;
				color: white;
			}
			p, h3 {
				margin-left: 15px;
			}
			.comment{
				background-color: green;
				color: white;
			}
		</style>
	</head>
	<body>
		<h1>The Wall</h1>
		<a href="process.php">Log out</a>
		<div class="messages">
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="create_message">
				<textarea name="message"></textarea>
				<input class="message" type="submit" value="Post a message">
			</form>
		</div>
<?php
		foreach($messages as $message)
		{ ?>
		<h2><?= htmlentities($message["first_name"]) ?> <?= htmlentities($message["last_name"]) ?>  posted at (<?= $message["created_at"] ?>)</h2>
		<p><?= htmlentities($message["message"]) ?></p>
<?php
		$comments = fetch_all("SELECT comments.*, users.first_name, users.last_name 
									FROM comments
									LEFT JOIN users
									ON users.id = comments.user_id
									WHERE comments.message_id =". $message['id']);

		foreach($comments as $comment)
		{
			if($comment["message_id"] == $message['id'])
			{
?>
		<h3><?= $comment["first_name"] ?> <?= $comment["last_name"] ?>  commented at (<?= $comment["created_at"] ?> )</h3>
		<p><?= $comment["comment"] ?> </p>
<?php   } } ?>

		<div class="comments">
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="create_comment">
				<input type="hidden" name="message_id" value="<?= $message["id"] ?>">
				<textarea name="comment"></textarea>
				<input class="comment" type="submit" value="Post a comment">
			</form>
		</div>
<?php 	} ?>

	</body>
</html>