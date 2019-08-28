<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\SystemBaseController;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Sentinel;
use Reminder;
use Exception;
use Swift_TransportException;

class ResetPasswordController extends SystemBaseController {


    /**
     * ResetPasswordController constructor.
     */
    public function __construct()
    {
    }

    public function processForgotPassword(Request $request) {
        try {
            $email = $request->email;
            $user = User::byEmail($email);
            if (count($user) == 0) {
                Flash::success(trans('passwords.sent'));
                return redirect()->back();
            }

            //$sentinalUser = Sentinel::findById($user->id);

            $reminder = Reminder::exists($user) ?: Reminder::create($user);

            $this->sendPasswordResetReminderEmail($user, $reminder->code);

            Flash::success(trans('passwords.sent'));
            return redirect()->back();
        } catch (Swift_TransportException $ex) {
            Flash::warning($ex->getMessage() . ". Email not sent to " . $request->email . '!');
            return redirect()->back();
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect()->back();
        }
    }

    public function resetPassword($email, $resetCode) {
        try {
            $user = User::byEmail($email);
            // dd($user);
            if (count($user) == 0) {
                Flash::warning(trans('passwords.user'));
                return redirect('/login');
            }

            //$sentinalUser = Sentinel::findById($user->id);
            $reminder = Reminder::exists($user);

            if ($reminder = Reminder::exists($user) && $reminder->code == $resetCode) {
                return view('auth.reset_password', compact('email', 'resetCode'));
            } else {
                Flash::warning(trans('passwords.token'));
                return redirect('/login');
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect('/login');
        }
    }

    public function processResetPassword(Request $request, $email, $resetCode) {
        try {
            $user = User::byEmail($email);
            // dd($user);
            // validate user email
            if (count($user) == 0) {
                Flash::warning(trans('passwords.user'));
                return redirect('/login');
            }
            //$sentinalUser = Sentinel::findById($user->id);
            $reminder = Reminder::exists($user);
            // validate reset token
            if ($reminder = Reminder::exists($user) && $reminder->code == $resetCode) {
                $rules = [
                    'password' => 'confirmed|required',
                    'password_confirmation' => 'required'
                ];
                
                // validate form values
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                // reset the password
                Reminder::complete($user, $resetCode, $request->password);
                // redirect back to login page
                Flash::success(trans('passwords.reset'));
                return redirect('/login');
                
            } else {
                Flash::warning(trans('passwords.token'));
                return redirect('/login');
            }
        } catch (Exception $ex) {
            Flash::warning($ex->getMessage());
            return redirect('/login');
        }
    }

    private function sendPasswordResetReminderEmail($user, $reminderCode) {
        $company_name = Setting::where('setting_key', 'company_name')->first()->setting_value;
        $company_email = Setting::where('setting_key', 'company_email')->first()->setting_value;
        $subject = Setting::where('setting_key', 'reset_password_email_subject')->first()->setting_value;
        $subject = str_replace('{companyName}', $company_name, $subject);
        $resetPasswordUrl = url('/reset-password/' . $user->email . '/' . $reminderCode);

        Mail::send('emails.reset_password', ['subject' => $subject, 'user' => $user, 'companyName' => $company_name, 'resetPasswordUrl' => $resetPasswordUrl], function ($message) use ($user, $company_name, $company_email, $subject) {
            $message->from($company_email, $company_name);
            $message->to($user->email);
            $message->cc(['info@virttutor.com']);
            $headers = $message->getHeaders();
            $message->setContentType('text/html');
            $message->setSubject($subject);
        });
    }

}
