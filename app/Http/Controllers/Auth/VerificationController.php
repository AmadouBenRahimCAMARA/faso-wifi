<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Show the verification form.
     */
    public function show()
    {
        // Check session for user_id_to_verify
        if (!session('user_id_to_verify')) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        return view('auth.verify');
    }

    /**
     * Verify the code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('user_id_to_verify');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        // Check if already verified
        if ($user->email_verified_at) {
             Auth::login($user);
             session()->forget('user_id_to_verify');
             return redirect()->route('home');
        }

        if ($user->verification_code !== $request->code) {
            return back()->with('error', 'Code incorrect.');
        }

        if (Carbon::now()->gt($user->verification_expires_at)) {
            return back()->with('error', 'Code expiré. Veuillez demander un nouveau code.');
        }

        // Success
        $user->email_verified_at = Carbon::now();
        $user->verification_code = null;
        $user->verification_expires_at = null;
        $user->save();

        Auth::login($user);
        session()->forget('user_id_to_verify');

        return redirect()->route('home')->with('success', 'Email vérifié avec succès !');
    }

    /**
     * Resend the code.
     */
    public function resend()
    {
        $userId = session('user_id_to_verify');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);
        
        // Generate new code
        $code = rand(100000, 999999);
        $user->verification_code = $code;
        $user->verification_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        // Send Email
        try {
             Mail::raw("Votre code de vérification est : $code", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Code de vérification - Faso Wifi');
            });
             return back()->with('success', 'Nouveau code envoyé !');
        } catch (\Exception $e) {
             return back()->with('error', "Erreur lors de l'envoi de l'email: " . $e->getMessage());
        }
    }
}
