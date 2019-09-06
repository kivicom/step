<?php
$db = new Admin(Connection::make($config['database']));
$comments = $db->getAll();
