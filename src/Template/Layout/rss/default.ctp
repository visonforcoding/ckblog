<?php
if (!isset($channel)):
<<<<<<< HEAD
    $channel = [];
=======
    $channel = array();
>>>>>>> c7e6b578f2d4857fd188f1a7574da5d1bf85eec6
endif;
if (!isset($channel['title'])):
    $channel['title'] = $this->fetch('title');
endif;

echo $this->Rss->document(
    $this->Rss->channel(
        [], $channel, $this->fetch('content')
    )
);
?>
