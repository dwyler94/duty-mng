<?php

namespace App\Http\Controllers\API\V1\Android;

use App\Http\Controllers\Controller;
use App\Http\Requests\Android\ApiDeviceRequest;
use App\Http\Requests\Android\ApiLiveRequest;
use App\Http\Requests\Android\ApiStampRequest;
use App\Http\Requests\Android\ApiRetryRequest;
use App\Models\Device;
use App\Models\Monitoring;
use App\Models\Office;
use App\Models\QrTransaction;
use \Illuminate\Support\Facades\Artisan;

class ApiController extends Controller
{
    CONST API_KEY = 'HTTP_X_API_KEY';
    CONST API_SECRET = 'bfa5f68c76c7017b1b104103c453563c';

    public function device(ApiDeviceRequest $request)
    {
        if (!self::validateHttpHeader($request)) {
            return response()->json(['result' => false, 'error' => 'You are not allowed']);
        }
        $data = $request->all();

        $device = Device::where('uuid', $data['uuid'])->first();
        if (empty($device)) {
            $id = Device::create(['uuid' => $data['uuid']]);
        } else {
            $id = $device->id;
        }
        $offices = Office::where('deleted_at', null)->select('id', 'name')->orderBy('id', 'asc')->get()->toArray();

        return response()->json(['result' => true, 'device_id' => $id, 'offices' => $offices]);
    }

    public function live(ApiLiveRequest $request)
    {
        if (!self::validateHttpHeader($request)) {
            return response()->json(['result' => false, 'error' => 'You are not allowed']);
        }
        $data = $request->all();

        Monitoring::create([
            'device_id' => $data['device_id'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        return response()->json(['result' => true]);
    }

    public function stamp(ApiStampRequest $request)
    {
        $type = '';
        $error = '';


        if (!self::validateHttpHeader($request)) {
            return response()->json(['result' => false, 'error' => 'You are not allowed']);
        }
        $data = $request->all();

        if(strpos($data['data'], "LK-USER-" ) !== false){
            // ToDo: 勤怠サブシステム打刻
        } elseif(strpos($data['data'], "LK-CHILDREN-" ) !== false) {
            //QRコードリーダーの仕様で一部QR読み取り時先頭に\000026などのプリフィックスが付与されるため
            $data['data'] = strstr($data['data'], 'LK-CHILDREN-');
            Artisan::call('command:stampChildren', [
                'device_id' => $data['device_id'],
                'data' => $data['data'],
                'datetime' => date('YmdHis', strtotime($data['datetime']))
            ]);

            $qr = QrTransaction::where('qr', $data['data'])->where('ymd', date('Ymd', strtotime($data['datetime'])))->orderby('created_at', 'desc')->first();
            switch ($qr->counter) {
                case 1:
                    $type = 'commute';
                    break;
                case 2:
                    $type = 'leave';
                    break;
                case 3:
                    return response()->json(['result' => false, 'error' => '既に退園済です']);
                    break;
                case 4:
                    return response()->json(['result' => false, 'error' => '既に登園済です']);
                    break;
                default:
                    return response()->json(['result' => false, 'error' => '打刻内容エラー']);
            }

        } else {
            return response()->json(['result' => false]);
        }

        return response()->json(['result' => true, 'type' => $type, 'error' => $error]);
    }

    public function retry(ApiRetryRequest $request)
    {
        if (!self::validateHttpHeader($request)) {
            return response()->json(['result' => false, 'error' => 'You are not allowed']);
        }
        $data = $request->all();

        $results = [];
        foreach ($data['retry_items'] as $item) {
            if(strpos($item['data'], "LK-USER-" ) === 0){
                // ToDo: 勤怠サブシステム打刻
                $results[] = false;
            } elseif(strpos($item['data'], "LK-CHILDREN-" ) === 0) {
                Artisan::call('command:stampChildren', [
                    'device_id' => $data['device_id'],
                    'data' => $item['data'],
                    'datetime' => $item['datetime']
                ]);
                $results[] = true;
            } else {
                $results[] = false;
            }
        }

        return response()->json(['result' => true, 'results' => $results]);
    }

    private function validateHttpHeader($request) {
        if ($request->hasHeader(self::API_KEY)) {
            $value = $request->header(self::API_KEY);
            if (!empty($value) && ($value == self::API_SECRET)) {
                return true;
            }
        }
        return false;
    }

}
