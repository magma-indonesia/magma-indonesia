<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal dan Waktu kejadian (WIB)</th>
            <th>Area</th>
            <th>Kota Terdekat</th>
            <th>Magnitude (SR)</th>
            <th>Kedalaman (Km)</th>
            <th>Latitude (LU)</th>
            <th>Longitude (BT)</th>
            <th>MMI</th>
            <th>Potensi Tsunami</th>
            <th>Pendahuluan</th>
            <th>Kondisi Wilayah</th>
            <th>Mekanisme</th>
            <th>Efek Gempa</th>
            <th>Rekomendasi</th>
            <th>Sumber Data</th>
            <th>Dibuat pada tanggal</th>
            <th>Pembuat Laporan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roqs as $key => $roq)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $roq->datetime_wib }}</td>
            <td>{{ $roq->area }}</td>
            <td>{{ $roq->koter }}</td>
            <td>{{ $roq->magnitude }}</td>
            <td>{{ $roq->depth }}</td>
            <td>{{ $roq->lat_lima }}</td>
            <td>{{ $roq->lon_lima }}</td>
            <td>{{ $roq->mmi ?: '-' }}</td>
            <td>{{ $roq->roq_tsu == 'YA' ? 'Ya' : 'Tidak' }}</td>
            <td>{{ $roq->roq_intro ? str_replace('â€“','',str_replace('Â','',$roq->roq_intro)) : '-' }}</td>
            <td>{!! $roq->roq_konwil ? nl2br(str_replace('Â','',$roq->roq_konwil)) : '-' !!}</td>
            <td>{!! $roq->roq_mekanisme ? nl2br(str_replace('Â','',$roq->roq_mekanisme)) : '-' !!}</td>
            <td>{!! $roq->roq_efek ? nl2br(str_replace('Â','',$roq->roq_efek)) : '-' !!}</td>
            <td>{!! $roq->roq_rekom ? nl2br(str_replace('â€¢ ','',str_replace('Â','',$roq->roq_rekom))) : '-' !!}</td>
            <td>{{ $roq->roq_source ?: '-' }}</td>
            <td>{{ $roq->roq_logtime ?: '-' }}</td>
            <td>{{ $roq->roq_nama_pelapor ?: '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>