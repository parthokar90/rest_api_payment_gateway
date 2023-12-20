@extends('layout.master')

@section('title')
    Payment Failure
@endsection

@section('content')

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="center-form">
            <h2 class="text-center mb-4">Payment Failure</h2>
            <h4>Transaction Id: {{$transactionId}} </h4>
            <a class="btn btn-info" href="{{route('payment.form')}}">Back to payment</a>
        </div>
    </div>

@endsection
