<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'LAPORAN FINDINGS' }}</title>
    <style>
        @page { size: A4 {{ $isAdmin ? 'portrait' : 'landscape' }}; margin: 8mm; }
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            font-size: 10px;
        }
        .container { width: 100%; margin: 0 auto; }
        h1 { 
            text-align: center; 
            margin: 6px 0 8px; 
            font-size: 16px; 
            color: #333;
            font-weight: bold;
        }
        table {  
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 6px; 
            page-break-inside: auto; 
            table-layout: fixed; 
        }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { 
            border: 1px solid #ddd; 
            padding: 3px 4px; 
            text-align: center; 
            vertical-align: middle; 
            font-size: 8px; 
            word-wrap: break-word; 
            overflow-wrap: break-word; 
        }
        th { 
            background-color: #4CAF50; 
            color: white; 
            font-weight: bold; 
        }
        /* Default image size; overridden for non-admin */
        img { 
            display: block; margin: 0 auto; object-fit: cover; border-radius: 2px;
            max-width: 50px; max-height: 50px; width: auto; height: auto;
        }
        /* Non-admin: larger image cells with compact other columns to target >=4 rows/page */
        @if(!$isAdmin)
        .img-cell { 
            width: 25% !important;
            position: relative;
            overflow: hidden; 
        }
        .pdf-img-large { 
            max-width: 220px !important;
            max-height: 155px !important;
            width: auto; height: auto; 
            display: block; 
            object-fit: contain;
        }
        .compact { font-size: 6px !important; padding: 2px 3px !important; }
        @endif
        /* Admin: better styling for portrait layout */
        @if($isAdmin)
        th, td { 
            padding: 4px 6px; 
            font-size: 9px; 
        }
        .text-left { text-align: left; }
        @endif
        .no-photo { 
            font-size: 6px; 
            color: #999; 
            font-style: italic; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title ?? 'LAPORAN FINDINGS' }}</h1>
        <table>
            <thead>
                @if(!$isAdmin)
                <tr>
                    <th class="compact" style="width: 4%;">No</th>
                    <th class="img-cell">Foto Before</th>
                    <th class="img-cell">Foto After</th>
                    <th class="compact" style="width: 10%;">Departemen</th>
                    <th class="compact" style="width: 10%;">Auditor</th>
                    <th class="compact" style="width: 10%;">Jenis Audit</th>
                    <th class="compact" style="width: 10%;">Kriteria</th>
                    <th class="compact" style="width: 18%;">Deskripsi</th>
                    <th class="compact" style="width: 7%;">Week</th>
                    <th class="compact" style="width: 7%;">Status</th>
                    <th class="compact" style="width: 14%;">Catatan</th>
                </tr>
                @else
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Departemen</th>
                    <th style="width: 12%;">Auditor</th>
                    <th style="width: 12%;">Jenis Audit</th>
                    <th style="width: 12%;">Kriteria</th>
                    <th style="width: 20%;">Deskripsi</th>
                    <th style="width: 8%;">Week</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 11%;">Catatan</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @if (!$isAdmin)
                    @php $pages = $findings->chunk(4); @endphp
                    @foreach ($pages as $pageNo => $pageFindings)
                        @foreach ($pageFindings as $no => $finding)
                    <tr>
                                <td class="compact">{{ $pageNo * 4 + $no + 1 }}</td>
                                <td class="img-cell">
                                @if ($finding->image_base64)
                                        <img src="{{ $finding->image_base64 }}" alt="Before" class="pdf-img-large">
                                @else
                                    <span class="no-photo">Tidak ada/terlalu besar</span>
                                @endif
                            </td>
                                <td class="img-cell">
                                @if ($finding->image2_base64)
                                        <img src="{{ $finding->image2_base64 }}" alt="After" class="pdf-img-large">
                                @else
                                    <span class="no-photo">Tidak ada/terlalu besar</span>
                                @endif
                            </td>
                                <td class="compact">{{ $finding->department ?: '-' }}</td>
                                <td class="compact">{{ $finding->auditor ?: '-' }}</td>
                                <td class="compact">{{ $finding->jenis_audit ?: '-' }}</td>
                                <td class="compact">{{ $finding->kriteria ?: '-' }}</td>
                                <td class="compact text-left">{{ $finding->description ?: '-' }}</td>
                                <td class="compact">{{ $finding->week ?: '-' }}</td>
                                <td class="compact">{{ $finding->status ?: '-' }}</td>
                                <td class="compact text-left">{{ $finding->closing ? $finding->closing->catatan_penyelesaian : '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div style="width:100%;text-align:right;font-size:9px;margin-top:2mm;">
                        Halaman {{ $pageNo + 1 }} / {{ $pages->count() }}
                    </div>
                    @if (!$loop->last)
                        <div style="page-break-after: always;"></div>
                        @endif
                    <table>
                        <thead>
                            <tr>
                                <th class="compact">No</th>
                                <th class="img-cell">Foto Before</th>
                                <th class="img-cell">Foto After</th>
                                <th class="compact">Departemen</th>
                                <th class="compact">Auditor</th>
                                <th class="compact">Jenis Audit</th>
                                <th class="compact">Kriteria</th>
                                <th class="compact">Deskripsi</th>
                                <th class="compact">Week</th>
                                <th class="compact">Status</th>
                                <th class="compact">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                    @endforeach
                @else
                    @foreach ($findings as $no => $finding)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ $finding->department ?: '-' }}</td>
                            <td>{{ $finding->auditor ?: '-' }}</td>
                            <td>{{ $finding->jenis_audit ?: '-' }}</td>
                            <td>{{ $finding->kriteria ?: '-' }}</td>
                            <td class="text-left">{{ $finding->description ?: '-' }}</td>
                            <td>{{ $finding->week ?: '-' }}</td>
                            <td>{{ $finding->status ?: '-' }}</td>
                            <td class="text-left">{{ $finding->closing ? $finding->closing->catatan_penyelesaian : '-' }}</td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>
