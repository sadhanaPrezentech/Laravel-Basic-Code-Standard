<?php

namespace App\Repositories;

use App\Models\Blog;

/**
 * Class BlogRepository
 * @package App\Repositories
 * @version March 15, 2021, 7:37 am UTC
 */

class BlogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Blog::class;
    }
}
