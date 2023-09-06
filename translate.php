<?php

$inputFilePath = './translations/files/file.properties';
$outputFilePath = './translations/sorted/sorted_file.properties';

$lines = file($inputFilePath);

if ($lines === false) {
    die("Failed to read file");
}

// Separate translations and comments

$translations = [];
$currentComment = '';

foreach ($lines as $key => $line) {
    $trimmedLine = trim($line);

    if (strpos($trimmedLine, '#') === 0) {
        $currentComment .= $line;
    } else {
        // Break line for last line of input file because it doesnt by default and therefore gives a messy result
        if ($key === count($lines) - 1 ) {
            $translations[] = ['translation' => $line . PHP_EOL, 'comment' => $currentComment];
        } else {
            $translations[] = ['translation' => $line , 'comment' => $currentComment];
        }
        // Reset the comment
        $currentComment = '';
    }
}

usort($translations, function ($a, $b) {
    return strcmp($a['translation'], $b['translation']);
});

$outputFileContent = '';

foreach ($translations as $line) {
    if (!empty($line['comment'])) {
        $outputFileContent .= $line['comment'];
    }
    $outputFileContent .= $line['translation'];
}

if (file_put_contents($outputFilePath, $outputFileContent) !== false) {
    echo "Translations sorted";
} else {
    "Failed to write the file";
}
