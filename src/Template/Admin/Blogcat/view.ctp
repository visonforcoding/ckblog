<head>
    <link rel="stylesheet" type="text/css" href="/wpadmin/lib/zui/css/zui.min.css"/>
</head>
<body>
<div class="blogcat view large-9 medium-8 columns content">
    <h3><?= h($blogcat->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($blogcat->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($blogcat->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Pid') ?></th>
            <td><?= $this->Number->format($blogcat->pid) ?></td>
        </tr>
        <tr>
            <th><?= __('Rank') ?></th>
            <td><?= $this->Number->format($blogcat->rank) ?></td>
        </tr>
        <tr>
            <th><?= __('Depth') ?></th>
            <td><?= $this->Number->format($blogcat->depth) ?></td>
        </tr>
        <tr>
            <th><?= __('Ctime') ?></th>
            <td><?= h($blogcat->ctime) ?></td>
        </tr>
    </table>
</div>
</body>
