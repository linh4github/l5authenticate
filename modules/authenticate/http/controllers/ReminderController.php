<?php

namespace Modules\Authenticate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Modules\Authenticate\Models\Repositories\Contracts\ReminderRepositoryInterface;
use Modules\Authenticate\Models\Repositories\Contracts\UserRepositoryInterface;
use Validator;

class ReminderController extends Controller
{
    protected $reminder;
    protected $user;
    protected $messageBag;

    /**
     * ReminderController constructor.
     */
    public function __construct(
        ReminderRepositoryInterface $reminder,
        UserRepositoryInterface $user, MessageBag $messageBag)
    {
        $this->user       = $user;
        $this->reminder   = $reminder;
        $this->messageBag = $messageBag;
    }

    public function index(){
        return view('authenticate::reminder_email');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function remindPasswordByEmail(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email'
        ]);

        if (! $validator->fails()){

            $user = $this->user->findByCredentials($data);
            $reminder = $this->reminder->create($user->id);


            // send email

            return redirect()->route('home');
        }

        $this->messageBag->add('error', $validator->errors()->messages());

        return redirect()->route('auth.reminder')->withErrors($this->messageBag);
    }

    /**
     * Link to reset password
     * @param $id
     * @param $code
     */
    public function resetPasswordLink($id, $code)
    {
        $status = $this->reminder->checkLinkStatus($id, $code);

        switch ($status){
            // link not valid
            case 0:
                app()->abort(404);
                break;
            // completed
            case 1:
                // redirect to login page
                break;
            // expired
            case 2:
                // redirect to resend reset request page
                break;
            // ready
            case 3:
                $reminder = $this->reminder->complete($id, $code);

                if ( $reminder ){
                    // create new password for user
                    $newPassword = $this->user->resetPassword($id);

                    // send email to inform
                }
                break;
            default:
                break;
        }
    }

}