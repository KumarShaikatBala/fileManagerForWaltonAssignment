<?php

namespace App\Http\Controllers\API;

use App\Bank_account;
use App\Financial_organization;
use App\Http\Controllers\Controller;
use App\Http\Resources\Accounts;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;
use File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function accounts()
    {
        //return Bank_account::with('bank')->latest()->paginate(10);
        $data=Bank_account::with('bank')->latest()->paginate('10');
        return Accounts::collection($data);
    }
    public function banks()
    {
        return Financial_organization::latest()->get();
    }
    public function accountStore(Request $request)
    {
        //return $request;
        $this->validate($request,[
            'account_name' =>'required',
            'account_no' => 'required',
            'branch' => 'required',
            'account_type' => 'required',
        ]);
        return Bank_account::create([
            'financial_organization_id' => $request['financial_organization_id'],
            'store_id' => $request['store_id'],
            'account_name' => $request['account_name'],
            'account_no' => $request['account_no'],
            'branch' => $request['branch'],
            'account_type' => $request['account_type'],
            'swift_code' => $request['swift_code'],
            'route_no' => $request['route_no'],
        ]);
    }
    public function updateAccount(Request $request, $id)
    {
        $account=Bank_account::findOrFail($id);
        $this->validate($request,[
            'account_name' =>'required',
            'account_no' => 'required',
            'branch' => 'required',
            'account_type' => 'required',
        ]);
        $account->update(
            $request->all()
        );
    }
    public function destroyAccount($id)
    {
        Bank_account::destroy($id);
    }

















    public function index()
    {
        return User::latest()->paginate(10);
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'bio' => $request['bio'],
            'type' => $request['type'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function profile()
    {

        return auth('api')->user();
    }
    public function updateProfile(Request $request)
    {
        $user=auth('api')->user();
        $this->validate($request,[
            'name' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto=$user->photo;
        if ($request->photo!=$currentPhoto) {

            $image = $request->get('photo');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->photo)->save('img/'.$name);

            $request->merge(['photo'=>$name]);

            $image_path ='img/'.$currentPhoto;

            if(File::exists($image_path))
            {
                File::delete($image_path);

            }


        }

        if (!empty($request->password)){
            $request->merge(['password'=>Hash::make($request['password'])]);
        }

$user->update($request->all());
    }




    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $user=User::findOrFail($id);
        $this->validate($request,[
            'name' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id
        ]);
        $user->update(
            $request->all()
        );
    }


    public function destroy($id)
    {
        User::destroy($id);
    }

}
