@foreach ($gadds as $gadd)
@foreach ($gadd->seismometers as $seismometer)
<option value="{{ $seismometer->scnl }}" {{ $scnl == $seismometer->scnl ? 'selected' : ''}}>
    {{ $gadd->name }} - {{ $seismometer->scnl }}</option>
@endforeach
@endforeach