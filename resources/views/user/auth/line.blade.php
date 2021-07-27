@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>LINEからのお問い合わせ</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item active">LINEからお問い合わせ</div>
      @endif
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">




            <img src="{{ asset('img/line.png') }}" width="300px"/>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
