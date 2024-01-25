<?php

namespace App\Helpers;

class BDInfo
{
    public function parse($string)
    {
        return [
            'disc_title'    => $this->parseSingleLine($string, 'Disc Title:'),
            'disc_label'    => $this->parseSingleLine($string, 'Disc Label:'),
            'disc_size'     => $this->parseDiscSize($string),
            'total_bitrate' => $this->parseTotalBitrate($string),
            'video'         => $this->parseSection($string, 'VIDEO:', 'AUDIO:'),
            'audio'         => $this->parseAudio($string),
            'subtitles'     => $this->parseSubtitles($string),
        ];
    }

    private function parseSingleLine($string, $fieldName)
    {
        preg_match('/'.$fieldName.'\s*(.+)/', $string, $matches);

        return trim($matches[1] ?? '');
    }

    private function parseDiscSize($string)
    {
        preg_match('/Disc Size:\s*(\d+)/', $string, $matches);

        return $this->convertBytesToGigabytes($matches[1] ?? 0);
    }

    private function parseTotalBitrate($string)
    {
        preg_match('/Total Bitrate:\s*(.+?)\s*$/m', $string, $matches);

        return trim($matches[1] ?? '');
    }

    private function parseSection($string, $sectionName, $nextSectionName)
    {
        preg_match('/'.$sectionName.'\s*(.*?)\s*(?='.$nextSectionName.'|$)/s', $string, $matches);

        return $this->cleanSection($matches[1] ?? '');
    }

    private function cleanSection($section)
    {
        // 删除所有单独的分隔符行
        return preg_replace('/^\s*[-]+\s*$/m', '', $section);
    }

    private function convertBytesToGigabytes($bytes)
    {
        return round($bytes / (1024 ** 3), 2).' GB'; // 将字节转换为千兆字节
    }

    private function parseAudio($string)
    {
        // 修改后的 parseAudio 方法
        // 解析音频信息并返回数组
        $audioData = [];
        preg_match_all('/(\w+ Audio)\s*(\w+)\s*(\d+ kbps)/', $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $audioData[] = [
                'format'   => $match[1],
                'language' => $match[2],
                'bit_rate' => $match[3],
            ];
        }

        return $audioData;
    }

    private function parseSubtitles($string)
    {
        // 修改后的 parseSubtitles 方法
        // 解析字幕信息并返回数组
        $subtitleData = [];
        preg_match_all('/Presentation Graphics\s*(\w+)\s*(\d+ kbps)/', $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $subtitleData[] = [
                'language' => $match[1],
                'bit_rate' => $match[2],
            ];
        }

        return $subtitleData;
    }
}
