@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтвердите почту') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Новая ссылка для подтверждения была отправлена.') }}
                        </div>
                    @endif

                    {{ __('Проверьте почтовый адрес.') }}
                    {{ __('Если не получили сообщения, ') }}, <a href="{{ route('verification.resend') }}">{{ __('нажмите здесь') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
