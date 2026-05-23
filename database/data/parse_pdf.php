<?php

$pdfPath = 'D:\\combinaciones proyecto\\combos_LP1-1.pdf';
if (!file_exists($pdfPath)) {
    die("PDF file not found at $pdfPath\n");
}

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
        if (strpos($decoded, 'FontDescriptor') !== false || 
            strpos($decoded, 'Arial') !== false || 
            strpos($decoded, 'GDEF') !== false) {
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
            $pageText = str_replace(
                ['\\(', '\\)', '\\\\', '\\n', '\\r', '\\t'], 
                ['(', ')', '\\', "\n", "\r", "\t"], 
                $pageText
            );
            $pageText = preg_replace_callback('/\\\\([0-7]{3})/', function($m) {
                return chr(octdec($m[1]));
            }, $pageText);
            
            $pages[] = $pageText;
        }
    }
    $offset = $end + 9;
}

// Ahora procesamos cada página extraída
$ambientes = [];
$ambienteNum = 1;

foreach ($pages as $text) {
    // Si no contiene la palabra AMBIENTE, la ignoramos o buscamos el patrón
    if (stripos($text, 'AMBIENTE') === false) {
        continue;
    }
    
    // Obtener título de la página (ej: AMBIENTE 01: Serie Zen Geométrica)
    preg_match('/(AMBIENTE\s*\d+[\s:]+.*?)(?=(?:PARED|PISO|DETALLES|SANITARIO|MOBILIARIO|$))/i', $text, $titleMatch);
    $fullTitle = isset($titleMatch[1]) ? trim($titleMatch[1]) : "AMBIENTE " . str_pad($ambienteNum, 2, '0', STR_PAD_LEFT);
    
    // Extraer número y nombre de la serie
    $parts = explode(':', $fullTitle, 2);
    $nombreAmbiente = trim($parts[0]);
    $nombreSerie = isset($parts[1]) ? trim($parts[1]) : "";
    
    // Dividir en secciones usando las palabras clave
    $sections = preg_split('/(PARED|PISO|DETALLES DECORATIVOS|SANITARIO|MOBILIARIO)/i', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    $parsedSections = [];
    $currentSectionName = '';
    
    for ($i = 1; $i < count($sections); $i += 2) {
        $sectionName = strtoupper(trim($sections[$i]));
        $sectionContent = trim($sections[$i+1]);
        $parsedSections[$sectionName] = $sectionContent;
    }
    
    $items = [];
    
    // Función auxiliar para parsear viñetas de una sección
    $parseList = function($content, $tipoUso) use (&$items) {
        // Normalizar viñetas (pueden ser \x95, , o simplemente guiones)
        $lines = preg_split('/[\x95\-\r\n\t]+/', $content);
        $currentProduct = [
            'tipo_uso' => $tipoUso,
            'tipo_producto' => null,
            'marca' => null,
            'diseño' => null,
            'formato' => null,
            'codigo' => null,
            'color' => null,
            'modelo' => null
        ];
        
        $hasData = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Buscar patrones comunes
            if (preg_match('/^Tipo\s*:\s*(.*?)(?:\s*\(Marca\s*(.*?)\))?$/i', $line, $m)) {
                $currentProduct['tipo_producto'] = trim($m[1]);
                if (isset($m[2])) $currentProduct['marca'] = trim($m[2]);
                $hasData = true;
            } elseif (preg_match('/^Diseño\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['diseño'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Formato\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['formato'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Código\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['codigo'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Nombre\/Modelo\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['modelo'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Modelo\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['modelo'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Marca\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['marca'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Color\s*:\s*(.*)$/i', $line, $m)) {
                $currentProduct['color'] = trim($m[1]);
                $hasData = true;
            } elseif (preg_match('/^Inserto\s*:\s*(.*)$/i', $line, $m)) {
                // Es un inserto
                $inserto = [
                    'tipo_uso' => 'detalle_decorativo',
                    'tipo_producto' => 'Inserto',
                    'marca' => null,
                    'diseño' => trim($m[1]),
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => null
                ];
                // Si la misma línea tiene formato
                if (preg_match('/formato\s*(\d+\s*x\s*\d+)/i', $m[1], $fm)) {
                    $inserto['formato'] = $fm[1] . ' cm';
                }
                $items[] = $inserto;
            } elseif (preg_match('/^Lápiz\s*:\s*(.*)$/i', $line, $m)) {
                // Es un lápiz
                $lapiz = [
                    'tipo_uso' => 'detalle_decorativo',
                    'tipo_producto' => 'Lápiz',
                    'marca' => null,
                    'diseño' => trim($m[1]),
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => null
                ];
                if (preg_match('/formato\s*(\d+\s*x\s*\d+)/i', $m[1], $fm)) {
                    $lapiz['formato'] = $fm[1] . ' cm';
                }
                $items[] = $lapiz;
            } elseif (preg_match('/^Formato Lápiz\s*:\s*(.*)$/i', $line, $m)) {
                // Actualizar formato del último lápiz agregado
                for ($k = count($items) - 1; $k >= 0; $k--) {
                    if ($items[$k]['tipo_producto'] === 'Lápiz') {
                        $items[$k]['formato'] = trim($m[1]);
                        break;
                    }
                }
            } elseif (preg_match('/^Código Lápiz\s*:\s*(.*)$/i', $line, $m)) {
                for ($k = count($items) - 1; $k >= 0; $k--) {
                    if ($items[$k]['tipo_producto'] === 'Lápiz') {
                        $items[$k]['codigo'] = trim($m[1]);
                        break;
                    }
                }
            } elseif (preg_match('/^Nombre Lápiz\s*:\s*(.*)$/i', $line, $m)) {
                for ($k = count($items) - 1; $k >= 0; $k--) {
                    if ($items[$k]['tipo_producto'] === 'Lápiz') {
                        $items[$k]['modelo'] = trim($m[1]);
                        break;
                    }
                }
            } elseif (preg_match('/^Inodoro\s*:\s*(.*)$/i', $line, $m)) {
                $inodoro = [
                    'tipo_uso' => 'sanitario',
                    'tipo_producto' => 'Inodoro',
                    'marca' => null,
                    'diseño' => null,
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => null
                ];
                // Intentar separar marca y color
                if (preg_match('/Marca\s+([A-Za-z]+)/i', $m[1], $bm)) {
                    $inodoro['marca'] = $bm[1];
                }
                if (preg_match('/color\s+([A-Za-z\s()]+)/i', $m[1], $cm)) {
                    $inodoro['color'] = trim($cm[1]);
                }
                $items[] = $inodoro;
            } elseif (preg_match('/^Urinario\s*:\s*(.*)$/i', $line, $m)) {
                $urinario = [
                    'tipo_uso' => 'sanitario',
                    'tipo_producto' => 'Urinario',
                    'marca' => null,
                    'diseño' => null,
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => null
                ];
                if (preg_match('/Marca\s+([A-Za-z]+)/i', $m[1], $bm)) {
                    $urinario['marca'] = $bm[1];
                }
                if (preg_match('/color\s+([A-Za-z\s()]+)/i', $m[1], $cm)) {
                    $urinario['color'] = trim($cm[1]);
                }
                $items[] = $urinario;
            } elseif (preg_match('/^Juego de Baño\s*:\s*(.*)$/i', $line, $m)) {
                $juego = [
                    'tipo_uso' => 'sanitario',
                    'tipo_producto' => 'Juego de Baño',
                    'marca' => null,
                    'diseño' => null,
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => null
                ];
                if (preg_match('/Marca\s+([A-Za-z]+)/i', $m[1], $bm)) {
                    $juego['marca'] = $bm[1];
                }
                if (preg_match('/modelo\s+([A-Za-z0-9\s]+)/i', $m[1], $mm)) {
                    $juego['modelo'] = trim($mm[1]);
                }
                $items[] = $juego;
            } elseif (preg_match('/^Lavatorio\s*:\s*(.*)$/i', $line, $m)) {
                $items[] = [
                    'tipo_uso' => 'sanitario',
                    'tipo_producto' => 'Lavatorio',
                    'marca' => null,
                    'diseño' => null,
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => trim($m[1])
                ];
            } elseif (preg_match('/^Repostero\s*:\s*(.*)$/i', $line, $m)) {
                $items[] = [
                    'tipo_uso' => 'mobiliario',
                    'tipo_producto' => 'Repostero',
                    'marca' => null,
                    'diseño' => null,
                    'formato' => null,
                    'codigo' => null,
                    'color' => null,
                    'modelo' => trim($m[1])
                ];
            } else {
                // Línea genérica dentro de una viñeta, intentar asignarla al producto actual si tiene un separador de dos puntos
                if (strpos($line, ':') !== false) {
                    list($key, $val) = explode(':', $line, 2);
                    $key = strtolower(trim($key));
                    $val = trim($val);
                    if ($key == 'diseño') $currentProduct['diseño'] = $val;
                    elseif ($key == 'formato') $currentProduct['formato'] = $val;
                    elseif ($key == 'código' || $key == 'codigo') $currentProduct['codigo'] = $val;
                    elseif ($key == 'marca') $currentProduct['marca'] = $val;
                    elseif ($key == 'color') $currentProduct['color'] = $val;
                    elseif ($key == 'modelo') $currentProduct['modelo'] = $val;
                }
            }
        }
        
        if ($hasData && ($currentProduct['tipo_producto'] || $currentProduct['codigo'])) {
            $items[] = $currentProduct;
        }
    };
    
    // Procesar secciones específicas de la página
    foreach ($parsedSections as $secName => $secContent) {
        if (strpos($secName, 'PARED') !== false) {
            $parseList($secContent, 'pared');
        } elseif (strpos($secName, 'PISO') !== false) {
            $parseList($secContent, 'piso');
        } elseif (strpos($secName, 'DETALLES') !== false) {
            $parseList($secContent, 'detalle_decorativo');
        } elseif (strpos($secName, 'SANITARIO') !== false) {
            $parseList($secContent, 'sanitario');
        } elseif (strpos($secName, 'MOBILIARIO') !== false) {
            $parseList($secContent, 'mobiliario');
        }
    }
    
    // Clasificar ambiente en Baño o Cocina
    $categoria = 'baño';
    if ($ambienteNum >= 26) {
        $categoria = 'cocina';
    }
    
    $ambientes[] = [
        'id' => $ambienteNum,
        'nombre_ambiente' => $nombreAmbiente,
        'nombre_serie' => $nombreSerie,
        'categoria' => $categoria,
        'items' => $items
    ];
    
    $ambienteNum++;
}

// Guardar los datos procesados en la base de datos de datos
$dataDir = __DIR__;
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$outputPath = $dataDir . '/ambientes.json';
file_put_contents($outputPath, json_encode($ambientes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Parsed " . count($ambientes) . " environments successfully and saved to $outputPath\n";
