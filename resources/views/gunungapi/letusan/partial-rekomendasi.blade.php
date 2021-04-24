@foreach ($rekomendasis as $key => $rekomendasi)
<div class="hpanel hblue rekomendasi-{{ $rekomendasi->id }} {{ $key != 0 ? 'collapsed' : ''}}">
    <div class="panel-heading hbuilt">
        <div class="panel-tools">
            <a class="showhide-rekomendasi"><i
                    class="fa {{ $key != 0 ? 'fa-chevron-circle-down' : 'fa-chevron-circle-up'}} fa-2x"></i></a>
        </div>
        <div class="p-xs" style="max-width: 50%;">
            <div class="checkbox">
                <label class="checkbox-inline">
                    <input name="rekomendasi" value="{{ $rekomendasi->id }}" type="radio" class="i-checks"
                        {{ $key == 0 ? 'checked' : '' }} required>
                    Pilih Rekomendasi {{ $key+1 }} {!! $key == 0 ? '<span class="label label-magma">default</span>' : ''
                    !!}
                </label>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="p-sm">
            <p style="line-height: 1.6;">{!! nl2br($rekomendasi->rekomendasi) !!}</p>
        </div>
    </div>
    @role('Super Admin')
    <div class="panel-footer text-right">
        <div class="btn-group">
            <button class="btn btn-danger delete-rekomendasi" type="button" rekomendasi-id="{{ $rekomendasi->id }}"
                value="{{ route('chambers.laporan.destroy.var.rekomendasi',['id' => $rekomendasi->id]) }}"><i
                    class="fa fa-trash"></i> Delete</button>
        </div>
    </div>
    @endrole
</div>
@endforeach