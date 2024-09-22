<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Exports\ContactBookExport;
use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\ContactBook0HomeRequest;
use App\Http\Requests\Child\ContactBook0SchoolRequest;
use App\Http\Requests\Child\ContactBook12HomeRequest;
use App\Http\Requests\Child\ContactBook12SchoolRequest;
use App\Http\Requests\Child\ContactBook345HomeRequest;
use App\Http\Requests\Child\ContactBook345SchoolRequest;
use App\Http\Requests\Child\ContactBookQuery;
use App\Http\Requests\Child\ContactBookTypeRequest;
use App\Models\Child;
use App\Models\ChildrenClass;
use App\Models\ContactBook;
use App\Models\ChildrenClassPeriod;
use App\Models\ContactBookTypePeriod;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

class ContactBookController extends BaseController
{
    use ChildcareAuthUserTrait;

    public function schoolSave0(Child $child, ContactBook0SchoolRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];

        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function schoolSave12(Child $child, ContactBook12SchoolRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function schoolSave345(Child $child, ContactBook345SchoolRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function typeSave(Child $child, ContactBookTypeRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];
        $type = $data['type'];

        ContactBookTypePeriod::appendType($child->id, $type, $date);

        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();

        if (!$contactBook) {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }

        return $this->sendResponse([
            'contact_book' => $contactBook,
            'contact_book_type' => $type
        ]);
    }

    public function homeSave0(Child $child, ContactBook0HomeRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];

        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function homeSave12(Child $child, ContactBook12HomeRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function homeSave345(Child $child, ContactBook345HomeRequest $request)
    {
        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        if (!$contactBook)
        {
            $contactBook = new ContactBook(['child_id' => $child->id, 'date' => $date]);
        }
        $contactBook->fill($data);
        $contactBook->save();
        return $this->sendResponse($contactBook);
    }

    public function retrieve(Child $child, ContactBookQuery $request)
    {
        $user = $this->getUser();
        if (!Gate::forUser($user)->allows('handle-child', $child))
        {
            abort(403, trans('You are not allowed'));
        }
        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();

        // if (!$contactBook)
        // {
        //     $result = [];
        // } else {
        //     $result = $contactBook->toArray();
        // }

        // for ($i = 1; $i <= 24; $i++)
        // {
        //     $keyHome = 'defecation_' . $i . '_home';
        //     $keySchool = 'defecation_' . $i . '_school';

        //     $result[$keyHome] = empty($result[$keyHome]) ? 0 : $result[$keyHome];
        //     $result[$keySchool] = empty($result[$keySchool]) ? 0 : $result[$keySchool];
        // }

        return $this->sendResponse([
            'child' => $child,
            'contact_book' => $contactBook,
            'contact_book_type' => ContactBookTypePeriod::getType($child, $date)
        ]);
    }

    public function excel(Child $child, ContactBookQuery $request)
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
        $currentUser = $token->tokenable;
        if (!$currentUser) {
            abort(403, "You are not allowed");
        }
        if (!Gate::forUser($currentUser)->allows('handle-child', $child))
        {
            abort(403, trans('You are not allowed'));
        }

        $data = $request->validated();
        $date = $data['date'];
        $contactBook = ContactBook::where(['child_id' => $child->id, 'date' => $date])->first();
        $contactBookType = ContactBookTypePeriod::getType($child, $date);
        if (!$contactBook) {
            $contactBook = new ContactBook();
        }
        $fileName = $child->name . '_' . $data['date'] . '_連絡帳.xlsx';

        return Excel::download(new ContactBookExport($child, $child->office, $date, $contactBook, $contactBookType), $fileName);
    }
}
