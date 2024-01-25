<?php

namespace App\Helpers;

class BDInfo
{
    public function parse($string)
    {
        $parsedData = [
            'disc_info' => $this->parseDiscInfo($string),
            'playlist_report' => $this->parsePlaylistReport($string),
            'video' => $this->parseSection($string, 'VIDEO:'),
            'audio' => $this->parseSection($string, 'AUDIO:'),
            'subtitles' => $this->parseSection($string, 'SUBTITLES:'),
        ];

        return $parsedData;
    }

    private function parseDiscInfo($string)
    {
        // 解析 Disc Info 部分
        // 适当调整正则表达式以匹配您的数据
        preg_match('/Disc Title:\s*(.*?)\s*Disc Size:/s', $string, $matches);
        return $matches[1] ?? '';
    }

    private function parsePlaylistReport($string)
    {
        // 解析 Playlist Report 部分
        // 适当调整正则表达式以匹配您的数据
        preg_match('/PLAYLIST REPORT:\s*(.*?)\s*VIDEO:/s', $string, $matches);
        return $matches[1] ?? '';
    }

    private function parseSection($string, $sectionName)
    {
        preg_match('/'.$sectionName.'\s*(.*?)\s*(?:AUDIO:|SUBTITLES:|$)/s', $string, $matches);
        return $matches[1] ?? '';
    }
}
