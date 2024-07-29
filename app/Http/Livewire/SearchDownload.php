<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Overtrue\Pinyin\Pinyin;

class SearchDownload extends Component
{
    public $query;
    public $results = [];
    public $downloadUrl;
    public $page = 1;
    public $totalResults = 0;
    public $perPage = 20; // 每页默认20个结果

    public function search()
    {
        // 判断关键词是否包含简体中文，如果是则转换为繁体中文
        if (preg_match('/\p{Han}+/u', $this->query)) {
            $pinyin = new Pinyin();
            $this->query = $pinyin->convert($this->query, PINYIN_TW);
        }

        $response = Http::get('https://www.qobuz.com/fr-fr/search', [
            'q' => $this->query,
            'page' => $this->page,
        ]);

        $html = $response->body();

        // 解析 HTML 内容
        $this->results = $this->parseResults($html);
        $this->totalResults = $this->getTotalResults($html);
    }

    public function parseResults($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);
        $searchResults = $xpath->query('//div[@class="search-results"]/div[@class="product item clear-fix hproduct"]');

        $results = [];

        foreach ($searchResults as $result) {
            $albumCover = $xpath->query('.//div[@class="album-cover photo"]//img', $result)->item(0)->getAttribute('src');
            $title = $xpath->query('.//h3[@class="album-title"]', $result)->item(0)->nodeValue;
            $artist = $xpath->query('.//h4[@class="artist-name"]', $result)->item(0)->nodeValue;
            $category = $xpath->query('.//span[@class="category"]', $result)->item(0)->nodeValue;
            $releaseDate = $xpath->query('.//p[@class="data overflow"]/span', $result)->item(1)->nodeValue;
            $label = $xpath->query('.//p[@class="data overflow"]/span[@class="brand"]', $result)->item(0)->nodeValue;
            $url = $xpath->query('.//div[@class="action"]/ul/li/a', $result)->item(0)->getAttribute('href');

            $results[] = [
                'cover' => $albumCover,
                'title' => $title,
                'artist' => $artist,
                'category' => $category,
                'release_date' => $releaseDate,
                'label' => $label,
                'url' => $url,
            ];
        }

        return $results;
    }

    public function getTotalResults($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);
        $totalResultsNode = $xpath->query('//div[@class="search-result-info"]/b')->item(0);

        return $totalResultsNode ? (int)$totalResultsNode->nodeValue : 0;
    }

    public function setPage($page)
    {
        $this->page = $page;
        $this->search();
    }

    public function setDownloadUrl($url)
    {
        $this->downloadUrl = 'https://www.qobuz.com' . $url;
    }

    public function download()
    {
        // 这里调用你的 Python 脚本来下载专辑
        $scriptPath = base_path('app/OrpheusDL_qobuz/orpheus.py');
        $command = "python3 $scriptPath '{$this->downloadUrl}'";
        exec($command, $output, $return_var);

        if ($return_var === 0) {
            session()->flash('message', '专辑下载已开始。');
        } else {
            session()->flash('message', '专辑下载失败，请稍后重试。');
        }
    }

    public function render()
    {
        return view('livewire.search-download', [
            'results' => $this->results,
            'totalResults' => $this->totalResults,
            'perPage' => $this->perPage,
            'currentPage' => $this->page,
        ]);
    }
}
