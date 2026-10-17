@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Edit Customer') }} - {{ $customer->customer_code }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customers.update', $customer) }}">
                        @method('PUT')
                        @include('customers._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
