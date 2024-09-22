<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\CodeGroups;
use App\Constants\Roles;
use App\Exports\UserExport;
use App\Http\Requests\PageQuery;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Requests\UserMasterQuery;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSettingRequest;
use App\Mail\PasswordUpdated;
use App\Models\Code;
use App\Models\EmploymentStatus;
use App\Models\Office;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public function get(UserMasterQuery $request)
    {
        $data = $request->validated();
        $officeName = $data['office_name']??null;
        $response = $this->getUserQuery($data['size'], $officeName, true);
        return $this->sendResponse($response);
    }
    public function csv(Request $request)
    {
        if (!$request->has('token'))
        {
            abort(403, "You are not allowed");
        }
        $token = $request->input('token');
        $token = PersonalAccessToken::findToken($token);

        if (!$token) {
            abort(403, "You are not allowed");
        }
        $user = $token->tokenable;
        if (!$user || $user->role_id !== Roles::ADMIN) {
            abort(403, "You are not allowed");
        }
        $response = $this->getUserQuery(10000);
        $employmentStatuses = EmploymentStatus::get();
        $roles = Role::get();
        $options = Code::get();
        return Excel::download(new UserExport($response, $employmentStatuses, $roles, $options), 'users.xlsx');
    }

    public function create(UserRequest $request)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $number = $data['number'];

        $existing = User::where(['number' => $number])->first();
        if ($existing)
        {
            return $this->sendError(trans("employee.duplicate_number"));
        }
        $user = new User();
        $user->fill($data);
        $user->password = Hash::make($data['password']);
        $user->create_user_id = $currentUser->id;

        if (!empty($data['role_id']))
        {
            $user->role_id = $data['role_id'];
        } else {
            $user->role_id = Roles::USER_B;
        }
        $user->save();
        $qr = "LK-USER-" . bcrypt($user->id);
        $user->qr = $qr;
        $user->save();

        $setting = new UserSetting();
        $setting->user_id = $user->id;
        $setting->create_user_id = $currentUser->id;

        $defaultOverTimePay = Code::where(['group' => CodeGroups::OVERTIME_PAY])->first();
        $salaryDeduction = Code::where(['group' => CodeGroups::SALARY_DEDUCTION])->first();
        $applicationDeadline = Code::where(['group' => CodeGroups::APPLICATION_DEADLINE])->first();

        $setting->overtime_pay = $defaultOverTimePay->key;
        $setting->salary_deduction = $salaryDeduction->key;
        $setting->application_deadline = $applicationDeadline->key;
        $setting->save();

        return $this->sendResponse($user);
    }
    public function update(User $user, UserRequest $request)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $existing = User::where(['number' => $data['number']])->first();
        if ($existing && $existing->id !== $user->id)
        {
            return $this->sendError(trans("employee.duplicate_number"));
        }

        $user->fill($data);
        if (!empty($data['password']))
        {
            $user->password = Hash::make($data['password']);
        }
        $user->update_user_id = $currentUser->id;

        if (!$user->qr)
        {
            $user->qr = "LK-USER-" . bcrypt($user->id);
        }

        $user->save();
        if (!empty($data['password']))
        {
            Mail::to($user)->send(new PasswordUpdated($user->name, $data['password']));
        }
        return $this->sendResponse($user);
    }
    public function delete(User $user)
    {
        $user->delete();
        return $this->sendResponse();
    }

    public function updateSetting(User $user, UserSettingRequest $request)
    {
        $currentUser = auth()->user();

        $data = $request->validated();
        $setting = $user->setting;
        if (!$setting)
        {
            $setting = new UserSetting;
            $setting->user_id = $user->id;
            $setting->create_user_id = $currentUser->id;
        } else {
            $setting->update_user_id = $currentUser->id;
        }
        if (!empty($data['overtime_pay']))
        {
            $setting->overtime_pay = $data['overtime_pay'];
        }
        if (!empty($data['salary_deduction']))
        {
            $setting->salary_deduction = $data['salary_deduction'];
        }
        if (!empty($data['application_deadline']))
        {
            $setting->application_deadline = $data['application_deadline'];
        }
        $setting->save();
        return $this->sendResponse($setting);
    }

    public function updateRole(User $user, RoleUpdateRequest $request)
    {
        $data = $request->validated();
        $role_id = $data['role_id'];
        $user->role_id = $role_id;
        $user->save();
        return $this->sendResponse($user);
    }

    public function getUsers(Office $office)
    {
        if (!Gate::allows('get-office-users', $office)) {
            abort(403);
        }
        return $this->sendResponse($office->users);
    }

    private function getUserQuery($pageSize, $officeName = null, $withPager = false)
    {
        $qb = User::with('setting', 'office', 'office.region', 'office.group');
        if ($officeName)
        {
            $qb->where(function($query) use ($officeName) {
                $query->whereHas('office', function ($query) use ($officeName) {
                    $query->where('name', 'LIKE', '%' . $officeName . '%');
                })
                ->orWhere('number', 'LIKE', '%' . $officeName . '%')
                ->orWhere('name', 'LIKE', '%' . $officeName . '%');
            });
        }
        $pager = $qb->orderByDesc('created_at')->paginate($pageSize);
        $users = $pager->items();
        $response = [];
        foreach($users as $user)
        {
            $item = $user->toArray();
            if (!empty($item['office']))
            {
                if (!empty($item['office']['region']))
                {
                    $item['region'] = [
                        'id'    =>  $item['office']['region']['id'],
                        'name'  =>  $item['office']['region']['name']
                    ];
                }
                // if (!empty($item['office']['group']))
                // {
                //     $item['office_group'] = [
                //         'id'    =>  $item['office']['group']['id'],
                //         'name'  =>  $item['office']['group']['name'],
                //     ];
                // }
                $item['office'] = [
                    'id'    =>  $item['office']['id'],
                    'name'  =>  $item['office']['name']
                ];
            }
            if (empty($item['setting']))
            {
                $item['setting'] = [
                    'overtime_pay'      =>  null,
                    'salary_deduction'  =>  null,
                    'application_deadline'  =>  null,
                ];
            }
            $response[] = $item;
        }
        if ($withPager) {
            return [
                'data'  =>  $response,
                'total' =>  $pager->total(),
                'per_page'  =>  $pager->perPage(),
                'current_page'  =>  $pager->currentPage()
            ];
        }
        return $response;
    }
}
