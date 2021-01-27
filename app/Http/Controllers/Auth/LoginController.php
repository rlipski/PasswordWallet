<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Password;
use App\Models\LoginAttempt;
use Auth;

class LoginController extends Controller
{
    public function login()
    {
      if (Auth::user()) {
        return redirect('home');
      } else {
        return view('auth.login');
      }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where([
            'email' => $request->email
        ])->first();

        if ($user)
        {
            if ($user->is_password_kept_as_hash == 1) {
              $passwordHash = hash("sha512", $user->salt . config('Constans.PEPPER') . $request->password);
              $validUser = User::where([
                  'email' => $request->email,
                  'password' => $passwordHash
              ])->first();
            } else {
              $validUser = User::where([
                  'email' => $request->email,
                  'password' => hash_hmac('sha512', $request->password, 'random key')
              ])->first();
            }
            if ($validUser) {//The user data is correct
              Auth::login($user);
              LoginAttempt::create([
                'user_id' => $user->id,
                'is_login_correct' => '1'
              ]);

              return redirect('home');
            } else {
              LoginAttempt::create([
                'user_id' => $user->id,
                'is_login_correct' => '0'
              ]);
            }
        }

        return redirect('login')->with('error', 'Oppes! You have entered invalid credentials');
    }

    public function logout() {
      Auth::logout();

      return redirect('login');
    }

    public function home()
    {
       $passwords = Password::where([
            'user_id' => Auth::user()->id
        ])->get();



      if (Auth::user()) {
        return view('home.home', ['passwords' => $passwords]);
      } else {
        return redirect('login');
      }
    }
}