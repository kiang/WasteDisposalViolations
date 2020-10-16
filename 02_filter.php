<?php
$filterPath = __DIR__ . '/filter';
$fh = fopen(__DIR__ . '/targets.csv', 'w');
fputcsv($fh, array('file', 'case', 'title'));
foreach(glob(__DIR__ . '/extract/*/*') AS $host) {
    foreach(glob($host . '/*.json') AS $jsonFile) {
        if(mb_substr($host, -2, 2, 'utf-8') === '刑事') {
            $pos = strpos($jsonFile, '/extract/') + 9;
            $json = json_decode(file_get_contents($jsonFile));
            if(false !== strpos($json->JTITLE, '違反廢棄物清理法')) {
                fputcsv($fh, array(substr($jsonFile, $pos), $json->JCASE, $json->JTITLE));

                $targetFile = str_replace('/extract/', '/filter/', $jsonFile);
                $p = pathinfo($targetFile);
                if(!file_exists($p['dirname'])) {
                    mkdir($p['dirname'], 0777, true);
                }
                copy($jsonFile, $targetFile);
            }
        }
    }
}