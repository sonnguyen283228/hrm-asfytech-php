<?php
// Usage: php scripts/scan_suspicious_requests.php /path/to/access.log 200

$logPath = $argv[1] ?? '';
$limit = (int)($argv[2] ?? 200);
if ($logPath === '' || !is_file($logPath)) {
    fwrite(STDERR, "Usage: php scripts/scan_suspicious_requests.php /path/to/access.log [limit]
");
    exit(1);
}

$patterns = [
    '/\.env/i', '/wp-admin/i', '/xmlrpc\.php/i', '/phpmyadmin/i', '/\/vendor\//i', '/\/\.git/i',
    '/union\s+select/i', '/select.+from/i', '/sleep\(/i', '/benchmark\(/i', '/base64_/i', '/<script/i',
    '/server-status/i', '/etc\/passwd/i', '/cmd=/i', '/\.sql/i', '/\.bak/i'
];

$ips = [];
$fh = fopen($logPath, 'r');
if (!$fh) exit(1);

while (($line = fgets($fh)) !== false) {
    if (!preg_match('/^(\S+) .*?"(GET|POST|PUT|DELETE|HEAD|OPTIONS) (.*?) HTTP\/.*" (\d{3}) /', $line, $m)) continue;
    $ip = $m[1];
    $method = $m[2];
    $path = $m[3];
    $status = (int)$m[4];

    $score = 0;
    foreach ($patterns as $p) if (preg_match($p, $path)) $score += 3;
    if ($status >= 400) $score += 1;
    if (strlen($path) > 120) $score += 1;

    if ($score > 0) {
        if (!isset($ips[$ip])) $ips[$ip] = ['score' => 0, 'hits' => 0, 'samples' => []];
        $ips[$ip]['score'] += $score;
        $ips[$ip]['hits'] += 1;
        if (count($ips[$ip]['samples']) < 5) $ips[$ip]['samples'][] = "{$method} {$path} [{$status}]";
    }
}
fclose($fh);

uasort($ips, fn($a,$b) => $b['score'] <=> $a['score']);
$ips = array_slice($ips, 0, $limit, true);

echo "IP,Score,Hits,Samples
";
foreach ($ips as $ip => $d) {
    $samples = str_replace('"', '\"', implode(' | ', $d['samples']));
    echo "{$ip},{$d['score']},{$d['hits']},"{$samples}"
";
}
