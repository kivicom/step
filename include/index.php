<?php
$db = new Comment(Connection::make($config['database']));
$comments = $db->getAll('comments');