<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\RegionMasterQuery;
use App\Http\Requests\RegionRequest;
use App\Models\Office;
use App\Models\Region;

class RegionController extends BaseController
{
    public function get(RegionMasterQuery $request)
    {
        $data = $request->validated();

        $qb = Region::with('offices:id,name,region_id');
        if (!empty($data['name']))
        {
            $qb->where('name', 'LIKE', '%' . $data['name'] . '%');
        }
        $regions = $qb->get();
        return $this->sendResponse($regions);
    }
    public function create(RegionRequest $request)
    {
        $data = $request->validated();
        $name = $data['name'];
        if (Region::where(['name' => $name])->count() > 0)
        {
            return $this->sendError(trans("すでに使用されている名前です。"));
        }
        $region = Region::create([
            'name'  =>  $data['name']
        ]);
        $region->offices()->update(['region_id' => null]);
        if (!empty($data['offices']))
        {
            Office::whereIn('id', $data['offices'])
                    ->update(['region_id' => $region->id]);
        }
        return $this->sendResponse();
    }
    public function update(Region $region, RegionRequest $request)
    {
        $data = $request->validated();
        $name = $data['name'];
        $existing = Region::where(['name' => $name])->first();
        if ($existing && $existing->id !== $region->id)
        {
            return $this->sendError(trans("すでに使用されている名前です。"));
        }

        $region->name = $data['name'];
        $region->save();

        $region->offices()->update(['region_id' => null]);
        if (!empty($data['offices']))
        {
            Office::whereIn('id', $data['offices'])
                    ->update(['region_id' => $region->id]);
        }
        return $this->sendResponse();
    }
    public function delete(Region $region)
    {
        $region->name = $region->name . "(deleted)";
        $region->save();
        $region->delete();
        return $this->sendResponse();
    }
}
