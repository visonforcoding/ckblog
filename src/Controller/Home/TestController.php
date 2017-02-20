<?php

namespace App\Controller\Home;

use App\Controller\Home\AppController;
use VDB\Spider\Spider;
use VDB\Spider\Discoverer\XPathExpressionDiscoverer;
use VDB\Spider\Event\SpiderEvents;
use Symfony\Component\EventDispatcher\Event;
use VDB\Spider\StatsHandler;
use Goutte\Client;

class TestController extends AppController {

    protected $file_map = array(
        'application/pdf' => '.pdf',
        'application/zip' => '.zip',
        'image/gif' => '.gif',
        'image/jpeg' => '.jpg',
        'image/png' => '.png',
        'text/css' => '.css',
        'text/html' => '.html',
        'text/javascript' => '.js',
        'text/plain' => '.txt',
        'text/xml' => '.xml',
    );

    public function index() {
        set_time_limit(0);
        $spider = new Spider('http://www.dmoz.org');
        // Add a URI discoverer. Without it, the spider does nothing. In this case, we want <a> tags from a certain <div>
        $spider->getDiscovererSet()->set(new XPathExpressionDiscoverer("//div[@id='catalogs']//a"));
        // Set some sane options for this example. In this case, we only get the first 10 items from the start page.
        $spider->getDiscovererSet()->maxDepth = 1;
        $spider->getQueueManager()->maxQueueSize = 10;
        // Let's add something to enable us to stop the script
        $spider->getDispatcher()->addListener(
                SpiderEvents::SPIDER_CRAWL_USER_STOPPED, function (Event $event) {
            echo "\nCrawl aborted by user.\n";
            exit();
        }
        );
        // Add a listener to collect stats to the Spider and the QueueMananger.
        // There are more components that dispatch events you can use.
        $statsHandler = new StatsHandler();
        $spider->getQueueManager()->getDispatcher()->addSubscriber($statsHandler);
        $spider->getDispatcher()->addSubscriber($statsHandler);
        // Execute crawl
        $spider->crawl();
        // Build a report
        echo "\n  ENQUEUED:  " . count($statsHandler->getQueued());
        echo "\n  SKIPPED:   " . count($statsHandler->getFiltered());
        echo "\n  FAILED:    " . count($statsHandler->getFailed());
        echo "\n  PERSISTED:    " . count($statsHandler->getPersisted());
        // Finally we could do some processing on the downloaded resources
        // In this example, we will echo the title of all resources
        echo "\n\nDOWNLOADED RESOURCES: ";
        foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {
            echo "\n - " . $resource->getCrawler()->filterXpath('//title')->text();
        }
        exit();
    }

    public function test() {
        \Cake\Log\Log::notice('脚本开始', 'cron');
        $start = time();
        $downcount = 0;
        set_time_limit(0);
        $client = new Client();
        $crawler = $client->request('GET', 'http://neihanshequ.com/pic/');
        $urls = [];
        $title = [];
        $crawler->filter('.img-wrapper img')->each(function ($node)use(&$urls) {
            $urls[] = $node->attr('data-src');
        });
        $crawler->filter('.upload-txt p')->each(function ($node)use(&$title) {
            $title[] = $node->text();
        });
        debug($title);
        $datas = [];
        $JokeTable = \Cake\ORM\TableRegistry::get('Joke');
        $client = new \Cake\Network\Http\Client();
        foreach ($urls as $k => $url) {
            $joke = $JokeTable->findByHash(md5($url))->first();
            if ($joke) {
                \Cake\Log\Log::warning($url . '已存在', 'cron');
                continue;
            }
            try {
                $res = $client->get($url, [], [
                    'timeout' => 30
                ]);
            } catch (\Exception $exc) {
                \Cake\Log\Log::error($url . '下载失败', 'cron');
                continue;
            }
            $content_type = $res->header('Content-Type');
            if (!isset($this->file_map[$content_type])) {
                continue;
            }
            $ext = $this->file_map[$content_type];
            $filename = uniqid() . $ext;
            if ($this->download($filename, $res->body())) {
                \Cake\Log\Log::notice('成功下载:' . $url, 'cron');
                $datas[] = [
                    'hash' => md5($url),
                    'url' => $url,
                    'title' => $title[$k],
                    'img' => $filename,
                    'create_time'=>date('Y-m-d H:i:s')
                ];
                $downcount++;
            }
        }
        $end = time();
        $duration = $end - $start;
        $query = $JokeTable->query()->insert(['hash', 'url', 'title', 'img','create_time']);
        foreach ($datas as $k => $value) {
            $query->values($value);
        }
        $ins = $query->execute();
        if($ins){
            \Cake\Log\Log::notice('脚本执行结束,耗时:' . $duration . '秒,共下载数据:' . $downcount, 'cron');
        }else{
            \Cake\Log\Log::error('在插入数据库时发生了错误', 'cron');
        }
        exit();
    }

    protected function download($filename, $source) {
        $today = date('Y-m-d');
        $path = WWW_ROOT . 'download/' . $today . '/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        try {
            return file_put_contents($path . $filename, $source);
        } catch (\Exception $exc) {
            return false;
        }
    }

}
