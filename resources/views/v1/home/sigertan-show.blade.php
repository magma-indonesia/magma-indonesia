@extends('layouts.slim') 

@section('title') 
{{ $gertan->laporan->judul }}
@endsection
 
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Gerakan Tanah</a></li>
<li class="breadcrumb-item active" aria-current="page">Tanggapan Kejadian</li>
@endsection
 
@section('page-title')
Tanggapan Kejadian
@endsection
 
@section('main')
<div class="row">
	<div class="col-lg-6">
		<div class="card pd-30 mg-b-30">
			<h3 class="tx-inverse pd-r-30">{{ $gertan->laporan->judul }}</h3>
			<p class="tx-12">Dibuat oleh <a href="#">{{ $gertan->laporan->pelapor }}</a><span class="visible-md visible-lg">, {{ $gertan->laporan->updated_at->formatLocalized('%d %B %Y').' pukul '.$gertan->laporan->updated_at->format('H:i:s').' WIB' }}</span></p>
			<h6 class="slim-card-title mg-b-15">Lokasi dan Waktu Kejadian</h6>
			<p class="pd-r-30">{{ $gertan->laporan->deskripsi }}</p>

			@if ($gertan->rekomendasi)
			<h6 class="slim-card-title mg-b-15">Rekomendasi</h6>
			<p>{{ $gertan->rekomendasi }}</p>
			@endif

			@if (!$gertan->laporan->foto_sosialisasi->isEmpty() || !$gertan->laporan->foto_kejadian->isEmpty())
			<h6 class="slim-card-title mg-b-15">Foto Kejadian dan Sosialisasi</h6>
			<div id="carousel1" class="carousel slide mg-b-30" data-ride="carousel">
				<div class="carousel-inner" role="listbox">
					@foreach ($gertan->laporan->foto_sosialisasi as $foto) 
					@if($loop->first)
					<div class="carousel-item ht-250 active">
						<img class="d-block img-fluid" src="{{ $foto->qls_sos }}" alt="{{ $foto->qls_ids }}">
					</div>
					@else
					<div class="carousel-item">
						<img class="d-block img-fluid" src="{{ $foto->qls_sos }}" alt="{{ $foto->qls_ids }}">
					</div>
					@endif
					@endforeach 

					@foreach ($gertan->laporan->foto_kejadian as $foto)
					<div class="carousel-item ht-250 {{ ($gertan->laporan->foto_sosialisasi->isEmpty() AND $loop->first) ? 'active' : ''}}">
						<img class="d-block img-fluid" src="{{ $foto->qls_fst }}" alt="Kejadian {{ $foto->qls_ids }}">
					</div>
					@endforeach
					<a class="carousel-control-prev" href="#carousel1" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carousel1" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			@endif

			@if ($gertan->laporan->peta)
			<a class="btn btn-outline-primary" href="{{ $gertan->laporan->peta }}" target="_blank" class="card-link">Download Peta</a>
			@endif
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card pd-30">
			<h6 class="slim-card-title mg-b-15 tx-14">Informasi Gerakan Tanah</h6>
			<dl class="row">
				<dt class="col-sm-4 tx-inverse">Tipe Gerakan Tanah</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->tipe }}</dd>

				<dt class="col-sm-4 tx-inverse">Dampak Gerakan Tanah</dt>
				<dd class="col-sm-8">
					@foreach ($gertan->tanggapan->dampak as $dampak)
					<p class="mg-0">{{ $dampak }}</p>
					@endforeach
				</dd>
			</dl>
			<hr>
			<h6 class="slim-card-title mg-b-15 tx-14">Kondisi Daerah Bencana</h6>
			<dl class="row">
				<dt class="col-sm-4 tx-inverse">Morfologi</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->kondisi->morfologi }}</dd>

				<dt class="col-sm-4 tx-inverse">Geologi</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->kondisi->geologi }}</dd>

				<dt class="col-sm-4 tx-inverse">Keairan</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->kondisi->keairan }}</dd>

				<dt class="col-sm-4 tx-inverse">Tata Guna Lahan</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->kondisi->tata_guna_lahan }}</dd>

				<dt class="col-sm-4 tx-inverse">Kerentanan</dt>
				<dd class="col-sm-8">{{ $gertan->tanggapan->kondisi->kerentanan }}</dd>
			</dl>
			<hr>
			<h6 class="slim-card-title mg-b-15 tx-14">Faktor Penyebab Gerakan Tanah</h6>
			<div>
				{!! $gertan->tanggapan->kondisi->penyebab !!}
			</div>
			<hr>
			
			<div>
			@if (count($gertan->laporan->anggota) > 1)
			<h6 class="slim-card-title mg-b-15 tx-14">Anggota Tim</h6>
				@foreach ($gertan->laporan->anggota as $anggota)
					@if (!$loop->first)
						{{$anggota->user->vg_nama}}</br>
					@endif
				@endforeach
			@endif
			</div>
		</div>
	</div>
	
</div>
@endsection