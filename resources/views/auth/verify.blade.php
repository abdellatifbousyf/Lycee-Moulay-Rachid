{{-- ✅ المسار: resources/views/auth/verify.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Verify Your Email Address') }}
                    {{-- ✅ عرض حالة التحقق --}}
                    @if (Auth::user()->hasVerifiedEmail())
                        <span class="badge bg-success float-end">{{ __('Verified') }}</span>
                    @endif
                </div>

                <div class="card-body">
                    {{-- ✅ رسالة النجاح عند إعادة الإرسال --}}
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('A fresh verification link has been sent to your email address.') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    {{-- ✅ الرسالة التوجيهية --}}
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </div>

                    <p class="mb-4">
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-decoration-none">
                                {{ __('click here to request another') }}
                            </button>.
                        </form>
                    </p>

                    {{-- ✅ معلومات إضافية للمستخدم --}}
                    <div class="text-muted small mb-4">
                        <i class="fas fa-envelope me-1"></i>
                        <strong>{{ __('Email') }}:</strong> {{ Auth::user()->email }}
                        <br>
                        <i class="fas fa-clock me-1"></i>
                        {{ __('Sent at') }}: {{ Auth::user()->created_at->format('d/m/Y H:i') }}
                    </div>

                    {{-- ✅ أزرار التنقل --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('logout') }}" class="btn btn-link text-decoration-none"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                        </a>

                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Go Back') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- ✅ تلميح إضافي --}}
            <div class="mt-3 text-center text-muted small">
                <i class="fas fa-shield-alt me-1"></i>
                {{ __('This step helps protect your account security.') }}
            </div>
        </div>
    </div>
</div>

{{-- ✅ Form Logout Hidden --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection
