<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\RoleRepository;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Throwable;

class RoleController extends AppBaseController
{
    public function __construct(RoleRepository $roleRepo)
    {
        $this->repository = $roleRepo;
        $this->getEntity();
    }

    /**
     * Display a listing of the Role.
     *
     * @param RoleDataTable $roleDataTable
     * @return Response
     */
    public function index(RoleDataTable $roleDataTable)
    {
        return $roleDataTable->render($this->entity['view'] . '.index', ['entity' => $this->entity]);
    }

    /**
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    public function create()
    {
        try {
            return view($this->entity['view'] . '.create', ['entity' => $this->entity]);
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param CreateRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        try {
            $input = $request->all();
            $input['name'] = Str::slug($input['title'], '-');

            $role = $this->repository->create($input);

            $permissions = isset($input['permissions']) && !empty($input['permissions'][$role->guard_name]) ? $input['permissions'][$role->guard_name] : [];
            $role->syncPermissions($permissions);

            Flash::success($this->entity['singular'] . ' saved successfully.');
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
        return redirect(route($this->entity['url'] . '.index'));
    }

    /**
     * Display the specified Role.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $role = $this->repository->find($id, ['*'], true);

            if (empty($role)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            return view($this->entity['view'] . '.show', ['role' => $role, 'entity' => $this->entity]);
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified Role.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $role = $this->repository->find($id, ['*'], true);

            if (empty($role)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            return view($this->entity['view'] . '.edit', ['role' => $role, 'entity' => $this->entity]);
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified Role in storage.
     *
     * @param  int              $id
     * @param UpdateRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleRequest $request)
    {
        try {
            $role = $this->repository->find($id, ['*'], true);

            if (empty($role)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            $input = $request->all();
            $role = $this->repository->update($input, $id, true);

            $permissions = isset($input['permissions']) && !empty($input['permissions'][$role->guard_name]) ? $input['permissions'][$role->guard_name] : [];
            $role->syncPermissions($permissions);

            Flash::success($this->entity['singular'] . ' updated successfully.');
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
        return redirect(route($this->entity['url'] . '.index'));
    }
}
