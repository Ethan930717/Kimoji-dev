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
            'total_bitrate' => $this->parseSingleLine($string, 'Total Bitrate:'),
            'video'         => $this->parseSections($string, 'Video:'),
            'audio'         => $this->parseSections($string, 'Audio:'),
            'subtitles'     => $this->parseSections($string, 'Subtitle:'),
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
        $bytes = $matches[1] ?? 0;
        $gigabytes = $bytes / (1024 ** 3); // Convert bytes to gigabytes
        return round($gigabytes, 2) . ' GB';
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
}
