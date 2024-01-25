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
        $bytes = $matches[1] ?? 0;
        return $this->convertBytesToGigabytes($bytes) . ' GB';
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
        // Remove lines that only contain separators like '-----'
        return preg_replace('/^\s*[-]+\s*$/m', '', $section);
    }

    private function convertBytesToGigabytes($bytes)
    {
        return round($bytes / (1024 ** 3), 2); // Convert bytes to gigabytes
    }
}