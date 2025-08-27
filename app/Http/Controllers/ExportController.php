<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Finding;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends Controller
{
    private $maxDataSize = 1073741824; // 1GB

    public function exportPDF(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin) {
            ini_set('memory_limit', '2048M');
            ini_set('max_execution_time', 600);
        }

        $findings = Finding::with('closing');

        if ($request->has('week') && $request->week) {
            $findings->where('week', $request->week);
        }
        if ($request->has('department') && $request->department) {
            $findings->where('department', $request->department);
        }
        if ($request->has('jenis_audit') && $request->jenis_audit) {
            $findings->where('jenis_audit', $request->jenis_audit);
        }
        if ($request->has('year') && $request->year) {
            $findings->where('year', $request->year);
        }

        $findings = $findings->get();

        if (!$isAdmin) {
            $dataSize = $this->calculateDataSize($findings);
            if ($dataSize > $this->maxDataSize) {
                return response()->json([
                    'error' => true,
                    'message' => 'File terlalu besar! Coba export menggunakan filter yang lebih spesifik.'
                ], 400);
            }
        }

        // Siapkan base64 thumbnail untuk foto hanya untuk non-admin
        if (!$isAdmin) {
            foreach ($findings as $finding) {
                $finding->image_base64 = $this->generatePdfImageBase64($finding->image);
                $finding->image2_base64 = $this->generatePdfImageBase64($finding->image2);
            }
        }

        // Kirimkan filter untuk membentuk judul laporan
        $filters = [
            'week' => $request->input('week'),
            'year' => $request->input('year'),
            'department' => $request->input('department'),
            'jenis_audit' => $request->input('jenis_audit'),
        ];

        // Buat judul yang menyesuaikan filter
        $title = 'LAPORAN ' . ($filters['jenis_audit'] ?: 'ALL');
        if ($filters['week']) $title .= ' - WEEK ' . $filters['week'];
        if ($filters['year']) $title .= ' - YEAR ' . $filters['year'];
        if ($filters['department']) $title .= ' - DEPT ' . $filters['department'];

        $html = view('findings.export_pdf', compact('findings', 'isAdmin', 'filters', 'title'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('chroot', realpath(base_path()));
        $options->set('tempDir', storage_path('app/temp'));
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // Ubah orientasi ke landscape untuk non-admin
        $dompdf->setPaper('A4', $isAdmin ? 'portrait' : 'landscape');
        $dompdf->render();

        $filename = $this->generateFilename($request, 'pdf');
        
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        return $dompdf->stream($filename, ['Attachment' => true]);
    }

    public function exportFindings(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin) {
            ini_set('memory_limit', '2048M');
            ini_set('max_execution_time', 600);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $week = $request->input('week');
        $department = $request->input('department');
        $year = $request->input('year');
        $jenis_audit = $request->input('jenis_audit');

        $query = Finding::with('closing');
        if ($week) $query->where('week', $week);
        if ($department) $query->where('department', $department);
        if ($jenis_audit) $query->where('jenis_audit', $jenis_audit);
        if ($year) $query->where('year', $year);

        $findings = $query->get();

        if (!$isAdmin) {
            $dataSize = $this->calculateDataSize($findings);
            if ($dataSize > $this->maxDataSize) {
                return response()->json([
                    'error' => true,
                    'message' => 'File terlalu besar! Coba export menggunakan filter yang lebih spesifik.'
                ], 400);
            }
        }

        $auditType = $jenis_audit ?: 'ALL';
        $lastCol = $isAdmin ? 'I' : 'K';
        $sheet->setTitle('LAPORAN_' . $auditType);

        // Buat judul yang menyesuaikan filter
        $title = 'LAPORAN ' . $auditType;
        if ($week) $title .= ' - WEEK ' . $week;
        if ($year) $title .= ' - YEAR ' . $year;
        if ($department) $title .= ' - DEPT ' . $department;

        // Judul dokumen
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');

        // Nama tabel
        $sheet->setCellValue('A2', 'Tabel Data Temuan');
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E8F5E8');

        // Header tabel data
        $headerRow = 3;
        if ($isAdmin) {
            $sheet->fromArray([
                ['No', 'Departemen', 'Auditor', 'Jenis Audit', 'Kriteria', 'Deskripsi', 'Week', 'Status', 'Catatan Penyelesaian']
            ], null, 'A' . $headerRow);
        } else {
            $sheet->fromArray([
                ['No', 'Foto Before', 'Foto After', 'Departemen', 'Auditor', 'Jenis Audit', 'Kriteria', 'Deskripsi', 'Week', 'Status', 'Catatan Penyelesaian']
            ], null, 'A' . $headerRow);
            // Lebar kolom gambar agar tidak terpotong
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(25);
        }

        // Styling header
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = $headerRow + 1; // mulai data
        foreach ($findings as $index => $finding) {
            $sheet->setCellValue("A{$row}", $index + 1);

            // Tinggi baris hanya diperlukan jika non-admin (ada gambar)
            if (!$isAdmin) {
                $sheet->getRowDimension($row)->setRowHeight(100);
            }

            if ($isAdmin) {
                $sheet->setCellValue("B{$row}", $finding->department);
                $sheet->setCellValue("C{$row}", $finding->auditor);
                $sheet->setCellValue("D{$row}", $finding->jenis_audit);
                $sheet->setCellValue("E{$row}", $finding->kriteria);
                $sheet->setCellValue("F{$row}", $finding->description);
                $sheet->setCellValue("G{$row}", $finding->week);
                $sheet->setCellValue("H{$row}", $finding->status);
                $sheet->setCellValue("I{$row}", $finding->closing ? $finding->closing->catatan_penyelesaian : '');
            } else {
                // Thumbnail agar file Excel ringan dan gambar tidak terpotong
                $this->addImageToExcel($sheet, $finding->image, "B{$row}", 'Before');
                $this->addImageToExcel($sheet, $finding->image2, "C{$row}", 'After');

                $sheet->setCellValue("D{$row}", $finding->department);
                $sheet->setCellValue("E{$row}", $finding->auditor);
                $sheet->setCellValue("F{$row}", $finding->jenis_audit);
                $sheet->setCellValue("G{$row}", $finding->kriteria);
                $sheet->setCellValue("H{$row}", $finding->description);
                $sheet->setCellValue("I{$row}", $finding->week);
                $sheet->setCellValue("J{$row}", $finding->status);
                $sheet->setCellValue("K{$row}", $finding->closing ? $finding->closing->catatan_penyelesaian : '');
            }
            $row++;
        }

        $lastDataRow = $row - 1;

        // Tabel ringkasan
        $this->addCriteriaSummaryTable($sheet, $findings, $isAdmin, $jenis_audit);
        $this->addStatusSummaryTable($sheet, $findings, $isAdmin);

        // Styling data utama saja (mulai dari header)
        $this->applyStyling($sheet, $isAdmin, $lastDataRow, $headerRow);

        $filename = $this->generateFilename($request, 'xlsx');
        
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set proper headers for Excel download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function addImageToExcel($sheet, $relativePath, $cell, $altText)
    {
        if (!$relativePath || !file_exists(public_path('storage/' . $relativePath))) {
            $sheet->setCellValue($cell, 'Tidak ada foto');
            return;
        }

        try {
            $fullPath = public_path('storage/' . $relativePath);
            $drawing = new Drawing();
            $drawing->setName($altText);
            $drawing->setDescription($altText);
            $drawing->setPath($fullPath);
            $drawing->setCoordinates($cell);
            $drawing->setWidth(80);
            $drawing->setHeight(60);
            $drawing->setResizeProportional(true);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        } catch (Exception $e) {
            $sheet->setCellValue($cell, 'Error loading image');
        }
    }

    private function generateFilename(Request $request, $extension)
    {
        $isAdmin = auth()->user()->role === 'admin';
        $filename = 'LAPORAN';
        if ($request->has('jenis_audit') && $request->jenis_audit) $filename .= '_' . $request->jenis_audit; else $filename .= '_ALL';
        if ($request->has('week') && $request->week) $filename .= '_WEEK_' . $request->week;
        if ($request->has('department') && $request->department) $filename .= '_DEPT_' . $request->department;
        if ($request->has('year') && $request->year) $filename .= '_YEAR_' . $request->year;
        if ($isAdmin) $filename .= '_ADMIN';
        return $filename . '.' . $extension;
    }

    private function addCriteriaSummaryTable($sheet, $findings, $isAdmin, $jenis_audit)
    {
        $departments = ['Produksi', 'Warehouse', 'Engineering', 'HR', 'QA', 'AG'];
        if ($jenis_audit === 'GMP') $criteriaList = ['Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning'];
        elseif ($jenis_audit === 'K3') $criteriaList = ['Kondisi - Tidak Aman', 'Prilaku - Tidak Aman'];
        elseif ($jenis_audit === '5R') $criteriaList = ['Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'];
        else $criteriaList = ['Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning', 'Kondisi - Tidak Aman', 'Prilaku - Tidak Aman', 'Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'];

        $counts = [];
        foreach ($criteriaList as $criteria) {
            $counts[$criteria] = [];
            foreach ($departments as $dept) {
                $counts[$criteria][$dept] = $findings->where('kriteria', $criteria)->where('department', $dept)->count();
            }
            $counts[$criteria]['total'] = $findings->where('kriteria', $criteria)->count();
        }

        $criteriaTableStartRow = 1;
        $criteriaTableStartCol = $isAdmin ? 'K' : 'M';
        $sheet->setCellValue("{$criteriaTableStartCol}{$criteriaTableStartRow}", 'Ringkasan Kriteria ' . ($jenis_audit ?: 'ALL'));
        $sheet->mergeCells("{$criteriaTableStartCol}{$criteriaTableStartRow}:" . chr(ord($criteriaTableStartCol) + count($departments) + 1) . $criteriaTableStartRow);
        $sheet->getStyle("{$criteriaTableStartCol}{$criteriaTableStartRow}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("{$criteriaTableStartCol}{$criteriaTableStartRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle("{$criteriaTableStartCol}{$criteriaTableStartRow}")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("{$criteriaTableStartCol}{$criteriaTableStartRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue(chr(ord($criteriaTableStartCol) + 1) . $criteriaTableStartRow, 'Jumlah Finding');

        $colLetter = chr(ord($criteriaTableStartCol) + 2);
        foreach ($departments as $dept) {
            $sheet->setCellValue("{$colLetter}{$criteriaTableStartRow}", $dept);
            $colLetter++;
        }

        $currentRow = $criteriaTableStartRow + 1;
        foreach ($criteriaList as $criteria) {
            $sheet->setCellValue("{$criteriaTableStartCol}{$currentRow}", $criteria);
            $sheet->setCellValue(chr(ord($criteriaTableStartCol) + 1) . $currentRow, $counts[$criteria]['total']);
            $colLetter = chr(ord($criteriaTableStartCol) + 2);
            foreach ($departments as $dept) {
                $sheet->setCellValue("{$colLetter}{$currentRow}", $counts[$criteria][$dept]);
                $colLetter++;
            }
            $currentRow++;
        }

        $sheet->setCellValue("{$criteriaTableStartCol}{$currentRow}", 'Total');
        $sheet->getStyle("{$criteriaTableStartCol}{$currentRow}")->getFont()->setBold(true);
        $grandTotal = 0; $colLetter = chr(ord($criteriaTableStartCol) + 2);
        foreach ($departments as $dept) {
            $t = $findings->where('department', $dept)->count();
            $sheet->setCellValue("{$colLetter}{$currentRow}", $t);
            $grandTotal += $t; $colLetter++;
        }
        $sheet->setCellValue(chr(ord($criteriaTableStartCol) + 1) . $currentRow, $grandTotal);
        $sheet->getStyle(chr(ord($criteriaTableStartCol) + 1) . $currentRow)->getFont()->setBold(true);
        return $currentRow;
    }

    private function addStatusSummaryTable($sheet, $findings, $isAdmin)
    {
        $departments = ['Produksi', 'Warehouse', 'Engineering', 'HR', 'QA', 'AG'];
        $statusTableStartRow = $this->getLastRow($sheet) + 3;
        $statusTableStartCol = $isAdmin ? 'K' : 'M';
        $sheet->setCellValue("{$statusTableStartCol}{$statusTableStartRow}", 'Ringkasan Status per Departemen');
        $sheet->mergeCells("{$statusTableStartCol}{$statusTableStartRow}:" . chr(ord($statusTableStartCol) + 3) . $statusTableStartRow);
        $sheet->getStyle("{$statusTableStartCol}{$statusTableStartRow}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("{$statusTableStartCol}{$statusTableStartRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2196F3');
        $sheet->getStyle("{$statusTableStartCol}{$statusTableStartRow}")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("{$statusTableStartCol}{$statusTableStartRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue(chr(ord($statusTableStartCol) + 1) . $statusTableStartRow, 'Jumlah Finding');
        $sheet->setCellValue(chr(ord($statusTableStartCol) + 2) . $statusTableStartRow, 'Close');
        $sheet->setCellValue(chr(ord($statusTableStartCol) + 3) . $statusTableStartRow, 'Open');

        $row = $statusTableStartRow + 1;
        foreach ($departments as $dept) {
            $total = $findings->where('department', $dept)->count();
            $close = $findings->where('department', $dept)->where('status', 'Close')->count();
            $open = $findings->where('department', $dept)->where('status', 'Open')->count();
            $sheet->setCellValue("{$statusTableStartCol}{$row}", $dept);
            $sheet->setCellValue(chr(ord($statusTableStartCol) + 1) . $row, $total);
            $sheet->setCellValue(chr(ord($statusTableStartCol) + 2) . $row, $close);
            $sheet->setCellValue(chr(ord($statusTableStartCol) + 3) . $row, $open);
            $row++;
        }
        $sheet->setCellValue("{$statusTableStartCol}{$row}", 'Total');
        $sheet->getStyle("{$statusTableStartCol}{$row}")->getFont()->setBold(true);
        $sheet->setCellValue(chr(ord($statusTableStartCol) + 1) . $row, $findings->count());
        $sheet->setCellValue(chr(ord($statusTableStartCol) + 2) . $row, $findings->where('status', 'Close')->count());
        $sheet->setCellValue(chr(ord($statusTableStartCol) + 3) . $row, $findings->where('status', 'Open')->count());
        $sheet->getStyle(chr(ord($statusTableStartCol) + 1) . $row)->getFont()->setBold(true);
        $sheet->getStyle(chr(ord($statusTableStartCol) + 2) . $row)->getFont()->setBold(true);
        $sheet->getStyle(chr(ord($statusTableStartCol) + 3) . $row)->getFont()->setBold(true);
        return $row;
    }

    private function getLastRow($sheet)
    {
        return $sheet->getHighestRow();
    }

    private function applyStyling($sheet, $isAdmin, $lastDataRow, $startRow = 3)
    {
        $range = ($isAdmin ? "A{$startRow}:I{$lastDataRow}" : "A{$startRow}:K{$lastDataRow}");
        $sheet->getStyle($range)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Alternating row colors
        for ($row = $startRow + 1; $row <= $lastDataRow; $row++) {
            if ($row % 2 == 0) {
                $rowRange = ($isAdmin ? "A{$row}:I{$row}" : "A{$row}:K{$row}");
                $sheet->getStyle($rowRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
            }
        }

        // autosize kolom
        $endCol = $isAdmin ? 'I' : 'K';
        foreach (range('A', $endCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function calculateDataSize($findings)
    {
        $size = 0;
        foreach ($findings as $finding) {
            $size += strlen($finding->auditor ?? '') + strlen($finding->department ?? '') + strlen($finding->jenis_audit ?? '') + strlen($finding->kriteria ?? '') + strlen($finding->description ?? '') + strlen($finding->status ?? '');
            if ($finding->image && file_exists(public_path('storage/' . $finding->image))) $size += filesize(public_path('storage/' . $finding->image));
            if ($finding->image2 && file_exists(public_path('storage/' . $finding->image2))) $size += filesize(public_path('storage/' . $finding->image2));
        }
        return $size;
    }

    // ==== Util: Thumbnail + Base64 ====
    private function createThumbnail(string $fullPath, int $maxWidth, int $maxHeight): ?string
    {
        if (!file_exists($fullPath)) return null;
        [$width, $height, $type] = getimagesize($fullPath);
        if (!$width || !$height) return null;

        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $newW = (int) max(1, floor($width * $ratio));
        $newH = (int) max(1, floor($height * $ratio));

        switch ($type) {
            case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($fullPath); break;
            case IMAGETYPE_PNG: $src = imagecreatefrompng($fullPath); break;
            case IMAGETYPE_GIF: $src = imagecreatefromgif($fullPath); break;
            default: return null;
        }
        if (!$src) return null;

        $dst = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) @mkdir($tempDir, 0777, true);
        $thumbPath = $tempDir . '/' . md5($fullPath . $newW . 'x' . $newH) . '.jpg';
        imagejpeg($dst, $thumbPath, 75);
        imagedestroy($src); imagedestroy($dst);
        return $thumbPath;
    }

    private function generatePdfImageBase64(?string $relativePath): ?string
    {
        if (!$relativePath) return null;
        $full = public_path('storage/' . $relativePath);
        $thumb = $this->createThumbnail($full, 400, 300);
        if (!$thumb || !file_exists($thumb)) return null;
        $data = file_get_contents($thumb);
        if ($data === false) return null;
        return 'data:image/jpeg;base64,' . base64_encode($data);
    }

    private function getDepartmentCode($department)
    {
        return match (strtolower($department)) {
            'produksi' => 1,
            'warehouse' => 2,
            'engineering' => 3,
            'hr' => 4,
            'qa' => 5,
            default => 0,
        };
    }

    private function getCriteriaCode($criteria)
    {
        return match (strtolower($criteria)) {
            'pest' => 1,
            'infrastruktur' => 2,
            'lingkungan' => 3,
            'personal behavior' => 4,
            'cleaning' => 5,
            default => 0,
        };
    }

    private function getStatusCode($status)
    {
        return strtolower($status) === 'close' ? 1 : 2;
    }
}
