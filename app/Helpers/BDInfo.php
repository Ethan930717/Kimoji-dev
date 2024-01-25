<?php

namespace App\Helpers;

class BDInfo
{
    public function parse($string)
    {
        if (str_contains($string, 'PLAYLIST REPORT:')) {
            return $this->parseFullTemplate($string);
        }

        return $this->parseQuickSummary($string);
    }

    protected function parseQuickSummary($string)
    {
        return [
            'disc_title'    => $this->parseSingleLine($string, 'Disc Title:'),
            'disc_label'    => $this->parseSingleLine($string, 'Disc Label:'),
            'disc_size'     => $this->parseDiscSize($string),
            'total_bitrate' => $this->parseSingleLine($string, 'Total Bitrate:'),
            'video'         => $this->parseSingleLine($string, 'Video:'),
            'audio'         => $this->parseMultiline($string, 'Audio:'),
            'subtitles'     => $this->parseMultiline($string, 'Subtitle:'),
        ];
    }

    protected function parseFullTemplate($string)
    {
        return [
            'disc_info'       => $this->parseDiscInfo($string),
            'playlist_report' => $this->parsePlaylistReport($string),
            'video'           => $this->parseSections($string, 'VIDEO:'),
            'audio'           => $this->parseSections($string, 'AUDIO:'),
            'subtitles'       => $this->parseSections($string, 'SUBTITLES:'),
        ];
    }

    private function parseDiscInfo($string)
    {
        // 提取 Disc Info 部分，可能需要调整正则表达式以适应您的数据
        preg_match('/Disc Title:\s*(.*?)\s*Disc Size:/s', $string, $matches);

        return $matches[1] ?? '';
    }

    private function parsePlaylistReport($string)
    {
        // 提取 Playlist Report 部分，可能需要调整正则表达式以适应您的数据
        preg_match('/PLAYLIST REPORT:\s*(.*?)\s*(VIDEO:|AUDIO:|SUBTITLES:|$)/s', $string, $matches);

        return $matches[1] ?? '';
    }

    private function parseSingleLine($string, $fieldName)
    {
        preg_match('/'.$fieldName.'\s*(.+)/', $string, $matches);

        return trim($matches[1] ?? '');
    }

    private function parseDiscSize($string)
    {
        preg_match('/Disc Size:\s*(\d+)/', $string, $matches);
        $bytes = $matches[1] ?? 0;
        $gigabytes = $bytes / (1024 ** 3); // Convert bytes to gigabytes

        return round($gigabytes, 2).' GB';
    }

    private function parseSections($string, $sectionName)
    {
        $sections = [];
        $pattern = '/'.preg_quote($sectionName, '/').'(.*?)\s*(?=(Video:|Audio:|Subtitle:|Disc Title:|Disc Label:|$))/si';
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $sections[] = trim($match[1]);
        }

        return $sections;
    }

    private function parseMultiline($string, $sectionName)
    {
        $pattern = '/'.preg_quote($sectionName, '/').'(.*?)(?=Audio:|Subtitle:|Disc Title:|Disc Label:|$)/si';
        preg_match_all($pattern, $string, $matches);

        return array_map('trim', $matches[1]);
    }
}
