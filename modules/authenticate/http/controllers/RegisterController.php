<?php namespace Modules\Authenticate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Modules\Authenticate\Http\Requests\RegisterFormRequest;
use Modules\Authenticate\Repositories\Auth;

class RegisterController extends Controller {

    protected $messageBag;
    protected $auth;
    /**
     * RegisterController constructor.
     */
    public function __construct(
        MessageBag $messageBag, Auth $auth)
    {
        $this->messageBag = $messageBag;
        $this->auth = $auth;
    }

    public function index(){
        return view('authenticate::register');
    }

    public function register(RegisterFormRequest $request)
    {
        // The incoming request is valid...
        $data = $request->toArray();

        $result = $this->auth->register($data);

        if ($result[0] === true){
            $activation = $result[1];
            // send email
            $this->sendActivationMail([]);

            // redirect to home
            return redirect()->route('home');
        }

        $this->messageBag->add('error', $result[1]);
        return redirect()->route('auth.register')->withErrors($this->messageBag);
    }

    public function sendActivationMail(array $data)
    {

    }


    /**
     * @param $id
     * @param $code
     */
    public function activateLink($id, $code){
        $result = $this->auth->activate($id, $code);

        if ($result[0] === true){

            // redirect to
        }

        $this->messageBag->add('error', $result[1]);
        // redirect to
    }
}