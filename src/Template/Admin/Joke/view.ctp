<head>
    <link rel="stylesheet" type="text/css" href="/wpadmin/lib/zui/css/zui.min.css"/>
</head>
<body>
<div class="joke view large-9 medium-8 columns content">
    <h3><?= h($joke->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Hash') ?></th>
            <td><?= h($joke->hash) ?></td>
        </tr>
        <tr>
            <th><?= __('Url') ?></th>
            <td><?= h($joke->url) ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($joke->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Img') ?></th>
            <td><?= h($joke->img) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($joke->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Create Time') ?></th>
            <td><?= h($joke->create_time) ?></td>
        </tr>
    </table>
</div>
</body>
