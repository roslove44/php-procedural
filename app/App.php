<?php

declare(strict_types=1);

// Your Code
function getTransactionFiles(string $dirPath): array
{
    $files = [];
    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        }

        $files[] = $dirPath . $file;

        return $files;
    }
}


function getTransactions(string $fileName): array
{
    if (!file_exists($fileName)) {
        trigger_error("File $fileName does not exist" . E_USER_ERROR);
    }

    $file = fopen(filename: $fileName, mode: 'r');

    fgetcsv($file); // met la première ligne à part

    $transactions = [];

    while (($transaction = fgetcsv(stream: $file)) !== false) {
        $transactions[] = $transaction;
    }

    return $transactions;
}
