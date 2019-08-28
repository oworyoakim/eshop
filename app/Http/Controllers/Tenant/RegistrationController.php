<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\TenantBaseController;
use App\Models\User;
use App\Models\Tenant\BusinessSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Sentinel;
use Activation;
use Exception;
use Swift_TransportException;

/**
 * Description of RegistrationController
 *
 * @author Yoakim
 */
class RegistrationController extends TenantBaseController {


    /**
     * RegistrationController constructor.
     */
    public function __construct()
    {
    }

    public function index() {
        try {
            if (Sentinel::check()) {
                return redirect('admin/dashboard');
            }
            return view('register');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function processRegister(Request $request) {
        try {
            //dd($request->all());
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'confirmed|required',
                'password_confirmation' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $credentials = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'password' => $request->password,
                'avatar' => 'student.png'
            ];
            // register the user
            $user = Sentinel::register($credentials);
            // create the activation
            $activation = Activation::create($user);
            // attach this user to the student role
            $role = Sentinel::findRoleBySlug('student');
            $role->users()->attach($user);

            // send notifications
            $this->sendActivationEmail($user, $activation->code);

            Flash::success(trans('login.success'));
            return redirect('login');
        } catch (Swift_TransportException $ex) {
            Flash::warning($ex->getMessage() . ". Email not sent to " . $request->email . '!');
            return redirect()->back()->withInput()->with(["error" => $ex->getMessage() . " Email not sent to " . $request->email]);
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back()->withInput()->with(["error" => $ex]);
        }
    }

    public function activate($email, $activationCode) {
        try {
            $user = User::byEmail($email);
            //$sentinalUser = Sentinel::findById($user->id);
            if (Activation::complete($user, $activationCode)) {
                Flash::success("Your account has been successfully activated. Please login to continue!");
                return redirect('/login');
            }
            Flash::warning("Your account is already activated. Please login to continue!");
            return redirect('/login');
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect('/login');
        }
    }

    private function sendActivationEmail($user, $activationCode) {
    $company_name = BusinessSetting::where('setting_key', 'company_name')->first()->setting_value;
    $company_email = BusinessSetting::where('setting_key', 'company_email')->first()->setting_value;
    $company_otheremail = BusinessSetting::where('setting_key', 'company_otheremail')->first()->setting_value;
    $subject = BusinessSetting::where('setting_key', 'account_registration_email_subject')->first()->setting_value;
    $subject = str_replace('{companyName}', $company_name, $subject);
    $activationUrl = url('/new_account/activate/' . $user->email . '/' . $activationCode);

    Mail::send('emails.activation', ['subject' => $subject, 'user' => $user, 'companyName' => $company_name, 'activationUrl' => $activationUrl], function ($message) use ($user, $company_name, $company_email, $company_otheremail, $subject) {
        $message->from($company_email, $company_name);
        $message->to($user->email);
        $message->cc([$company_otheremail]);
        $headers = $message->getHeaders();
        $message->setContentType('text/html');
        $message->setSubject($subject);
    });
}

}
