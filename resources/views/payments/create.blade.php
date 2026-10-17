@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Record Payment') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store') }}">
                        @include('payments._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
