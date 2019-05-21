@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтвердіть почту') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Нове посилання для відновленя паролю було відправлено.') }}
                        </div>
                    @endif

                    {{ __('Перевірте почтову адресу.') }}
                    {{ __('Якщо не отримали повідомення, ') }}, <a href="{{ route('verification.resend') }}">{{ __('натисніть сюда') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
