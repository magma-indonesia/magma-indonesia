@extends('layouts.slim')

@section('title')
Glossary
@endsection

@section('add-vendor-css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/jquery.dataTables.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('v1.edukasi.index') }}">Edukasi</a></li>
<li class="breadcrumb-item active" aria-current="page">Glossary</li>
@endsection

@section('page-title')
Glossary
@endsection

@section('main')
<div class="section-wrapper">
    <label class="section-title">Table Glossary</label>
    <p class="mg-b-20 mg-sm-b-40">Daftar istilah yang digunakan dalam kebencanaan geologi.</p>
    <div class="table-wrapper">
        <table id="table-glossary" class="table display responsive">
            <thead>
                <tr>
                    <th>Istilah</th>
                    <th class="wd-80p">deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($glossaries as $glossary)
                <tr>
                    <td>{{ $glossary->judul }}</td>
                    <td>
                        <div>
                            {!! $glossary->deskripsi !!}
                        </div>
                        @if ($glossary->glossary_files->isNotEmpty())
                        <div class="bd pd-10 mg-b-10">
                            @foreach ($glossary->glossary_files as $index => $file)
                            <a href="{{ $file->url }}" data-lightbox="{{ $glossary->slug }}" data-title="{{ $glossary->judul.'_'.($index+1) }}"> <img class="img-fluid ht-100" src="{{ $file->thumbnail }}" alt="" />
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div><!-- section-wrapper -->
@endsection

@section('add-vendor-script')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/lightbox2/js/lightbox.min.js') }}"></script>
@endsection

@section('add-script')
<script>
$(document).ready(function() {
    $('#table-glossary').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });
});
</script>    
@endsection