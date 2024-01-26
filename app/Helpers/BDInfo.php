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
        preg_match('/'.$fieldName.'\s*(.+)/', $string, $matches);

        return trim($matches[1] ?? '');
    }

    private function parseDiscSize($string)
    {
        preg_match('/Disc Size:\s*([\d,]+)/', $string, $matches);
        $bytesString = str_replace(',', '', $matches[1] ?? '0'); // 移除逗号
        $bytes = (int) $bytesString; // 将字符串转换为整数

        return $this->convertBytesToGigabytes($bytes);
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
        $audioData = [];
        if (preg_match('/AUDIO:\s*(.*?)\s*(?=SUBTITLES:|$)/s', $string, $matches)) {
            $audioLines = explode("\n", trim($matches[1]));
            if (strpos(strtolower($audioLines[0]), 'codec') !== false) {
                array_shift($audioLines);
            }
            foreach ($audioLines as $line) {
                if (!empty(trim($line))) {
                    $language = $this->getLanguageFromAudioSubtitleLine($line);
                    $flagUrl = language_flag($language); // 使用 language_flag 函数
                    $audioData[] = [
                        'line' => trim($line),
                        'flag' => $flagUrl
                    ];
                }
            }
        }
        return $audioData;
    }

    private function parseSubtitles($string)
    {
        $subtitleData = [];
        if (preg_match('/SUBTITLES:\s*(.*)/s', $string, $matches)) {
            $subtitleLines = explode("\n", trim($matches[1]));
            if (strpos(strtolower($subtitleLines[0]), 'presentation') !== false) {
                array_shift($subtitleLines);
            }
            foreach ($subtitleLines as $line) {
                if (!empty(trim($line))) {
                    $language = $this->getLanguageFromAudioSubtitleLine($line);
                    $flagUrl = language_flag($language); // 使用 language_flag 函数
                    $subtitleData[] = [
                        'line' => trim($line),
                        'flag' => $flagUrl
                    ];
                }
            }
        }
        return $subtitleData;
    }

    private function parseMultipleLines($string, $sectionName)
    {
        // 解析多行数据（适用于 Quick Summary 模板）
        $data = [];
        // 正则表达式匹配节名称后的所有内容，直到遇到下一个节名称或字符串结束
        $pattern = '/'.$sectionName.':\s*(.*?)(?=\n\S+:|\Z)/s';

        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            // 匹配到的每一段包含整个 Audio 或 Subtitle 节的内容
            $data[] = trim($match[1]);
        }

        return $data;
    }

    private function getLanguageFromAudioSubtitleLine($line)
    {
        $language = null;
        if (preg_match('/\b(\w+)\s*(Audio|Subtitle)\b/', $line, $matches)) {
            $language = $matches[1];
        }
        return $language;
    }

}
