<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BlogDataTable;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Repositories\BlogRepository;
use App\Http\Controllers\AppBaseController;
use Response;
use Laracasts\Flash\Flash;
use Throwable;

class BlogController extends AppBaseController
{
    /** @var  $repository */
    public $repository;

    public function __construct(BlogRepository $blogRepo)
    {
        $this->repository = $blogRepo;
        $this->getEntity('blogs');
    }

    /**
     * Display a listing of the Blog.
     *
     * @param BlogDataTable $blogDataTable
     * @return Response
     */
    public function index(BlogDataTable $blogDataTable)
    {
        return $blogDataTable->render($this->entity['view'] . '.index', ['entity' => $this->entity]);
    }


    /**
     * Store a newly created Blog in storage.
     *
     * @param CreateBlogRequest $request
     *
     * @return Response
     */
    public function store(CreateBlogRequest $request)
    {
        try {
            $input = $request->all();

            $blog = $this->repository->create($input);
            Flash::success($this->entity['singular'] . ' saved successfully.');

            return redirect(route($this->entity['url'] . '.index'));
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }



    /**
     * Show the form for editing the specified Blog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $blog = $this->repository->find($id, ['*'], true);

            if (empty($blog)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            return view($this->entity['view'] . '.edit', ['blog' => $blog, 'entity' => $this->entity]);
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
    public function update($id, UpdateBlogRequest $request)
    {
        try {
            $blog = $this->repository->find($id, ['*'], true);

            if (empty($blog)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            $blog = $this->repository->update($request->all(), $id, true);

            Flash::success($this->entity['singular'] . ' updated successfully.');

            return redirect(route($this->entity['url'] . '.index'));
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }
}
