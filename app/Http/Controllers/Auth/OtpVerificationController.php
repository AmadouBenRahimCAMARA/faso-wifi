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
        $this->middleware('auth');
        // We don't want already verified users accessing this, but we need them to be logged in (auth)
        // Check verification in index method
    }

    public function show()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->verification_code === $request->verification_code) {
            
            // Check expiry
            if ($user->verification_expires_at && now()->gt($user->verification_expires_at)) {
                return back()->with('error', 'Le code a expiré. Veuillez en demander un nouveau.');
            }

            $user->markEmailAsVerified();
            $user->verification_code = null;
            $user->verification_expires_at = null;
            $user->save();

            return redirect()->route('home')->with('success', 'Compte vérifié avec succès !');
        }

        return back()->with('error', 'Code invalide.');
    }

    public function resend()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $code = rand(100000, 999999);
        $user->verification_code = $code;
        $user->verification_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code));
        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors de l'envoi du mail.");
        }

        return back()->with('message', 'Un nouveau code a été envoyé.');
    }
}
