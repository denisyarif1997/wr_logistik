<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait Exportable
{
    /**
     * Download data sebagai file CSV Excel-compatible (UTF-8 BOM).
     *
     * @param string $filename Nama dasar file
     * @param array $headers Judul kolom header
     * @param iterable|array $rows Baris data (array of arrays atau array of strings)
     * @return StreamedResponse
     */
    public function downloadCsv(string $filename, array $headers, $rows): StreamedResponse
    {
        $sanitizedFilename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename) . '_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Tulis UTF-8 BOM agar Excel mengenali UTF-8 dan pemisah kolom
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tulis Header
            fputcsv($handle, $headers, ',');

            // Tulis Setiap Baris Data
            foreach ($rows as $row) {
                $cleanRow = array_map(function ($val) {
                    if (is_null($val)) return '';
                    if (is_bool($val)) return $val ? 'Ya' : 'Tidak';
                    return (string) $val;
                }, (array) $row);

                fputcsv($handle, $cleanRow, ',');
            }

            fclose($handle);
        }, $sanitizedFilename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $sanitizedFilename . '"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

