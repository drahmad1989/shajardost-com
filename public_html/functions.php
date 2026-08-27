<?php
function getDataFromFile($filename) {
    $filepath = __DIR__ . '/../data/' . $filename;
    if (file_exists($filepath)) {
        $json_data = file_get_contents($filepath);
        return json_decode($json_data, true);
    }
    return [];
}
?>