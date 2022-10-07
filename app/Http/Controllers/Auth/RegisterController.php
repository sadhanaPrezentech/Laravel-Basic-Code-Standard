<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FunctionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepository;
use App\Traits\RedirectTo;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

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
    use RedirectTo;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    public $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');
        $this->repository = $userRepository;
    }

    protected function create($type = 'admin')
    {
        return view('auth.register', compact('type'));
    }

    protected function store(CreateUserRequest $request)
    {
        try {
            $role = config('constants.user_role.' . $request->user_type);
            $input = $request->all();
            $input['email_verified_at'] = FunctionHelper::today(false, true, true);
            $input['password'] = Hash::make($request->password);
            $user = $this->repository->create($input);

            // assign role
            if (!empty($role)) {
                $user->assignRole($role);
            }

            return redirect()->route(
                'login'

            );
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->withInput();
    }
}
