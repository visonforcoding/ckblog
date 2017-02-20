<?php
use Cake\Core\Configure;
<<<<<<< HEAD
use Cake\Error\Debugger;

$this->layout = 'error';
=======
>>>>>>> c7e6b578f2d4857fd188f1a7574da5d1bf85eec6

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
<<<<<<< HEAD
        <?php Debugger::dump($error->params) ?>
=======
        <?= Debugger::dump($error->params) ?>
>>>>>>> c7e6b578f2d4857fd188f1a7574da5d1bf85eec6
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<h2><?= h($message) ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= sprintf(
        __d('cake', 'The requested address %s was not found on this server.'),
        "<strong>'{$url}'</strong>"
    ) ?>
</p>
