<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $input = $this->input('nip');
        $password = $this->input('password');
        
        $authenticated = false;
        
        // Cek apakah input adalah email (untuk admin)
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            // Login menggunakan email untuk admin
            $authenticated = Auth::attempt(['email' => $input, 'password' => $password], $this->boolean('remember'));
            \Log::info('Login attempt with email', ['email' => $input, 'success' => $authenticated]);
        } else {
            // Login menggunakan NIP untuk pegawai
            $authenticated = Auth::attempt(['nip' => $input, 'password' => $password], $this->boolean('remember'));
            \Log::info('Login attempt with NIP', ['nip' => $input, 'success' => $authenticated]);
        }
        
        if (!$authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'nip' => 'NIP/Email atau password salah.',
            ]);
        }
        
        // Check if user is authenticated after attempt
        \Log::info('After authentication', ['auth_check' => Auth::check(), 'user_id' => Auth::id()]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('nip')).'|'.$this->ip());
    }
}
