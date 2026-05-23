<?php
$pdfPath = 'D:\\combinaciones proyecto\\combos_LP1-1.pdf';
$raw = file_get_contents($pdfPath);
$offset = 0;
$count = 0;
$matchingCount = 0;

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
        $count++;
        if (stripos($decoded, 'AMBIENTE') !== false) {
            $matchingCount++;
        }
    }
    $offset = $end + 9;
}
echo "Total text streams: $count, Streams containing 'AMBIENTE': $matchingCount\n";
