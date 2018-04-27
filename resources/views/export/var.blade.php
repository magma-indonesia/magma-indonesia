<table>
    <thead>
        <tr>
            <th>Gunung Api</th>
            <th>Nama Pelapor</th>
            <th>NIP</th>
            <th>Tanggal Laporan</th>
            <th>Periode Laporan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vars as $var)
        <tr>
            <td>{{ $var->gunungapi->name }}</td>
            <td>{{ $var->user->name }}</td>
            <td>{{ $var->user->nip }}</td>
            <td>{{ $var->var_data_date }}</td>
            <td>{{ $var->var_perwkt.' Jam, '.$var->periode }}</td>
        </tr>
        @endforeach
    </tbody>
</table>