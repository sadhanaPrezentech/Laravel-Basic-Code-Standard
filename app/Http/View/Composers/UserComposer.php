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
        $roleItems = Role::pluck('title', 'name')->toArray();
        if (in_array($view->getName(), ['users.edit', 'users.show'])) {
            $roles = $view->user->roles ?? null;
            $view->user->role = $view->user->getRoleNames();
            if (!empty($roles)) {
                $view->user->role_title = $roles->first()->title;
            }
        }
        $view->with([
            'response' => $response,
            'roleItems' => $roleItems
        ]);
    }
}
