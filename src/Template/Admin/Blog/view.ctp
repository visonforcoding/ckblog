<head>
    <link rel="stylesheet" type="text/css" href="/wpadmin/lib/zui/css/zui.min.css"/>
</head>
<body>
<div class="blog view large-9 medium-8 columns content">
    <h3><?= h($blog->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Blogcat') ?></th>
            <td><?= $blog->has('blogcat') ? $this->Html->link($blog->blogcat->name, ['controller' => 'Blogcat', 'action' => 'view', $blog->blogcat->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Admin') ?></th>
            <td><?= $blog->has('admin') ? $this->Html->link($blog->admin->id, ['controller' => 'Admin', 'action' => 'view', $blog->admin->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($blog->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Guide') ?></th>
            <td><?= h($blog->guide) ?></td>
        </tr>
        <tr>
            <th><?= __('Cover') ?></th>
            <td><?= h($blog->cover) ?></td>
        </tr>
        <tr>
            <th><?= __('Keywords') ?></th>
            <td><?= h($blog->keywords) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($blog->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($blog->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Hits') ?></th>
            <td><?= $this->Number->format($blog->hits) ?></td>
        </tr>
        <tr>
            <th><?= __('Ctime') ?></th>
            <td><?= h($blog->ctime) ?></td>
        </tr>
        <tr>
            <th><?= __('Updatetime') ?></th>
            <td><?= h($blog->updatetime) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4>seo描述</h4>
        <?= $this->Text->autoParagraph(h($blog->content)); ?>
    </div>
</div>
</body>
