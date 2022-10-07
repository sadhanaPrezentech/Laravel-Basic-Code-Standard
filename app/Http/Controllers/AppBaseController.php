<?php

namespace App\Http\Controllers;

use App\Helpers\FunctionHelper;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
use Throwable;

/**

 * This class should be parent class for other controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public $entity;
    public $repository;

    public function getEntity($entity = null)
    {
        $this->entity = FunctionHelper::getEntity($entity);
    }
    public static function sendResponse($message, $data)
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

    /**
     * Show the form for creating a new Blog.
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
     * Show the form for editing the specified Blog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $record = $this->repository->find($id, ['*'], true);

            if (empty($record)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            return view($this->entity['view'] . '.edit', ['record' => $record, 'entity' => $this->entity]);
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }


    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $record = $this->repository->find($id, ['*'], true);

            if (empty($record)) {
                Flash::error($this->entity['singular'] . ' not found');

                return redirect(route($this->entity['url'] . '.index'));
            }

            return view($this->entity['view'] . '.show', [$this->entity['targetModel'] => $record, 'entity' => $this->entity]);
        } catch (Throwable $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified Record from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $record = $this->repository->find($id);

        if (empty($record)) {
            return $this->sendError($this->entity['view'] . ' not found', 400);
        }
        $record->is_deleted = 1;
        $record->save();
        $this->repository->forceDelete($id);
        return $this->sendResponse($this->entity['view'] . ' deleted successfully.', $record);
    }
}
