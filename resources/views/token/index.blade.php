@extends('layouts.default')

@section('title')
Token Request
@endsection

@section('content-header')
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>
                        <a href="{{ route('chambers.index') }}">Chamber</a>
                    </li>
                    <li class="active">
                        <a href="{{ route('chambers.token.index') }}">Token</a>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Token API
            </h2>
            <small>Daftar Token yang pernah dibuat</small>
        </div>
    </div>
</div>
@endsection

@section('content-body')
<div class="content content-boxed">

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
                <p>Token yang dibuat hanya ditampilkan sekali, jadi <b>catat dan simpanlah Token Anda</b>. Token yang buat hanya <b>valid untuk 1 hari</b>. Dalam satu hari <b>maksimal 5 Token</b> yang bisa dibuat.</p>
            </div>
        </div>
    </div>

    @if(Session::has('failed'))
    <div class="alert alert-danger">
        <i class="fa fa-bolt"></i> {{  session('failed')  }}
    </div>
    @endif

    @if ($tokens->isEmpty())
    <div class="alert alert-danger">
        <i class="fa fa-gears"></i> Request Token belum pernah dibuat. <a href="{{ route('chambers.token.generate') }}" onclick="event.preventDefault();document.getElementById('generate').submit();"><b>Buat baru?</b></a>

        <form id="generate" action="{{ route('chambers.token.generate') }}" method="post">
            @csrf
        </form>
    </div>
    @else

    @if(Session::has('success'))
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel hgreen">
                <div class="panel-body">
                    <h4>Token berhasil dibuat</h4>
                    <div class="text-muted font-bold m-b-xs">Pada {{ now() }}</div>
                    <hr>
                    <textarea id="token" class="form-control" rows="5">{{ session('success') }}</textarea>

                    <button id="copy" class="btn btn-primary m-t">Copy</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    Daftar Request Token
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-statistik" class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah Request</th>
                                    <th>Time Last Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokens as $token)
                                <tr>
                                    <td>{{ $token->date }}</td>
                                    <td>{{ $token->count ?? 0 }}</td>
                                    <td>{{ $token->updated_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button class="btn btn-success copy m-t" onclick="event.preventDefault();document.getElementById('generate').submit();">Buat baru?</button>
                </div>

                <form id="generate" action="{{ route('chambers.token.generate') }}" method="post">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection

@section('add-script')
<script>
    function copy() {
        var copyText = document.querySelector("#token");
        copyText.select();
        document.execCommand("copy");
        alert('Copied!');
    }

    document.querySelector("#copy").addEventListener("click", copy);
</script>
@endsection