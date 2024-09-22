<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\ChildQuery;
use App\Http\Requests\Child\ChildRequest;
use App\Http\Resources\ChildResource;
use App\Http\Resources\ChildLightResource;
use App\Models\Child;
use App\Models\ChildInformation;
use App\Models\ChildrenClass;
use App\Scopes\ChildCancelScope;
use App\Models\ChildrenClassPeriod;
use App\Models\ContactBookTypePeriod;
use App\Services\QrService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class ChildController extends BaseController
{
    public function register(ChildRequest $request, QrService $qrService)
    {
        $user = auth()->user();

        $data = $request->validated();
        if (empty($data['password'])) {
            return response()->json(['message' => 'Password is required'], 422);
        }
        // $child = Child::where(['email' => $data['email']])->first();
        // if ($child) {
        //     return response()->json(['message' => trans('This email is already registered')]);
        // }
        if ($data['type'] === 1 || $data['type'] === 2)
        {
            return response()->json(['message' => 'Please input company name'], 422);
        }

        $data['password'] = Crypt::encryptString($data['password']);
        $child = new Child($data);
        $child->office_id = $user->office_id;

        if (empty($data['class_id']))
        {
            $birthday = Carbon::parse($data['birthday']);
            $diff_in_months = $birthday->diffInMonths(Carbon::now());
            $year = floor($diff_in_months / 12);
            switch($year) {
                case 0:
                    $classId = ChildrenClass::AGE_0; break;
                case 1:
                    $classId = ChildrenClass::AGE_1; break;
                case 2:
                    $classId = ChildrenClass::AGE_2; break;
                case 3:
                    $classId = ChildrenClass::AGE_3; break;
                case 4:
                    $classId = ChildrenClass::AGE_4; break;
                case 5:
                    $classId = ChildrenClass::AGE_5; break;
                default:
                    $classId = ChildrenClass::AGE_5; break;
            }
            $child->class_id = $classId;
        }
        $child->save();

        $qr = "LK-CHILDREN-" . bcrypt($child->id);

        $child->qr = $qr;
        $child->save();

        $childInfo = new ChildInformation($data);
        $child->child_info()->save($childInfo);
        $qrService->getChildQrImageUri($child);

        ChildrenClassPeriod::appendClass($child->id, $child->class_id, $child->admission_date);
        ContactBookTypePeriod::appendType($child->id, ContactBookTypePeriod::typeByBirthday($child->birthday), $child->admission_date);

        return $this->sendResponse(new ChildResource($child));
    }

    public function update(Child $child, ChildRequest $request, QrService $qrService)
    {
        $user = auth()->user();
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Crypt::encryptString($data['password']);
        } else if ($data['password'] === null) {
            unset($data['password']);
        }
        // $existing = Child::where(['email' => $data['email']])->first();
        // if ($existing && $existing->id !== $child->id) {
        //     return response()->json(['message' => trans('This email is already registered')]);
        // }

        $beforeClassID = $child->class_id;

        $child->fill($data);

        if (empty($data['class_id']))
        {
            $birthday = Carbon::parse($data['birthday']);
            $diff_in_months = $birthday->diffInMonths(Carbon::now());
            $year = floor($diff_in_months / 12);
            switch($year) {
                case 0:
                    $classId = ChildrenClass::AGE_0; break;
                case 1:
                    $classId = ChildrenClass::AGE_1; break;
                case 2:
                    $classId = ChildrenClass::AGE_2; break;
                case 3:
                    $classId = ChildrenClass::AGE_3; break;
                case 4:
                    $classId = ChildrenClass::AGE_4; break;
                case 5:
                    $classId = ChildrenClass::AGE_5; break;
                default:
                    $classId = ChildrenClass::AGE_5; break;
            }
            $child->class_id = $classId;
        }
        if (!$child->qr)
        {
            $child->qr = "LK-CHILDREN-" . bcrypt($child->id);
        }
        $child->save();

        $childInfo = $child->child_info;
        $inforChanged = $this->isChildInforChanged($childInfo, $data);

        if ($childInfo && $inforChanged)
        {
            $childInfo->end_date = Carbon::now()->subDays(1);
            $childInfo->save();
        }

        if ($inforChanged)
        {
            $childInfo = new ChildInformation(['child_id' => $child->id]);
            $childInfo->fill($data);
            $childInfo->start_date = Carbon::now();
            $childInfo->save();
        }

        $child->refresh();
        $qrService->getChildQrImageUri($child);

        if ($beforeClassID != $child->class_id) {
            $now = Carbon::now();

            ChildrenClassPeriod::appendClass($child->id, $child->class_id, $now);
            ContactBookTypePeriod::appendType($child->id, ContactBookTypePeriod::typeByClassID($child->class_id), $now);
        }

        return $this->sendResponse(new ChildResource($child));
    }

    private function isChildInforChanged($childInformation, $data)
    {
        if (!$childInformation) return true;
        if ($childInformation->name !== $data['name']) return true;
        if ($childInformation->type !== $data['type']) return true;
        if ($childInformation->company_name !== $data['company_name']??null) return true;
        if ($childInformation->free_of_charge !== $data['free_of_charge']??null) return true;
        if ($childInformation->certificate_of_payment !== $data['certificate_of_payment']??null) return true;
        if ($childInformation->certificate_expiration_date !== $data['certificate_expiration_date']??null) return true;
        if ($childInformation->tax_exempt_household !== $data['tax_exempt_household']??null) return true;
        if ($childInformation->remarks !== $data['remarks']??null) return true;
        if ($childInformation->certification_type !== $data['certification_type']??null) return true;
    }

    public function retrieve(Child $child, QrService $qrService)
    {
        $qrService->getChildQrImageUri($child);
        return $this->sendResponse(new ChildResource($child));
    }
    public function delete(Child $child)
    {
        $child->delete();
        return $this->sendResponse([]);
    }
    public function cancel(Child $child)
    {
        if($child->canceled_at!=null)
            return abort(422, "既にキャンセルされています");
        $child->canceled_at = Carbon::now();
        $child->save();
        return $this->sendResponse([]);
    }
    public function list(ChildQuery $request)
    {
        $user = auth()->user();

        $data = $request->validated();

        $qb = Child::where(['office_id' => $user->office_id]);
        if (!empty($data['query']))
        {
            $search = $data['query'];
            $qb->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('child_class', function($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
        if (!empty($data['class_id']))
        {
            $qb->where(['class_id' => $data['class_id']]);
        }
        // TODO search plan_registered
        if (isset($data['plan_registered']))
        {
            $qb->where(['plan_registered' => $data['plan_registered']]);
        }

        $qb->withoutGlobalScopes([ChildCancelScope::class]);
        if (empty($data['all'])) {
            $qb->where(function ($query1) use ($data) {
                if (!empty($data['canceled']))
                {
                    $query1->whereNotNull('canceled_at');
                    if (!empty($data['exited'])) {
                        $query1->orWhere('exit_date', '<', Carbon::now()->format('Y-m-d'));
                        if (!empty($data['planed'])) {
                            $query1->orwhereNull('admission_date')->orWhere('admission_date', '>', Carbon::now()->format('Y-m-d'));
                        }else {
                            $query1->Where(function ($query) {
                                $query->Where('admission_date', '<=', Carbon::now()->format('Y-m-d'));
                            });
                        }
                    } else {
                        if (!empty($data['planed'])) {
                            $query1->orwhereNull('admission_date')->orWhere('admission_date', '>', Carbon::now()->format('Y-m-d'));
                        }else {
                        }
                    }

                } else {
                    $query1->whereNull('canceled_at');
                    if (!empty($data['exited'])) {
                        $query1->Where('exit_date', '<', Carbon::now()->format('Y-m-d'));
                        if (!empty($data['planed'])) {
                            $query1->orwhereNull('admission_date')->orWhere('admission_date', '>', Carbon::now()->format('Y-m-d'));
                        }else {
                            $query1->Where(function ($query) {
                                $query->Where('admission_date', '<=', Carbon::now()->format('Y-m-d'));
                            });
                        }
                    } else {
                        $query1->Where(function ($query) {
                            $query->Where('exit_date', '>=', Carbon::now()->format('Y-m-d'))->orwhereNull('exit_date');
                        });
                        if (!empty($data['planed'])) {
                            $query1->Where(function ($query){
                                $query->whereNull('admission_date')->orWhere('admission_date', '>', Carbon::now()->format('Y-m-d'));
                            });
                        }else {
                            $query1->Where(function ($query) {
                                $query->Where('admission_date', '<=', Carbon::now()->format('Y-m-d'));
                            });
                        }
                    }
                }
            });

        }
        $sort = $data['sort']??'number';
        $dir = $data['dir']??'asc';
        $qb->orderBy($sort, $dir);

        return $this->sendResponse(ChildLightResource::collection($qb->get()));
    }
}
