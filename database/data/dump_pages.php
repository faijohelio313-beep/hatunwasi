<?php
$pdfPath = 'D:\\combinaciones proyecto\\combos_LP1-1.pdf';
$raw = file_get_contents($pdfPath);
$offset = 0;
$pages = [];

while (($pos = strpos($raw, 'stream', $offset)) !== false) {
    $end = strpos($raw, 'endstream', $pos);
    if ($end === false) break;
    $start = $pos + 6;
    if ($raw[$start] === "\r") $start++;
    if ($raw[$start] === "\n") $start++;
    $streamData = substr($raw, $start, $end - $start - 1);
    $decoded = @gzuncompress($streamData);
    if ($decoded !== false && strlen($decoded) > 50) {
        if (strpos($decoded, 'FontDescriptor') !== false || strpos($decoded, 'Arial') !== false || strpos($decoded, 'GDEF') !== false) {
            $offset = $end + 9;
            continue;
        }
        preg_match_all('/(?:\((.*?)\)\s*Tj)|(?:\[(.*?)\]\s*TJ)/s', $decoded, $matches);
        $pageText = "";
        foreach ($matches[0] as $match) {
            if (strpos($match, 'TJ') !== false) {
                preg_match_all('/\((.*?)\)/', $match, $parts);
                foreach ($parts[1] as $part) {
                    $pageText .= $part;
                }
                $pageText .= " ";
            } else {
                preg_match('/\((.*?)\)/', $match, $part);
                if (isset($part[1])) {
                    $pageText .= $part[1] . " ";
                }
            }
        }
        if (trim($pageText) !== "") {
            $pageText = str_replace(['\\(', '\\)', '\\\\', '\\n', '\\r', '\\t'], ['(', ')', '\\', "\n", "\r", "\t"], $pageText);
            $pageText = preg_replace_callback('/\\\\([0-7]{3})/', function($m) { return chr(octdec($m[1])); }, $pageText);
            $pages[] = $pageText;
        }
    }
    $offset = $end + 9;
}

echo "Extracted " . count($pages) . " pages.\n";
foreach ($pages as $i => $page) {
    echo "--- Page $i ---\n";
    echo substr($page, 0, 200) . "\n...\n";
}
