<?php
/*
 * Copyright (c) 7/12/21, 7:18 PM.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

namespace App\Models;

use App\Constant\RoleConstant;
use App\Helpers\HelperTrait;
use App\Models\Contracts\BaseModelContract;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel
    extends Model
    implements BaseModelContract
{
    protected $hidden = ["created_at", "updated_at"];
    use HelperTrait;

    /**
     * handle pre & post action
     */
    protected static function boot()
    {
        parent::boot();
    }

    public function toArray()
    {
        if(auth()->check() && auth()->user()->hasRole([RoleConstant::SUPER_ADMIN, RoleConstant::ADMIN])){
            $this->setAttributeVisibility();
        }
        return parent::toArray();
    }

    public function setAttributeVisibility()
    {
        $this->makeVisible(array_merge($this->fillable, $this->appends, ['created_at', 'updated_at']));
    }
}
