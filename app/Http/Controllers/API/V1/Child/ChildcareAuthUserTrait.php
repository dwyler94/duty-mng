<?php

namespace App\Http\Controllers\API\V1\Child;

trait ChildcareAuthUserTrait
{
    public function getUser()
    {
        $user = auth('api')->user();
        if ($user) {
            return $user;
        }
        $child = auth('childcare')->user();
        if ($child) {
            return $child;
        }
        return null;
    }
}
