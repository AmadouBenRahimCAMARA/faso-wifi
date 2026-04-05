<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class OtpVerificationController extends Controller
{
    public function __construct()
    {
        // No auth middleware, we verify checking the session
        $this->middleware('guest');
    }

    public function show()
    {
        if (!session()->has('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $data = session()->get('pending_registration');

        if (!$data) {
            return redirect()->route('register')->with('error', 'Session expirée, veuillez recommencer.');
        }

        if ($data['otp'] == $request->verification_code) {
            
            // Check expiry
            if (isset($data['otp_expires_at']) && now()->gt($data['otp_expires_at'])) {
                 return back()->with('error', 'Le code a expiré. Veuillez en demander un nouveau.');
            }

            // Create User NOW
            // We use the password hash already stored in session
            $user = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'pays' => $data['pays'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => $data['password'],
                'email_verified_at' => now(), // Mark as verified immediately
                // verification_code columns are simpler to leave null or used for logs? we leave null
            ]);

            // Clear session
            session()->forget('pending_registration');

            // Login
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Compte créé et vérifié avec succès !');
        }

        return back()->with('error', 'Code invalide.');
    }

    public function resend()
    {
        $data = session()->get('pending_registration');

        if (!$data) {
            return redirect()->route('register');
        }

        $code = rand(100000, 999999);
        $data['otp'] = $code;
        $data['otp_expires_at'] = now()->addMinutes(10);
        
        session()->put('pending_registration', $data);

        try {
            Mail::to($data['email'])->send(new VerificationCodeMail($code));
        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors de l'envoi du mail.");
        }

        return back()->with('message', 'Un nouveau code a été envoyé.');
    }
}
