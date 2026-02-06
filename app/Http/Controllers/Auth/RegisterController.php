<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        // Standard validation (checks unique:users in DB)
        $this->validator($request->all())->validate();

        // Generate 6-digit code
        $code = rand(100000, 999999);
        
        // Store everything in Session (expires in 10 mins approx via session lifetime or manual check)
        $data = $request->all();
        $data['password'] = Hash::make($data['password']); // Hash now
        $data['otp'] = $code;
        $data['otp_expires_at'] = now()->addMinutes(10);

        // We use the email as a key or just a single 'pending_registration' key
        // Assuming one pending registration per session is enough
        session()->put('pending_registration', $data);

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($data['email'])->send(new \App\Mail\VerificationCodeMail($code));
        } catch (\Exception $e) {
            // Log error
        }

        // Redirect to verification page
        return redirect()->route('verification.notice');
    }
    
    // Original methods preserved but unused by our override
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'pays' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // Unused in new flow
        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'pays' => $data['pays'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
