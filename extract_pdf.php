<?php
$raw = file_get_contents('Guia Laravel-1.pdf');
$offset = 0;
$output = "";

while (($pos = strpos($raw, 'stream', $offset)) !== false) {
    $end = strpos($raw, 'endstream', $pos);
    if ($end === false) break;
    
    $start = $pos + 6;
    if ($raw[$start] === "\r") $start++;
    if ($raw[$start] === "\n") $start++;
    
    $streamData = substr($raw, $start, $end - $start - 1);
    $decoded = @gzuncompress($streamData);
    
    if ($decoded !== false && strlen($decoded) > 50) {
        // Ignorar streams que definen fuentes y metadatos
        if (strpos($decoded, 'FontDescriptor') !== false || 
            strpos($decoded, 'Arial') !== false || 
            strpos($decoded, 'GDEF') !== false) {
            $offset = $end + 9;
            continue;
        }
        
        // Buscar cadenas de texto del tipo (Texto) Tj o (Texto) TJ
        preg_match_all('/(?:\((.*?)\)\s*Tj)|(?:\[(.*?)\]\s*TJ)/s', $decoded, $matches);
        
        $pageText = "";
        foreach ($matches[0] as $match) {
            if (strpos($match, 'TJ') !== false) {
                // Es un array de strings, ej: [(P)-12(a)-10(s)-15(o)] TJ
                preg_match_all('/\((.*?)\)/', $match, $parts);
                foreach ($parts[1] as $part) {
                    $pageText .= $part;
                }
                $pageText .= " ";
            } else {
                // Es un string simple (Texto) Tj
                preg_match('/\((.*?)\)/', $match, $part);
                if (isset($part[1])) {
                    $pageText .= $part[1] . " ";
                }
            }
        }
        
        if (trim($pageText) !== "") {
            // Decodificar caracteres especiales de PDF
            $pageText = str_replace(
                ['\\(', '\\)', '\\\\', '\\n', '\\r', '\\t'], 
                ['(', ')', '\\', "\n", "\r", "\t"], 
                $pageText
            );
            // Reemplazar octales como \363 (ó) o \355 (í)
            $pageText = preg_replace_callback('/\\\\([0-7]{3})/', function($m) {
                return chr(octdec($m[1]));
            }, $pageText);
            
            $output .= $pageText . "\n\n=========================================\n\n";
        }
    }
    $offset = $end + 9;
}

file_put_contents('Guia_Laravel_Texto.txt', $output);
echo "Texto extraido correctamente a Guia_Laravel_Texto.txt\n";
