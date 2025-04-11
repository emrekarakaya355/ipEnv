<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\ModelNotFoundException;
use App\Models\User;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
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
        $loginInput = $this->input('login');
        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($loginType == 'username') {
            $loginInput .= '@nevsehir.edu.tr';
        }

        if($this->LdapLogin($loginInput, $this->password)) {

            $bilgiler = User::where('email','=',$loginInput)->first();
            if(!$bilgiler){
                throw new ModelNotFoundException('Yetkiniz Yok');
            }
            Auth::loginUsingId($bilgiler->id);
            RateLimiter::clear($this->throttleKey());
        }else{
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.email' =>  'E-posta adresiniz veya şifreniz yanlış. Lütfen tekrar kontrol edin ve yeniden deneyin.',
            ]);
        }

        /*
        if (! Auth::attempt([$loginType => $this->input('login'), 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
*/
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
            'email' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    private function LdapLogin($email,$password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;

        $fp = fsockopen ( "79.123.186.104" , 110 );
        if (!$fp) {
            return false;
        }
        $trash = fgets ( $fp, 128 );
        fwrite ( $fp, "USER ".$email."\r\n" );

        $trash = fgets ( $fp, 128 );
        fwrite ( $fp, "PASS ".$password."\r\n" );
        $result = fgets ( $fp, 128 );

        if(str_starts_with($result, '+OK'))
            return true;
        else
            return false;
    }
}
