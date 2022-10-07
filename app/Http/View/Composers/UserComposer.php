<?php

namespace App\Http\View\Composers;

use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class UserComposer
{
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $response = [];


        $view->with([
            'response' => $response,
        ]);
    }
}
