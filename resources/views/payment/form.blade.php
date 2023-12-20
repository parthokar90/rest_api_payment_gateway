@extends('layout.master')

@section('title')
    Payment Form
@endsection

@section('content')

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="center-form">
            <h2 class="text-center mb-4">Payment Form</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('payment.process') }}">
                @csrf
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount"
                        required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit Payment</button>
            </form>
        </div>
    </div>

@endsection
