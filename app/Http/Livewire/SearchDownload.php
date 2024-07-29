<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDownload extends Component
{
    public $query;
    public $results = [];
    public $downloadUrl;

    public function search()
    {
        $response = Http::get('https://www.qobuz.com/fr-fr/search', [
            'q' => $this->query,
        ]);

        $html = $response->body();

        // 解析 HTML 内容
        $this->results = $this->parseResults($html);
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

    public function setDownloadUrl($url)
    {
        $this->downloadUrl = 'https://www.qobuz.com' . $url;
    }

    public function download()
    {
        // 这里调用你的 Python 脚本来下载专辑
        $scriptPath = storage_path('app/OrpheusDL_qobuz/orpheus.py');
        $command = "python3 $scriptPath '{$this->downloadUrl}'";
        exec($command);

        session()->flash('message', '专辑下载已开始。');
    }

    public function render()
    {
        return view('livewire.search-download');
    }
}

