<?php

namespace App\Traits;

trait ModelState
{
    public function getCurrentState()
    {
        $states = config('constants.state.data', []);
        $color = config('constants.state.color-class', []);
        $state = 'active';
        if (isset($this->is_deleted) && $this->is_deleted == 1) {
            $state = 'deleted';
        } elseif (isset($this->deleted_at) && $this->deleted_at != null) {
            $state = 'archived';
        }
        return ['label' => $states[$state], 'color' => $color[$state], 'state' => $state];
    }

    public function scopeOfAll($query)
    {
        return $query->withTrashed();
    }

    public function scopeOfActive($query)
    {
        return $query->withoutTrashed();
    }

    // public function scopeOfArchived($query)
    // {
    //     return $query->orWhere(self::getTable() . '.is_deleted', false)->whereNotNull(self::getTable() . '.deleted_at');
    // }

    public function scopeOfInactive($query)
    {
        return $query->orWhere(self::getTable() . '.is_deleted', false)->whereNotNull(self::getTable() . '.deleted_at');
    }

    public function scopeOfDeleted($query)
    {
        return $query->orWhere(self::getTable() . '.is_deleted', true)->whereNotNull(self::getTable() . '.deleted_at');
    }

    public function makeArchive()
    {
        $this->delete();
    }

    public function makeDelete()
    {
        $this->is_deleted = true;
        $this->save();

        $this->delete();
    }
}
