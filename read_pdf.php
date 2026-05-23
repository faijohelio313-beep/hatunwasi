<?php
$raw = file_get_contents('Guia Laravel-1.pdf');

// Find all compressed streams and try to decode them
$streams = [];
$offset = 0;
while (($pos = strpos($raw, 'stream', $offset)) !== false) {
    $end = strpos($raw, 'endstream', $pos);
    if ($end === false) break;
    
    // Skip the newline after 'stream'
    $start = $pos + 6;
    if ($raw[$start] === "\r") $start++;
    if ($raw[$start] === "\n") $start++;
    
    $streamData = substr($raw, $start, $end - $start - 1);
    
    // Try zlib decompress
    $decoded = @gzuncompress($streamData);
    if ($decoded !== false && strlen($decoded) > 100) {
        // Extract text - look for text between () in PDF syntax
        preg_match_all('/\(([^\)\\\\]|\\\\[^\)])*\)/', $decoded, $m);
        $text = '';
        foreach ($m[0] as $match) {
            $inner = substr($match, 1, -1);
            $inner = str_replace(['\\n', '\\r', '\\t'], ["\n", "\r", "\t"], $inner);
            $inner = preg_replace('/\\\\(.)/', '$1', $inner);
            // Only printable ASCII
            $printable = preg_replace('/[^\x20-\x7E\xC0-\xFF]/', '', $inner);
            if (strlen(trim($printable)) > 2) {
                $text .= $printable . ' ';
            }
        }
        if (strlen(trim($text)) > 50) {
            echo $text . "\n---\n";
        }
    }
    $offset = $end + 9;
}
