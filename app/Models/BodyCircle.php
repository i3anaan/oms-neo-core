<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyCircle extends Model
{
    protected $table = "body_circles";
    protected $guarded = ["id"];

    // Relationships..
    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function memberships() {
    	return $this->belongsToMany('App\Models\BodyMembership', 'body_membership_circles', 'circle_id', 'membership_id');
    }

    public function globalCircle() {
        return $this->belongsTo('App\Models\GlobalCircle');
    }
}
