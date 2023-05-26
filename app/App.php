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
        $transactions[] = extractTransaction($transaction);
    }

    return $transactions;
}



function extractTransaction(array $transactionRow): array
{
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount,
    ];
}


function calculateTotals(array $transactions): array
{

    $totals = [
        'netTotal' => 0,
        'totalIncomes' => 0,
        'totalExpenses' => 0,
    ];

    foreach ($transactions as $transaction) {
        # code...
        $totals['netTotal'] += $transaction['amount'];
        if ($transaction['amount'] < 0) {
            $totals['totalExpenses'] +=  $transaction['amount'];
        } else {
            $totals['totalIncomes'] +=  $transaction['amount'];
        }
    }
    return $totals;
}


function formatDollarAmount(float $amount): string
{
    $isNegative = $amount < 0;
    return ($isNegative ? '-' : '') . '  $' . number_format(abs($amount), decimals: 2);
}
