<?php

namespace App\Http\Controllers\DashboardController;

use App\Events\UserEvents\UserRegister;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show Users List
     * @param $q
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function usersList(Request $request){
        $q = $request->q;
        $users = User::where('type',User::USER)
            ->where(function ($query) use($q){
               $query->where('first_name','LIKE','%'.$q.'%')
                   ->orWhere('last_name','LIKE','%'.$q.'%')
                   ->orWhere('email','LIKE','%'.$q.'%')
                   ->orWhere('mobile','LIKE','%'.$q.'%')
                   ->orWhere('address','LIKE','%'.$q.'%')
                   ->orWhere('birthday','LIKE','%'.$q.'%')
                   ->orWhere('emergency_contact_number','LIKE','%'.$q.'%')
                   ->orWhere('emergency_contact_relationship','LIKE','%'.$q.'%')
                   ->orWhere('state','LIKE','%'.$q.'%');
            })->orderBy('first_name');

        return view('user.users',[
            'users' => $users->paginate(10)
        ]);
    }

    /**
     * Save User
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'dob' => 'nullable|date|date_format:Y-m-d|before:today',
            'photo_id' => 'image|max:2048',
            'police_check' => 'image|max:2048',
            'wwcc' => 'image|max:2048',
            'state' => 'required',
            'password' => 'required|min:8|confirmed',
        ],[
            'dob' => 'The birthday must be a date before today.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try{
            $password = Hash::make($request->password);

            $user = new User();
            $user->first_name = ucfirst($request->first_name);
            $user->last_name = ucfirst($request->last_name);
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->birthday = $request->dob;
            $user->address = $request->address;
            $user->emergency_contact_number = $request->ecn;
            $user->emergency_contact_relationship = $request->ecr;
            $user->type = User::USER;
            $user->state = $request->state;
            $user->password = $password;
            $user->save();

            if($request->hasFile('photo_id')){
                $extension = $request->photo_id->extension();
                $url = $request->photo_id->storeAs("user-documents/".$user->id,$user->id."_photo_id.".$extension);
                $user->photo_id_path = $url;
                $user->save();
            }

            if($request->hasFile('police_check')){
                $extension = $request->police_check->extension();
                $url = $request->police_check->storeAs("user-documents/".$user->id,$user->id."_police_check.".$extension);
                $user->police_check_path = $url;
                $user->save();
            }

            if($request->hasFile('wwcc')){
                $extension = $request->wwcc->extension();
                $url = $request->wwcc->storeAs("user-documents/".$user->id,$user->id."_wwcc.".$extension);
                $user->wwcc_path = $url;
                $user->save();
            }

            $setting = Setting::where('name',Setting::USER_REGISTER_NOTIFICATIONS)->first();
            if ($setting->value){
                $data = array(
                    'user' => array(
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'password' => $request->password,
                    ),
                    'type' => "user",
                );

                UserRegister::dispatch($data);
            }

            return redirect('users')
                ->with('state',true)
                ->with('message','User save successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','User save failed !!');
        }
    }

    /**
     * Delete user images
     * @param $path
     * @return void
     */
    public function deleteStorageImage($path){
        if($path != null){
            $storage = Storage::disk('public');
            if($storage->exists($path)){
                $storage->delete($path);
            }
        }
    }

    /**
     * Delete User
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(Request $request){
        $user = User::find($request->id);
        if($user != null){
            Storage::deleteDirectory('user-documents/'.$user->id);
            $user->delete();
            return redirect('users')
                ->with('state',true)
                ->with('message','User delete successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User delete failed !!');
        }
    }

    /**
     * Change User State
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeUserState(Request $request){
        $user = User::find($request->id);
        if($user != null){
            $user->state = $user->state == User::ACTIVE ? User::DE_ACTIVE : User::ACTIVE;
            $user->save();
            return redirect()->back()
                ->with('state',true)
                ->with('message',$user->state == User::DE_ACTIVE ? 'User disable successfully' : 'User active successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User state change failed !!');
        }
    }

    /**
     * Show Update User View
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateUserView(Request $request){
        $user = User::find($request->id);
        if($user != null){
            return view('user.update-user',[
                'user' => $user,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }

    /**
     * Update User
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'dob' => 'nullable|date|date_format:Y-m-d|before:today',
            'photo_id' => 'image|max:2048',
            'police_check' => 'image|max:2048',
            'wwcc' => 'image|max:2048',
            'state' => 'required',
        ],[
            'dob' => 'The birthday must be a date before today.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($request->id);
        if($user != null){
            try{
                $user->first_name = ucfirst($request->first_name);
                $user->last_name = ucfirst($request->last_name);
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->birthday = $request->dob;
                $user->address = $request->address;
                $user->emergency_contact_number = $request->ecn;
                $user->emergency_contact_relationship = $request->ecr;
                $user->state = $request->state;
                $user->save();

                if($request->hasFile('photo_id')){
                    $this->deleteStorageImage($user->photo_id);
                    $extension = $request->photo_id->extension();
                    $url = $request->photo_id->storeAs("user-documents/".$user->id,$user->id."_photo_id.".$extension);
                    $user->photo_id_path = $url;
                    $user->save();
                }

                if($request->hasFile('police_check')){
                    $this->deleteStorageImage($user->police_check);
                    $extension = $request->police_check->extension();
                    $url = $request->police_check->storeAs("user-documents/".$user->id,$user->id."_police_check.".$extension);
                    $user->police_check_path = $url;
                    $user->save();
                }

                if($request->hasFile('wwcc')){
                    $this->deleteStorageImage($user->wwcc);
                    $extension = $request->wwcc->extension();
                    $url = $request->wwcc->storeAs("user-documents/".$user->id,$user->id."_wwcc.".$extension);
                    $user->wwcc_path = $url;
                    $user->save();
                }

                return redirect('users')
                    ->with('state',true)
                    ->with('message','User update successfully');
            }
            catch(\Throwable $e){
                return redirect()->back()
                    ->with('state',false)
                    ->with('message','User update failed !!');
            }
        }
        else{
            return redirect('users')
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }

    /**
     * Update User Password View
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateUserPasswordView(Request $request){
        $user = User::find($request->id);
        if($user != null){
            return view('user.update-user-password',[
                'user' => $user,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }

    /**
     * Update User Password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserPassword(Request $request){
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::find($request->id);
        if($user != null){
            try{
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect('users')
                    ->with('state',true)
                    ->with('message','Password update successfully');
            }
            catch(\Throwable $e){
                return redirect()->back()
                    ->with('state',false)
                    ->with('message','Password update failed !!');
            }
        }
        else{
            return redirect('users')
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }

    /**
     * View User
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewUserProfile(Request $request){
        $user = User::find($request->id);
        if($user != null){
            return view('user.view-user',[
                'user' => $user,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }

    public function viewUserLocations(Request $request){
        $userLocations = UserLocations::where('user_id',$request->id)->paginate(10);
        if($userLocations != null){
            return view('user.user-locations',[
                'userLocations' => $userLocations,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User not found !!');
        }
    }
}
