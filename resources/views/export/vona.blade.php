<table>
    <thead>
        <tr>
            <th>Volcano</th>
            <th>Issued (UTC)</th>
            <th>Current Aviation Colour Code</th>
            <th>Volcano Cloud Height (ASL - Meter)</th>
            <th>Sender</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vonas as $vona)
        <tr>
            <td>{{ $vona->gunungapi->name }}</td>
            <td>{{ $vona->issued }}</td>
            <td>{{ title_case($vona->cu_code) }}</td>
            <td>{{ $vona->vch_asl }}</td>
            <td>{{ $vona->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>