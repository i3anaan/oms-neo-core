<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = "fees";

    // Relationships..
    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'fee_users', 'fee_id', 'user_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $fees = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $fees = $fees->where('name', 'LIKE', "%".$search['name']."%");
        }

        if(isset($search['availability_from']) && !empty($search['availability_from'])) {
            $fees = $fees->where('availability', '>=', $search['availability_from']);
        }

        if(isset($search['availability_to']) && !empty($search['availability_to'])) {
            $fees = $fees->where('availability', '<=', $search['availability_to']);
        }

        if(isset($search['availability_unit']) && !empty($search['availability_unit'])) {
            $fees = $fees->where('availability_unit', $search['availability_unit']);
        }

        if(isset($search['price_from']) && !empty($search['price_from'])) {
            $fees = $fees->where('price', '>=', $search['price_from']);
        }

        if(isset($search['price_to']) && !empty($search['price_to'])) {
            $fees = $fees->where('price', '<=', $search['price_to']);
        }

        if(isset($search['currency']) && !empty($search['currency'])) {
            $fees = $fees->where('currency', $search['currency']);
        }

        if(isset($search['mandatory']) && !empty($search['mandatory'])) {
            switch ($search['mandatory']) {
                case '1':
                    $fees = $fees->whereNotNull('is_mandatory');
                    break;
                case '2':
                    $fees = $fees->whereNull('is_mandatory');
                    break;
            }
        }
        // END filters..

        if($onlyTotal) {
            return $fees->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                case 'availability':
                case 'availability_unit':
                    $fees = $fees->orderBy($search['sidx'], $search['sord']);
                    break;
                case 'price':
                    $fees = $fees->orderBy($search['sidx'], $search['sord'])->orderBy('currency', $search['sord']);
                    break;

                default:
                    $fees = $fees->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $fees = $fees->take($limit)->skip($from);
        }

        return $fees->get();
    }
}
