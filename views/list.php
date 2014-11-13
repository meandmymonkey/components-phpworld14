<?php $view->extend('layout.php') ?>

<?php $view['slots']->start('content') ?>

    <form action="<?php echo $view['router']->generate('create'); ?>" method="post">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" size="45"/>
            <input type="hidden" name="action" value="create"/>
            <button type="submit">send</button>
        </div>
    </form>

    <p>
        There are <strong><?php echo count($tasks); ?></strong> tasks.
    </p>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($tasks as $task): ?>

            <tr>
                <td class="center"><?php echo $task['id']; ?></td>
                <td<?php if ($task['is_done']): ?> class="done"<?php endif; ?>>
                    <a href="<?php echo $view['router']->generate('show', array('id' => $task['id'])); ?>"><?php echo $task['title']; ?></a>
                </td>
                <td class="center">

                    <?php if ($task['is_done']): ?>
                        <span class="done">done</span>
                    <?php else: ?>
                        <form action="<?php echo $view['router']->generate('close', array('id' => $task['id'])); ?>"
                              method="post">
                            <button type="submit">close</button>
                        </form>
                    <?php endif; ?>

                </td>
                <td>
                    <form action="<?php echo $view['router']->generate('remove', array('id' => $task['id'])); ?>"
                          method="post">
                        <button type="submit">delete</button>
                    </form>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php $view['slots']->stop() ?>