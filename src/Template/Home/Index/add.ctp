<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Index'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="index form large-9 medium-8 columns content">
    <?= $this->Form->create($index) ?>
    <fieldset>
        <legend><?= __('Add Index') ?></legend>
        <?php
            echo $this->Form->input('category_id');
            echo $this->Form->input('user_id');
            echo $this->Form->input('title');
            echo $this->Form->input('guide');
            echo $this->Form->input('cover');
            echo $this->Form->input('content');
            echo $this->Form->input('keywords');
            echo $this->Form->input('description');
            echo $this->Form->input('ctime');
            echo $this->Form->input('updatetime', ['empty' => true]);
            echo $this->Form->input('hits');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
