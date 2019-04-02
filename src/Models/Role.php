<?php

namespace TCG\Voyager\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        $userModel = Voyager::modelClass('User');

        return $this->belongsToMany($userModel, 'user_roles')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(Voyager::modelClass('Permission'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('available', function (Builder $builder) {
            //$id = Auth::user()->role_id;
            //$builder->where('id', '<=', $id);
            if (Auth::user() != null) {
                $rang = Auth::user()->getrole->rang;
                $builder->where('rang', '>=', $rang);
            }
        });

    }
}
