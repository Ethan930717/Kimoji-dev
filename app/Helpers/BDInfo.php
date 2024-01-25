<?php

namespace App\Helpers;

class BDInfo
{
    public function parse($string)
    {
        // 检查是否为 Quick Summary 模板
        if (!str_contains($string, 'PLAYLIST REPORT:')) {
            return $this->parseQuickSummary($string);
        }

        // 完整模板的解析逻辑
        return $this->parseFullTemplate($string);
    }

    protected function parseQuickSummary($string)
    {
        // Quick Summary 模板的解析逻辑
        return [
            'disc_title'    => $this->parseSingleLine($string, 'Disc Title:'),
            'disc_label'    => $this->parseSingleLine($string, 'Disc Label:'),
            'disc_size'     => $this->parseDiscSize($string),
            'total_bitrate' => $this->parseSingleLine($string, 'Total Bitrate:'),
            'video'         => $this->parseSingleLine($string, 'Video:'),
            'audio'         => $this->parseMultipleLines($string, 'Audio:'),
            'subtitles'     => $this->parseMultipleLines($string, 'Subtitle:'),
        ];
    }

    protected function parseFullTemplate($string)
    {
        // 完整模板的解析逻辑
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
        preg_match('/'.$fieldName.'\s*:\s*(.+)/', $string, $matches);

        return trim($matches[1] ?? '');
    }

    private function parseDiscSize($string)
    {
        // 正则表达式调整为匹配可能包含逗号的数字
        preg_match('/Disc Size:\s*([\d,]+)/', $string, $matches);

        // 调用修改后的convertBytesToGigabytes函数
        return $this->convertBytesToGigabytes($matches[1] ?? '0');
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
        $section = preg_replace('/Codec\s+Bitrate\s+Description\s*\n[-\s]+/s', '', $section);
        return preg_replace('/^\s*-{5}\s+-{7}\s+-{11}\s*$/m', '', $section);
    }

    private function convertBytesToGigabytes($bytes)
    {
        return round($bytes / (1024 ** 3), 2).' GB'; // 将字节转换为千兆字节
    }

    private function parseAudio($string)
    {
        // 解析音频信息并返回数组
        $audioData = [];

        if (preg_match('/AUDIO:\s*(.*?)\s*(?=SUBTITLES:|$)/s', $string, $matches)) {
            preg_match_all('/(\w+\s*Audio)\s*(\w+)\s*(\d+\s*kbps)/', $matches[1], $audioMatches, PREG_SET_ORDER);

            foreach ($audioMatches as $match) {
                $audioData[] = [
                    'format'   => $match[1],
                    'language' => $match[2],
                    'bit_rate' => $match[3],
                ];
            }
        }

        return $audioData;
    }

    private function parseSubtitles($string)
    {
        // 解析字幕信息并返回数组
        $subtitleData = [];

        if (preg_match('/SUBTITLES:\s*(.*)/s', $string, $matches)) {
            preg_match_all('/Presentation Graphics\s*(\w+)\s*(\d+\s*kbps)/', $matches[1], $subtitleMatches, PREG_SET_ORDER);

            foreach ($subtitleMatches as $match) {
                $subtitleData[] = [
                    'language' => $match[1],
                    'bit_rate' => $match[2],
                ];
            }
        }

        return $subtitleData;
    }

    private function parseMultipleLines($string, $sectionName)
    {
        // 解析多行数据（适用于 Quick Summary 模板）
        $data = [];
        $pattern = '/'.$sectionName.'\s*([^:]+(?:\s*\(.*?\))?)/';
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);


        foreach ($matches as $match) {
            $data[] = trim($match[1]);
        }

        return $data;
    }
}
