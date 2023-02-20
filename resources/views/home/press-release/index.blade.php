@extends('layouts.slim')

@section('title')
Press Release
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">
	<a href="{{ route('press-release.index') }}">Press Release</a>
</li>
@endsection

@section('page-title')
Press Release
@endsection

@section('main')
<div class="row row-sm">
	<div class="col-lg-9">
		{{ $pressReleases->appends(request()->query())->links('vendor.pagination.slim-simple') }}
		@foreach ($pressReleases as $pressRelease)
		<div class="card card-latest-activity mg-t-20">
			<div class="card-body">
				<div class="row no-gutters">

					@if ($pressRelease->cover_thumbnail_url)
					<div class="col-md-4">
						<a href="{{ $pressRelease->cover_url }}" data-lightbox="file-set"
							data-title="{{ $pressRelease->judul }}">
							<img src="{{ $pressRelease->cover_thumbnail_url }}" class="img-fit-cover"
								alt="{{ $pressRelease->judul }}"
								data-value="{{ $pressRelease->id }}"
								style="height: 250px;">
						</a>
					</div>
					@endif

					<div class="{{ $pressRelease->cover_thumbnail_url ? "col-md-8" : "col-md-12" }}">
						<div class="post-wrapper">
							<span>{{ $pressRelease->datetime->formatLocalized('%A, %d %B %Y') }}</span>
							<a href="{{ $pressRelease->url }}" class="activity-title">{{$pressRelease->judul }}</a>

							<p>{{ $pressRelease->short_deskripsi }}</p>

							<p class="mg-b-0">
								@foreach ($pressRelease->tags as $tag)
									<a href="{{ route('press-release.index', ['tag' => $tag->slug]) }}"
										class="badge badge-pill badge-primary tx-12 mg-b-5">{{ $tag->name }}</a>
								@endforeach

								@if ($pressRelease->gunung_api)
									<a href="{{ route('press-release.index', ['category' => 'gunung-api']) }}"
										class="badge badge-pill badge-danger tx-12 mg-b-5">Gunung Api</a>
									<a href="{{ route('press-release.index', ['volcano' => $pressRelease->gunungApi->code]) }}"
										class="badge badge-pill badge-danger tx-12 mg-b-5">{{ "Gunung Api {$pressRelease->gunungApi->name}" }}</a>
								@endif

								@if ($pressRelease->gerakan_tanah)
									<a href="{{ route('press-release.index', ['category' => 'gerakan-tanah']) }}"
										class="badge badge-pill badge-danger tx-12 mg-b-5">Gerakan Tanah</a>
								@endif

								@if ($pressRelease->gempa_bumi)
									<a href="{{ route('press-release.index', ['category' => 'gempa-bumi']) }}"
										class="badge badge-pill badge-danger tx-12 mg-b-5">Gempa Bumi</a>
								@endif

								@if ($pressRelease->tsunami)
									<a href="{{ route('press-release.index', ['category' => 'tsunami']) }}"
										class="badge badge-pill badge-danger tx-12 mg-b-5">Tsunami</a>
								@endif

								@if ($pressRelease->lainnya)
									<a href="{{ route('press-release.index', ['category' => 'lainnya', 'value' => $pressRelease->lainnya]) }}"
										class="badge badge-pill badge-light tx-12 mg-b-5">{{ $pressRelease->lainnya }}</a>
								@endif

							</p>
						</div><!-- post-wrapper -->
					</div><!-- col-8 -->
				</div><!-- row -->

			</div><!-- card-body -->

		</div>
		@endforeach
		<div class="mg-t-20">
		{{ $pressReleases->appends(request()->query())->links('vendor.pagination.slim-simple') }}
		</div>
	</div>
</div>
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
@endsection
