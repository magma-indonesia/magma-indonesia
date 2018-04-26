<table class="table table-striped">
    <thead>
        <tr>
            <th>Status</th>
            <th>Nama Pelapor</th>
            <th>No. HP</th>
            <th>Tipe Bencana</th>
            <th>Validasi</th>
            <th>Validator</th>
            <th>Waktu Kejadian</th>
            <th>Lokasi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($crs as $item)
        <tr title="{{ $item->name }}">
            <td class="{{ $item->status == 'BARU' ? 4 : ($item->status == 'DRAFT' ? 2 : 1) }}">{{ title_case($item->status) }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ title_case($item->type) }}</td>
            <td><span class="label {{ optional($item->validator)->valid ? $item->validator->valid == 'Valid' ? 'label-success' : 'label-warning': 'label-danger'}}">{{ optional($item->validator)->valid ? $item->validator->valid : 'Belum divalidasi'}}</span></td>
            <td>{{ optional($item->validator)->user ? $item->validator->user->name : '-'}}</td>  
            <td>{{ $item->waktu_kejadian }}</td>
            <td>{{ Indonesia::findProvince($item->province_id)->name .', '. Indonesia::findCity($item->city_id)->name .', '. Indonesia::findDistrict($item->district_id)->name }}</td>
            <td>{{ $item->tsc .' - '. $item->ksc }}</td>                                      
        </tr>
        @endforeach
    </tbody>
</table>