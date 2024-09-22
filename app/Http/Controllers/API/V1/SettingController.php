<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;

class SettingController extends BaseController
{
    public function get()
    {
        $setting = Setting::first();
        return $this->sendResponse($setting);
    }

    public function save(SettingRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $setting = Setting::first();
        $setting->fill($data);
        $setting->update_user_id = $user->id;
        $setting->save();

        return $this->sendResponse($setting);
    }
}
