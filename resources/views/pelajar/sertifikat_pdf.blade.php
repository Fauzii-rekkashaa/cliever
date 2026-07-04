<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - {{ $enrollment->course->judul }}</title>
    <style>
        @page { margin: 0; }

        * { box-sizing: border-box; }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .certificate {
            width: 100%;
            height: 595px;
            position: relative;
            padding: 0;
        }

        /* Top accent bar */
        .top-bar {
            height: 14px;
            width: 100%;
            background: linear-gradient(90deg, #3b82f6, #2563eb, #1d4ed8);
        }

        .bottom-bar {
            height: 14px;
            width: 100%;
            background: linear-gradient(90deg, #1d4ed8, #2563eb, #3b82f6);
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .content {
            padding: 50px 70px 30px;
            text-align: center;
            position: relative;
        }

        /* Decorative corner shapes */
        .corner-shape {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            opacity: 0.06;
        }
        .corner-tl {
            top: -50px;
            left: -50px;
            background: #2563eb;
        }
        .corner-br {
            bottom: -50px;
            right: -50px;
            background: #2563eb;
        }

        /* Brand */
        .brand {
            font-size: 15px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 1px;
            margin-bottom: 28px;
        }
        .brand .icon {
            color: #fbbf24;
        }

        /* Badge icon */
        .badge-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: block;
            text-align: center;
            line-height: 64px;
            font-size: 28px;
        }

        .eyebrow {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin-bottom: 28px;
        }

        .sub {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .nama {
            font-size: 32px;
            font-weight: bold;
            color: #1e293b;
            margin: 6px 0 22px;
            padding-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            min-width: 420px;
        }

        .course-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .course {
            font-size: 21px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 36px;
        }

        /* Footer info row */
        .footer-info {
            display: table;
            width: 100%;
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 22px;
        }
        .footer-col {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }
        .footer-label {
            font-size: 9.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .footer-value {
            font-size: 13px;
            font-weight: bold;
            color: #1e293b;
        }
        .divider-dot {
            display: table-cell;
            width: 1px;
            vertical-align: middle;
        }
        .divider-dot span {
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .cert-footer-id {
            position: absolute;
            bottom: 28px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #cbd5e1;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="certificate">
    <div class="top-bar"></div>

    <div class="content">
        <div class="corner-shape corner-tl"></div>
        <div class="corner-shape corner-br"></div>

        <div class="brand"><span class="icon">&#10022;</span> CLIEVER</div>

        <div class="badge-icon">&#127942;</div>

        <p class="eyebrow">Sertifikat Penyelesaian</p>
        <h1 class="title">Certificate of Completion</h1>

        <p class="sub">Dengan ini menyatakan bahwa</p>
        <div class="nama">{{ $enrollment->user->nama ?? $enrollment->user->username }}</div>

        <p class="course-label">telah berhasil menyelesaikan seluruh materi pada course</p>
        <div class="course">{{ $enrollment->course->judul }}</div>

        <div class="footer-info">
            <div class="footer-col">
                <p class="footer-label">Pengajar</p>
                <p class="footer-value">{{ $enrollment->course->pengajar->nama ?? $enrollment->course->pengajar->username }}</p>
            </div>
            <div class="footer-col">
                <p class="footer-label">Tanggal Selesai</p>
                <p class="footer-value">{{ \Carbon\Carbon::parse($tanggalTerbit)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="footer-col">
                <p class="footer-label">Certificate ID</p>
                <p class="footer-value">{{ $certificateId }}</p>
            </div>
        </div>
    </div>

    <div class="cert-footer-id">DITERBITKAN OLEH CLIEVER LEARNING PLATFORM &nbsp;&bull;&nbsp; {{ $certificateId }}</div>

    <div class="bottom-bar"></div>
</div>

</body>
</html>
