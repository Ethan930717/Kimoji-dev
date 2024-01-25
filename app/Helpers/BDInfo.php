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
            'audio'         => $this->parseSection($string, 'AUDIO:', 'SUBTITLES:'),
            'subtitles'     => $this->parseSection($string, 'SUBTITLES:', 'FILES:'),
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
        return round($bytes / (1024 ** 3), 2) . ' GB'; // 将字节转换为千兆字节
    }

    private function parseAudio($string)
    {
        $audioData = [];
        $pattern = '/Audio:\s*(.*?)\s*(?=Subtitle:|FILES:|$)/si';
        preg_match($pattern, $string, $matches);
        $audioLines = explode("\n", $matches[1] ?? '');

        foreach ($audioLines as $line) {
            if (preg_match('/(\w+)\s*Audio\s*(\w+)\s*(\d+)\s*kbps/', $line, $matches)) {
                $audioData[] = [
                    'format' => $matches[1],
                    'language' => $matches[2],
                    'bit_rate' => $matches[3] . ' kbps',
                ];
            }
        }

        return $audioData;
    }

    private function parseSubtitles($string)
    {
        $subtitleData = [];
        $pattern = '/Subtitle:\s*(.*?)\s*(?=FILES:|$)/si';
        preg_match($pattern, $string, $matches);
        $subtitleLines = explode("\n", $matches[1] ?? '');

        foreach ($subtitleLines as $line) {
            if (preg_match('/(\w+)\s*(\d+)\s*kbps/', $line, $matches)) {
                $subtitleData[] = [
                    'language' => $matches[1],
                    'bit_rate' => $matches[2] . ' kbps',
                ];
            }
        }

        return $subtitleData;
    }
}
