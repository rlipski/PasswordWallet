<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Validator;

class RegisterController extends Controller
{
  public function register()
  {
    return view('auth.register');
  }

  public function storeUser(Request $request)
  {
      $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|confirmed',
          'password_confirmation' => 'required',
      ]);


      if ( isset($request->isPasswordKeptAsHash) ) {//SHA512
        $salt = $this->generateRandomString(16);
        $passwordHash = hash("sha512", $salt . config('Constans.PEPPER') . $request->password);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'salt' => $salt,
            'password' => $passwordHash,
            'is_password_kept_as_hash' => '1'
        ]);

      } else {//HMAC
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash_hmac('sha512', $request->password, 'random key'),
            'is_password_kept_as_hash' => '0',
        ]);
      }

      return redirect('home');
  }

  public function editMasterPassword()
  {
    if (!Auth::user()) {
      return redirect('login');
    }
    return view('auth.editMasterPassword');
  }

  public function saveMasterPassword(Request $request)
  {
      $user = Auth::user();

      if ($user->is_password_kept_as_hash == 1) {//SHA512
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) use ($user) {
            return hash_equals($user->password, hash("sha512", $user->salt . config('Constans.PEPPER') . $value));
        },
            'Current password is wrong!'
        );
      } else {
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) use ($user) {
            return hash_equals($user->password, hash_hmac('sha512', $value, 'random key'));
        },
            'Current password is wrong!'
        );
      }

      $request->validate([
          'old_password' => 'required|string|old_password:' . $user->password,
          'password' => 'required|string|confirmed',
          'password_confirmation' => 'required|same:password',
      ]);

      if ($user->is_password_kept_as_hash == 1 ) {//SHA512
        $salt = $this->generateRandomString(16);
        $passwordHash = hash("sha512", $salt . config('Constans.PEPPER') . $request->password);
        $user->salt = $salt;
        $user->password = $passwordHash;
        $user->save();

      } else {//HMAC
        $user->password = hash_hmac('sha512', $request->password, 'random key');
        $user->save();
      }
      return redirect('home');
  }

  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

}