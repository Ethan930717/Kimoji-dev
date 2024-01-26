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

        if ($fieldName === 'Video:') {
            return $this->parseVideoParameters(trim($matches[1] ?? ''));
        }

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
        $section = $this->cleanSection($matches[1] ?? '');

        // 检测是否为 Quick Summary 模板
        if ($sectionName == 'VIDEO:' && !str_contains($section, "\n")) {
            // Quick Summary 模板，单行视频信息
            return $this->parseVideoParameters($section);
        }

        if ($sectionName == 'VIDEO:') {
            // 完整模板，视频信息可能有多行
            $videos = explode("\n", $section);

            return array_map([$this, 'parseVideoParameters'], $videos);
        }

        // 如果不是 VIDEO 部分，保持原样返回
        return $section;
    }

    private function cleanSection($section)
    {
        $section = preg_replace('/Codec\s+Bitrate\s+Description\s*\n[-\s]+/s', '', $section);

        return preg_replace('/^\s*-{5}\s+-{7}\s+-{11}\s*$/m', '', $section);
    }

    private function parseVideoParameters($videoString)
    {
        $videoData = [];

        if (preg_match('/(.+?)\s+Video\s+(\d+)\s+kbps\s+(\d+p)\s+\/\s+(\d+\.\d+\s+fps)\s+\/\s+(\d+:\d+)\s+\/\s+(.+)/', $videoString, $matches)) {
            $videoData = [
                'format'         => $matches[1],
                'bitrate'        => $matches[2].' kbps',
                'resolution'     => $matches[3],
                'frame_rate'     => $matches[4],
                'aspect_ratio'   => $matches[5],
                'profile_level'  => $matches[6]
            ];

            // 检测并提取附加参数
            if (isset($matches[7])) {
                $additionalParams = $matches[7];
                if (preg_match('/(\d+:\d+:\d+)/', $additionalParams, $chromaMatches)) {
                    $videoData['chroma_subsampling'] = $chromaMatches[1];
                }
                if (preg_match('/(\d+\s+bits)/', $additionalParams, $depthMatches)) {
                    $videoData['color_depth'] = $depthMatches[1];
                }
                if (preg_match('/(\d+\s+nits)/', $additionalParams, $brightnessMatches)) {
                    $videoData['peak_brightness'] = $brightnessMatches[1];
                }
                if (preg_match('/(HDR\d+)/', $additionalParams, $hdrMatches)) {
                    $videoData['hdr_format'] = $hdrMatches[1];
                }
                if (preg_match('/(BT\.\d+)/', $additionalParams, $colorSpaceMatches)) {
                    $videoData['color_space'] = $colorSpaceMatches[1];
                }
            }
        }

        return $videoData;
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
            array_shift($audioLines); // 跳过第一行（分割符）

            foreach ($audioLines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    $countryCode = $this->mapLanguageToCountryCode($line);
                    $audioData[] = [
                        'info'         => $line,
                        'country_code' => $countryCode
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

            foreach ($subtitleLines as $line) {
                $countryCode = $this->mapLanguageToCountryCode($line);

                if ($countryCode) {
                    $subtitleData[] = [
                        'line'         => $line,
                        'country_code' => $countryCode
                    ];
                }
            }
        }

        return $subtitleData;
    }

    private function parseMultipleLines($string, $sectionName)
    {
        $data = [];

        $pattern = '/'.$sectionName.':\s*(.+)/';

        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $line = trim($match[1]);

            if (!empty($line)) {
                $countryCode = $this->mapLanguageToCountryCode($line); // 获取国家代码

                $data[] = [
                    'info'         => $line,
                    'country_code' => $countryCode
                ];
            }
        }

        return $data;
    }

    private function mapLanguageToCountryCode($line)
    {
        $mapping = [
            'English'                 => 'us', '英语' => 'us',
            'English (GB)'            => 'gb',
            'English (CA)'            => 'can',
            'English (AU)'            => 'au',
            'Albanian'                => 'al', 'Albanian (AL)' => 'al',
            'Arabic'                  => 'ae', 'Arabic (001)' => 'ae', 'Arabic (AE)' => 'ae',
            'Arabic (SA)'             => 'sa',
            'Arabic (MA)'             => 'ma',
            'Armenian'                => 'am',
            'Azerbaijani'             => 'az',
            'Belarusian'              => 'by',
            'Bengali'                 => 'bd',
            'Bosnian'                 => 'ba', 'Bosnian (BA)' => 'ba',
            'Bulgarian'               => 'bg', 'Bulgarian (BG)' => 'bg',
            'Burmese'                 => 'mm',
            'Sichuan'                 => 'sichuan',
            'Chinese'                 => 'cn', 'Mandarin' => 'cn', 'Cantonese' => 'cn',
            'Chinese (HK)'            => 'hk', 'Cantonese (HK)' => 'hk', 'yue' => 'hk', '粤语' => 'hk',
            'Chinese (Taiwan)'        => 'tw',
            'Croatian'                => 'hr', 'Croatian (HR)' => 'hr',
            'Czech'                   => 'cz', 'Czech (CZ)' => 'cz',
            'Danish'                  => 'dk', 'Danish (DK)' => 'dk',
            'Dutch'                   => 'nl', 'Dutch (NL)' => 'nl',
            'Dutch (BE)'              => 'be',
            'Estonian'                => 'ee', 'Estonian (EE)' => 'ee',
            'Finnish'                 => 'fi', 'Finnish (FI)' => 'fi',
            'French'                  => 'fr', 'French (FR)' => 'fr',
            'French (CA)'             => 'can-qc',
            'Georgian'                => 'ge',
            'German'                  => 'de', 'German (DE)' => 'de',
            'German (CH)'             => 'ch',
            'Greek'                   => 'gr', 'Greek (GR)' => 'gr',
            'Hebrew'                  => 'il', 'Hebrew (IL)' => 'il',
            'Hindi'                   => 'in', 'Tamil' => 'in', 'Telugu' => 'in',
            'Hungarian'               => 'hu', 'Hungarian (HU)' => 'hu',
            'Icelandic'               => 'is', 'Icelandic (IS)' => 'is',
            'Indonesian'              => 'id', 'Indonesian (ID)' => 'id',
            'Irish'                   => 'ie', 'Irish (IE)' => 'ie',
            'Italian'                 => 'it', 'Italian (IT)' => 'it',
            'Japanese'                => 'jp', '日' => 'jp',
            'Kazakh'                  => 'kz', 'Kazakh (KZ)' => 'kz',
            'Korean'                  => 'kr', '韩' => 'kr',
            'Latvian'                 => 'lv', 'Latvian (LV)' => 'lv',
            'Lithuanian'              => 'lt', 'Lithuanian (LT)' => 'lt',
            'Malay'                   => 'my', 'Malay (MY)' => 'my',
            'Malay (SG)'              => 'sg',
            'Macedonian'              => 'mk', 'Macedonian (MK)' => 'mk',
            'Mongolian'               => 'mn',
            'Norwegian'               => 'no', 'Norwegian Bokmal' => 'no',
            'Persian'                 => 'ir',
            'Polish'                  => 'pl', 'Polish (PL)' => 'pl',
            'Portuguese'              => 'pt', 'Portuguese (PT)' => 'pt',
            'Portuguese (BR)'         => 'br',
            'Romanian'                => 'ro', 'Romanian (RO)' => 'ro',
            'Russian'                 => 'ru', 'Russian (RU)' => 'ru',
            'Serbian'                 => 'rs', 'Serbian (RS)' => 'rs',
            'Sinhala'                 => 'lk',
            'Slovak'                  => 'sk', 'Slovak (SK)' => 'sk',
            'Slovenian'               => 'si', 'Slovenian (SI)' => 'si',
            'Spanish'                 => 'es', 'Spanish (ES)' => 'es',
            'Spanish (AR)'            => 'ar',
            'Spanish (Latin America)' => 'mx', 'Spanish (MX)' => 'mx',
            'Basque'                  => 'es-pv',
            'Catalan'                 => 'es-ct',
            'Galician'                => 'es-ga',
            'Swedish'                 => 'se', 'Swedish (SE)' => 'se',
            'Tagalog'                 => 'ph', 'Filipino' => 'ph',
            'Thai'                    => 'th', 'Thai (TH)' => 'th',
            'Turkish'                 => 'tr', 'Turkish (TR)' => 'tr',
            'Ukrainian'               => 'ua', 'Ukrainian (UA)' => 'ua',
            'Vietnamese'              => 'vn', 'Vietnamese (VN)' => 'vn',
            'Welsh'                   => 'gb-wls',
            // ... 其他映射
        ];

        foreach ($mapping as $language => $code) {
            if (str_contains($line, $language)) {
                return $code;
            }
        }

        return; // 如果没有找到匹配项，返回 null
    }
}
