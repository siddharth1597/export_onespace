<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->after(function ($validator) use ($data) {
            $admin_password = User::select('password')->where('role', 'super_admin')->first();
            if (!isset($data['admin_password']) || !Hash::check($data['admin_password'], $admin_password->password)) {
                $validator->errors()->add('admin_password', __('The provided Super Admin Password does not match. Try Again!'));
            }
        });
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'google2fa_secret' => $data['google2fa_secret'],
        ]);
    }

    public function register(Request $request)
    {

        // delete the temp_qrcode file
        if (config('app.env') == 'live') {
            File::delete(public_path() . '/temp_qrcode.png');
        }

        //Validate the incoming request using the already included validator method
        $this->validator($request->all())->validate();

        // Initialise the 2FA class
        $google2fa = new Google2FA();

        // Save the registration data in an array
        $registration_data = $request->all();

        // Add the secret key to the registrationdata
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        $request->session()->put('registration_data', $registration_data);

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        if (config('app.env') == 'live') {
            $filename_path = public_path() . "/temp_qrcode.png";
            $base64_string = str_replace('data:image/png;base64,', '', $QR_Image);
            $base64_string = str_replace(' ', '+', $base64_string);
            $decoded = base64_decode($base64_string);
            file_put_contents($filename_path, $decoded);
            $QR_Image = '/public/temp_qrcode.png';
        }

        // Pass the QR barcode image to our view
        return view('google2fa.register', ['QR_Image' => $QR_Image]);
    }

    public function completeRegistration(Request $request)
    {
        // add the session data back to the request input
        $all_data = $request->merge($request->session()->get('registration_data'));

        // Verify secret code
        $google2fa = new Google2FA();
        $secret = $request->input('secret');
        $user_details = $all_data->request->all();
        $valid = $google2fa->verifyKey($user_details['google2fa_secret'], $secret);

        if ($valid) {
            // Call the default laravel authentication
            $user = $this->create($all_data->request->all());
            event(new Registered($user));

            $this->guard()->login($user);

            return $this->registered($request, $user) ?: redirect($this->redirectPath());
        } else {
            // delete the temp_qrcode file
            if (config('app.env') == 'live') {
                File::delete(public_path() . '/temp_qrcode.png');
            }

            // Generate the same QR image again if user enter wrong otp.
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user_details['email'],
                $user_details['google2fa_secret']
            );

            if (config('app.env') == 'live') {
                $filename_path = public_path() . "/temp_qrcode.png";
                $base64_string = str_replace('data:image/png;base64,', '', $QR_Image);
                $base64_string = str_replace(' ', '+', $base64_string);
                $decoded = base64_decode($base64_string);
                file_put_contents($filename_path, $decoded);
                $QR_Image = '/public/temp_qrcode.png';
            }

            return view('google2fa.register', ['QR_Image' => $QR_Image]);
        }
    }
}
