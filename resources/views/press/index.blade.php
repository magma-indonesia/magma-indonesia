@extends('layouts.default')

@section('title') 
    Daftar Press Release 
@endsection 

@section('add-vendor-css')
	<link rel="stylesheet" href="{{ asset('vendor/sweetalert/lib/sweet-alert.css') }}" /> 
@endsection 

@section('content-header')
	<div class="small-header">
		<div class="hpanel">
			<div class="panel-body">
				<div id="hbreadcrumb" class="pull-right">
					<ol class="hbreadcrumb breadcrumb">
						<li>
							<a href="{{ route('chamber') }}">Chamber</a>
						</li>
						<li>
							<span>Press Release</span>
						</li>
						<li class="active">
							<span>Daftar </span>
						</li>
					</ol>
				</div>
				<h2 class="font-light m-b-xs">
					Daftar Press Release
				</h2>
				<small>Daftar Press Release yang pernah dikeluarkan oleh PVMBG</small>
			</div>
		</div>
	</div>
@endsection 

@section('content-body')
	<div class="content animate-panel">
		<div class="row">
			<div class="col-md-12">
				@if ($presses->isEmpty())
				<div class="alert alert-info">
					<h4>
						<i class="fa fa-bolt"></i> Tidak ada data Press Release.
						<a href="{{ route('press.create') }}">
							<strong>Buat Press Release?</strong>
						</a>
					</h4>
				</div>
				@endif @if(Session::has('flash_message'))
				<div class="alert alert-success">
					<i class="fa fa-bolt"></i> {!! session('flash_message') !!}
				</div>
				@endif

				<div class="hpanel">
					<div class="v-timeline vertical-container animate-panel" data-child="vertical-timeline-block" data-delay="1">
						@foreach($presses as $press)
						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-calendar"></i>
							</div>
							<div class="vertical-timeline-content">
								<div class="panel-heading hbuilt">
									<div class="pull-right">
										<form style="display:inline" id="form-delete" method="POST" action="{{ route('press.destroy',['id'=>$press->id]) }}" accept-charset="UTF-8">
											{{ method_field('DELETE') }} {{ csrf_field() }}
											<button class="btn btn-danger btn-xs" type="submit">Delete</button>
										</form>
										<a role="button" href="{{ route('press.edit',['id' => $press->id] )}}" class="btn btn-default btn-xs"> Edit</a>
											<a role="button" href="{{ route('press.show',['id' => $press->id] )}}" class="btn btn-default btn-xs"> View</a>
									</div>
									<h4>{{ $press->title }}, 								<small>
										<i>{{ $press->updated_at ? 'updated at '.$press->updated_at : '' }} </i>
									</small></h4>

								</div>
								<div class="panel-body p-m">
									<span class="vertical-date pull-right"> {{ \Carbon\Carbon::parse($press->created_at)->format('l') }}
										<br/>
										<small>{{ \Carbon\Carbon::parse($press->created_at)->format('d M Y H:i:s') }}</small>
									</span>
									<div style="line-height: 1.8;">
									{!! str_limit(strip_tags($press->body),300) !!}
									</div>
									
								</div>
								<div class="panel-footer">
									Dibuat oleh, {{ $press->user->name }}
								</div>
							</div>
						</div>
						@endforeach
					</div>
					{{ $presses->links() }}
				</div>

			</div>
		</div>
	</div>
@endsection 

@section('add-vendor-script')
	<script src="{{ asset('vendor/sweetalert/lib/sweet-alert.min.js') }}"></script>
@endsection 

@section('add-script')
<script>
	$(document).ready(function(){

            $('form#form-delete').on('submit',function (e) {
                e.preventDefault();

                var $this = $(this);
                    $url = $(this).attr('action'),
                    $data = $(this).serialize();

                swal({
                    title: "Anda yakin?",
                    text: "Data yang telah dihapus tidak bisa dikembalikan",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Hapus Gan!!",
                    cancelButtonText: "Gak jadi deh!",
                    closeOnConfirm: false,
                    closeOnCancel: true },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: $url,
                            data: $data,
                            type: 'POST',
                            success: function(data){
                                if (data.success){
                                    swal("Berhasil!", data.message, "success");
                                    $this.closest('.vertical-timeline-block').remove();
                                }
                            }
                        });
                    }
                });
            });
        });
</script>
@endsection