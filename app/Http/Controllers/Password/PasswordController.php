<?php

namespace App\Http\Controllers\Password;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Password;
use Response;

class PasswordController extends Controller
{
    public function home()
    {
      if (!$user = Auth::user()) {
        return redirect('login');
      }
      $passwords = Password::where([
        'user_id' => Auth::user()->id
      ])
      ->get();

      return view('home.home', ['passwords' => $passwords]);
    }

    public function create()
    {
      if (!$user = Auth::user()) {
        return redirect('login');
      }
      return view('password.create');
    }

    public function storePassword(Request $request)
    {
      if (!Auth::user()) {
        return redirect('login');
      }
        $request->validate([
          'password' => 'required|string',
          'web_address' => 'nullable|string',
          'login' => 'string',
          'description' => 'string',
          ]);
          if (empty($request->user_id)) {
            $request->user_id = Auth::user()->id;
          }

          Password::create([
              'password' => PasswordController::encrypt($request->password),
              'login' => $request->login,
              'web_address' => $request->web_address,
              'description' => $request->description,
              'user_id' => $request->user_id
          ]);

        return redirect('home');
    }

   public function decryptPassword($id)
    {
      if (!$user = Auth::user()) {
        return response::json(['Permission denied.']);
      }
      $password = Password::where([
        'user_id' => Auth::user()->id
      ])
      ->where([
        'id' => $id
      ])
      ->first();

      $decryptedPassword = PasswordController::decrypt($password->password);
      return response::json($decryptedPassword);
    }

   public function delete($id)
    {
      if (!$user = Auth::user()) {
        return response::json(['Permission denied.']);
      }
      $password = Password::where([
        'user_id' => Auth::user()->id
      ])
      ->where([
        'id' => $id
      ])
      ->first();

      $password->delete();

      return redirect('home');
    }

    public static function decrypt($value) {
       return openssl_decrypt($value, "AES-128-ECB", 'random string');
    }

    public static function encrypt($value) {
       return openssl_encrypt($value, "AES-128-ECB", 'random string');
    }
}
