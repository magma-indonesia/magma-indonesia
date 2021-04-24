@foreach ($gadds as $gadd)
@foreach ($gadd->seismometers as $seismometer)
<option value="{{ $seismometer->id }}" {{ $id == $seismometer->id ? 'selected' : ''}}>
    {{ $gadd->name }} - {{ $seismometer->scnl }}</option>
@endforeach
@endforeach