<?php

namespace App\Shell;

use Cake\Console\Shell;
use Goutte\Client;
/**
 * Joke shell command.
 */
class JokeShell extends Shell {

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

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser() {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() {
        $this->out($this->OptionParser->help());
    }

    public function craw() {
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
                    'create_time' => date('Y-m-d H:i:s')
                ];
                $downcount++;
            }
        }
        $end = time();
        $duration = $end - $start;
        $query = $JokeTable->query()->insert(['hash', 'url', 'title', 'img', 'create_time']);
        foreach ($datas as $k => $value) {
            $query->values($value);
        }
        $ins = $query->execute();
        if ($ins) {
            \Cake\Log\Log::notice('脚本执行结束,耗时:' . $duration . '秒,共下载数据:' . $downcount, 'cron');
        } else {
            \Cake\Log\Log::error('在插入数据库时发生了错误', 'cron');
        }
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
