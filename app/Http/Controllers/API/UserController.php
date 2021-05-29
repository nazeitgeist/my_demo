<?php

namespace App\Http\Controllers\API;

use Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Mail; 
use DB; 
use Illuminate\Support\Str;
use Carbon\Carbon; 
use Validator;
use App\Mail\MyDemoMail;

class UserController extends Controller
{
    /**
     * Register
     */
    public function register(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $success = true;
            $message = 'User register successfully';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $success = true;
            $message = 'User login successfully';
        } else {
            $success = false;
            $message = 'Unauthorised';
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * Logout
     */
    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = 'Successfully logged out';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

	public function myDemoMail($email, $token)
    {
        $myEmail = $email;
   
        $details = [
            'title' => 'Forget Password Email Demo',
            'url' => url("/reset-password/{$token}")
        ];
  
        Mail::to($myEmail)->send(new MyDemoMail($details));
    }

	public function forgotPassword(Request $request)
    {
		$success = false;
  
		$data = $request->all();
		$rules = [
			'email' => 'required|email|exists:users',
		];

		$validator = Validator::make($data, $rules);
		if ($validator->passes()) {
			
			//TODO Handle your data
			$token = Str::random(64);
			try {
				DB::table('password_resets')->insert([
					'email' => $request->email, 
					'token' => $token, 
					'created_at' => Carbon::now()
				]);
				$success = true;
			} catch (\Illuminate\Database\QueryException $ex) {
				$success = false;
				$message = $ex->getMessage();
			}
	  
			if($success){
				$this->myDemoMail($request->email, $token);
				/*Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
					$message->to($request->email);
					$message->subject('Reset Password');
				});*/
				$message = "Email sent.";
			}
			

		} else {
			//TODO Handle your error
			$response = [
				'success' => false,
				'message' => $validator->errors()->all()
			];
			return response()->json($response);
		}

		// response
		$response = [
            'success' => $success,
            'message' => $message,
        ];

		return response()->json($response);
    }

	public function submitResetPasswordForm(Request $request)
    {
		$success = false;
  
		$data = $request->all();
		$rules = [
			'email' => 'required|email|exists:users',
			'password' => 'required|string|min:6|confirmed',
			'password_confirmation' => 'required'
		];

		$validator = Validator::make($data, $rules);
		if ($validator->passes()) {
			//TODO Handle your data
		} else {
			//TODO Handle your error
			$response = [
				'success' => false,
				'message' => $validator->errors()->all()
			];
			return response()->json($response);

		}

		$updatePassword = DB::table('password_resets')
							->where([
							'email' => $request->email, 
							'token' => $request->token
							])
							->first();

		if(!$updatePassword){
			return back()->withInput()->with('error', 'Invalid token!');
		}
  
		$user = User::where('email', $request->email)
					->update(['password' => Hash::make($request->password)]);
		if($user){
			DB::table('password_resets')->where(['email'=> $request->email])->delete();
			$success = true;
			$message = "Password changed. Please login again.";
		}
	
        // response
		$response = [
			'success' => $success,
			'message' => $message,
		];
		return response()->json($response);
    }
}