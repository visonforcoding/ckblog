<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Index'), ['action' => 'edit', $index->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Index'), ['action' => 'delete', $index->id], ['confirm' => __('Are you sure you want to delete # {0}?', $index->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Index'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Index'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="index view large-9 medium-8 columns content">
    <h3><?= h($index->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($index->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Guide') ?></th>
            <td><?= h($index->guide) ?></td>
        </tr>
        <tr>
            <th><?= __('Cover') ?></th>
            <td><?= h($index->cover) ?></td>
        </tr>
        <tr>
            <th><?= __('Keywords') ?></th>
            <td><?= h($index->keywords) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($index->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($index->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Category Id') ?></th>
            <td><?= $this->Number->format($index->category_id) ?></td>
        </tr>
        <tr>
            <th><?= __('User Id') ?></th>
            <td><?= $this->Number->format($index->user_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Hits') ?></th>
            <td><?= $this->Number->format($index->hits) ?></td>
        </tr>
        <tr>
            <th><?= __('Ctime') ?></th>
            <td><?= h($index->ctime) ?></td>
        </tr>
        <tr>
            <th><?= __('Updatetime') ?></th>
            <td><?= h($index->updatetime) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($index->content)); ?>
    </div>
</div>
