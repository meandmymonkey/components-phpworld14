<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Todo Application</title>
    <link rel="stylesheet" media="screen" type="text/css" href="/style.css"/>
</head>
<body>
<div id="container">
    <h1><a href="<?php echo $view['router']->generate('list'); ?>">My Todo List</a></h1>
    <div id="content">

        <?php $view['slots']->output('content') ?>

    </div>
</div>
</body>
</html>
