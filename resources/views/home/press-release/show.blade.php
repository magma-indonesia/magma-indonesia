@extends('layouts.slim')

@section('title')
{{ $pressRelease->judul }}
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@if (!is_null($thumbnail))
    @section('thumbnail')
        {{ $thumbnail }}
    @endsection
@endif

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
    <div class="col-lg-12">
        <div class="card card-blog pd-20">

            @if ($cover)
            <img class="img-fit-cover pd-20" src="{{ $cover }}" alt="{{ $pressRelease->judul }}">
            @endif

            <div class="card-header">
                @role('Super Admin')
                <a href="{{ route('chambers.press-release.edit', $pressRelease) }}"
                    class="badge badge-pill badge-light tx-14" target="_blank">Edit Press Release</a>
                @endrole

                @foreach ($pressRelease->tags as $tag)
                    <a href="{{ route('press-release.index', ['tag' => $tag->slug]) }}"
                        class="badge badge-pill badge-primary tx-14" target="_blank">{{ $tag->name }}</a>
                @endforeach

                @if ($pressRelease->gunung_api)
                    <a href="{{ route('press-release.index', ['category' => 'gunung-api']) }}"
                        class="badge badge-pill badge-danger tx-14" target="_blank">Gunung Api</a>
                    <a href="{{ route('press-release.index', ['volcano' => $pressRelease->gunungApi->code]) }}"
                        class="badge badge-pill badge-danger tx-14">{{ "Gunung Api {$pressRelease->gunungApi->name}" }}</a>
                @endif

                @if ($pressRelease->gerakan_tanah)
                    <a href="{{ route('press-release.index', ['category' => 'gerakan-tanah']) }}"
                        class="badge badge-pill badge-danger tx-14">Gerakan Tanah</a>
                @endif

                @if ($pressRelease->gempa_bumi)
                    <a href="{{ route('press-release.index', ['category' => 'gempa-bumi']) }}"
                        class="badge badge-pill badge-danger tx-14">Gempa Bumi</a>
                @endif

                @if ($pressRelease->tsunami)
                    <a href="{{ route('press-release.index', ['category' => 'tsunami']) }}"
                        class="badge badge-pill badge-danger tx-14">Tsunami</a>
                @endif

                @if ($pressRelease->lainnya)
                    <a href="{{ route('press-release.index', ['category' => 'lainnya', 'value' => $pressRelease->lainnya]) }}"
                        class="badge badge-pill badge-light tx-14">{{ $pressRelease->lainnya }}</a>
                @endif
            </div>

            <div class="card-body" style="border: 0">
                <p class="blog-category">{{ $pressRelease->datetime->formatLocalized('%A, %d %B %Y') }}</p>
                <h5 class="blog-title tx-medium">
                    <p class="tx-black tx-normal tx-20">{{ $pressRelease->judul }}</p>
                </h5>
                <div class="tx-20">
                    <a href="https://www.facebook.com/pvmbg_" class="tx-primary mg-r-5"><i
                            class="fa fa-facebook"></i></a>
                    <a href="https://twitter.com/pvmbg_" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                    <a href="https://github.com/magma-indonesia" class="tx-inverse mg-r-5"><i
                            class="fa fa-github"></i></a>
                    <a href="https://www.instagram.com/pvmbg_" class="tx-pink mg-r-5"><i
                            class="fa fa-instagram"></i></a>
                </div>
                <hr>

                <p class="blog-text lh-8" style="text-align: justify">{!! $pressRelease->deskripsi !!}</p>

                @if ($pressRelease->press_release_files->isNotEmpty())

                    {{-- Has Gambars --}}
                    @if ($pressRelease->press_release_files->whereIn('collection', ['petas', 'gambars'])->count())
                    <label class="section-title">Daftar Foto, Gambar dan Peta</label>

                    <div class="table-responsive">
                        <table class="table mg-b-0 tx-12">
                            <tbody>
                                @foreach ($pressRelease->press_release_files as $pressReleaseFile)
                                    @if ($pressReleaseFile->collection !== 'files')
                                        <tr>
                                            <td class="wd-20p pd-l-20">
                                                <a href="{{ $pressReleaseFile->url }}" data-lightbox="file-set"
                                                    data-title="{{ $pressReleaseFile->overview ?? $pressRelease->judul }}">
                                                    <img src="{{ $pressReleaseFile->thumbnail }}" class="send wd-100"
                                                        alt="{{ $pressReleaseFile->overview ?? $pressRelease->judul }}"
                                                        data-value="{{ $pressReleaseFile->id }}">
                                                </a>
                                            </td>
                                            <td class="valign-middle">
                                                {{ $pressReleaseFile->overview ?? $pressRelease->judul }}
                                                <span class="tx-11 d-block"><span
                                                        class="square-8 bg-info mg-r-5 rounded-circle"></span>{{ $pressReleaseFile->size_kb }}</span>
                                                <a class="btn btn-sm btn-oblong btn-outline-primary mg-t-10"
                                                    href="{{ $pressReleaseFile->url }}"
                                                    download="{{ $pressReleaseFile->overview ?? $pressRelease->judul }}">Download</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- Has Documents --}}
                    @if (($pressRelease->press_release_files->whereIn('collection', ['files'])->count()))
                    <label class="section-title">Dokumen Press Release</label>
                        <div class="file-group">
                        @foreach ($pressRelease->press_release_files as $pressReleaseFile)

                            @if ($pressReleaseFile->collection === 'files')
                            <div class="file-item">
                                <div class="row no-gutters wd-100p">
                                    <div class="col-9 align-items-center">
                                        <i class="fa fa-file-pdf-o"></i>
                                        <a href="{{ $pressReleaseFile->url }}" download="{{ $pressReleaseFile->file_name }}">{{ $pressReleaseFile->file_name }}</a>
                                    </div><!-- col-6 -->
                                    <div class="col-3 tx-right ">{{ $pressReleaseFile->size_kb }}</div>
                                </div><!-- row -->
                            </div><!-- file-item -->
                            @endif

                        @endforeach
                        </div>

                    @endif

                @endif

            </div>
            <div class="card-footer bd-t">
                <div class="tx-20">
                    <a href="https://www.facebook.com/pvmbg_" class="tx-primary mg-r-5"><i
                            class="fa fa-facebook"></i></a>
                    <a href="https://twitter.com/pvmbg_" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                    <a href="https://github.com/magma-indonesia" class="tx-inverse mg-r-5"><i
                            class="fa fa-github"></i></a>
                    <a href="https://www.instagram.com/pvmbg_" class="tx-pink mg-r-5"><i
                            class="fa fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('add-vendor-script')
    <script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
@endsection
