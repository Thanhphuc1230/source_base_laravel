<?php

namespace App\Services;

use Illuminate\Support\Str;

class ContentTocService
{
    /**
     * Extract h2/h3 headings into a Table of Contents, inject anchor IDs, and estimate reading time.
     *
     * @return array{html: string, toc: array<int, array{level: int, id: string, text: string}>, reading_time: int}
     */
    public function process(?string $html): array
    {
        $html = (string) ($html ?? '');
        $plainText = trim(preg_replace('/\s+/u', ' ', strip_tags($html)) ?? '');
        $wordCount = $plainText === '' ? 0 : count(preg_split('/\s+/u', $plainText, -1, PREG_SPLIT_NO_EMPTY));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        if (trim($html) === '') {
            return [
                'html' => '',
                'toc' => [],
                'reading_time' => 1,
            ];
        }

        $previousUseErrors = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<div id="__toc_root__">' . $html . '</div>';
        $encodedHtml = mb_encode_numericentity($wrappedHtml, [0x80, 0x10FFFF, 0, ~0], 'UTF-8');
        $dom->loadHTML($encodedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previousUseErrors);

        $xpath = new \DOMXPath($dom);
        $headings = $xpath->query('//h2 | //h3');

        $toc = [];
        $usedIds = [];

        if ($headings !== false) {
            foreach ($headings as $heading) {
                if (! $heading instanceof \DOMElement) {
                    continue;
                }

                $text = trim(preg_replace('/\s+/u', ' ', $heading->textContent) ?? '');
                if ($text === '') {
                    continue;
                }

                $existingId = trim($heading->getAttribute('id'));
                $baseId = $existingId !== '' ? $existingId : Str::slug($text);
                if ($baseId === '') {
                    $baseId = 'section';
                }

                $id = $baseId;
                $suffix = 1;
                while (isset($usedIds[$id])) {
                    $id = $baseId . '-' . $suffix;
                    $suffix++;
                }
                $usedIds[$id] = true;

                $heading->setAttribute('id', $id);
                $toc[] = [
                    'level' => strtolower($heading->tagName) === 'h3' ? 3 : 2,
                    'id' => $id,
                    'text' => $text,
                ];
            }
        }

        $root = $dom->getElementById('__toc_root__');
        $outputHtml = '';
        if ($root) {
            foreach ($root->childNodes as $child) {
                $outputHtml .= $dom->saveHTML($child);
            }
        } else {
            $outputHtml = $html;
        }

        return [
            'html' => $outputHtml,
            'toc' => $toc,
            'reading_time' => $readingTime,
        ];
    }
}
