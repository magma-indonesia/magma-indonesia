@extends('layouts.default')

@section('title')
    Kebencanaan Geologi
@endsection

@section('content-header')
<div class="content animate-panel content-boxed normalheader">
    <div class="hpanel">
        <div class="panel-body">   
            <h2 class="font-light m-b-xs">
                Resume Gunung Api {{ $resumeHarian->tanggal->formatLocalized('%A, %d %B %Y') }}
            </h2>
            <small class="font-light"> Resume Harian Gunung Api</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed animate-panel">

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    {{ $resumeHarian->tanggal->formatLocalized('%A, %d %B %Y') }}
                </div>

                <div class="panel-body">
                    <form>
                        <div class="form-group col-sm-12">
                            <div class="m-t-xs">
                                <button id="copy" class="btn btn-primary" type="button">Copy</button>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <textarea id="whatsapp" class="form-control" rows="50">{{  $resumeHarian->resume }}</textarea>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="m-t-xs">
                                <button id="copy" class="btn btn-primary" type="button">Copy</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('add-script')
<script>
    function copy() {
        var copyText = document.querySelector("#whatsapp");
        copyText.select();
        document.execCommand("copy");
        alert('Copied!');
    }

    document.querySelector("#copy").addEventListener("click", copy);
</script> 
@endsection