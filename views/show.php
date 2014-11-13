<?php $view->extend('layout.php') ?>

<?php $view['slots']->start('content') ?>

    <p>
        <strong>Id</strong>: <?php echo $task['id'] ?><br/>
        <strong>Title</strong>: <?php echo $task['title'] ?><br/>
        <strong>Status</strong>: <?php echo $task['is_done'] ? 'done' : 'not finished' ?>
    </p>

<?php $view['slots']->stop() ?>
