<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRepository;
use Response;
use Laracasts\Flash\Flash;
use Throwable;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FunctionHelper;

class UserController extends AppBaseController
{
    /** @var  $repository */
    public $repository;

    public function __construct(UserRepository $userRepo)
    {
        $this->repository = $userRepo;
        $this->getEntity('users');
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render($this->entity['view'] . '.index', ['entity' => $this->entity]);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $input = $request->all();
            // dd($input);
            $role = $input['role'];
            $input['password'] = Hash::make($input['password']);

            $input['email_verified_at'] = FunctionHelper::today(false, true, true);
            $user = $this->repository->create($input);
            // assign role
            if (!empty($role)) {
                $user->assignRole($role);
            }
            Flash::success($this->entity['singular'] . ' saved successfully.');
            return redirect(route($this->entity['url'] . '.index'));
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }


    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $user = $this->repository->find($id, ['*'], true);

            if (empty($user)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            } else {
                $user->email_verified_at = $user->email_verified_at != null ? 1 : null;
                return view($this->entity['view'] . '.edit', ['user' => $user, 'entity' => $this->entity]);
            }
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified Blog in storage.
     *
     * @param  int              $id
     * @param UpdateBlogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        try {
            $user = $this->repository->find($id, ['*'], true);

            if (empty($user)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            } else {
                $input = $request->all();
                if (!empty($input['password'])) {
                    $input['password'] = Hash::make($input['password']);
                } else {
                    unset($input['password']);
                }
                if ($user->email_verified_at == null) {
                    $input['email_verified_at'] = FunctionHelper::today(false, true, true);
                } else {
                    unset($input['email_verified_at']);
                }

                $user = $this->repository->update($input, $id, true);


                Flash::success($this->entity['singular'] . ' updated successfully.');

                return redirect(route($this->entity['url'] . '.index'));
            }
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }
}
