<?php

namespace App\Http\Controllers\DashboardController;

use App\Events\UserEvents\UserRegister;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admins list
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminsList(Request $request){
        $q = $request->q;
        $admins = User::where('type',User::ADMIN)
            ->where(function ($query) use($q){
                $query->where('first_name','LIKE','%'.$q.'%')
                    ->orWhere('last_name','LIKE','%'.$q.'%')
                    ->orWhere('email','LIKE','%'.$q.'%')
                    ->orWhere('mobile','LIKE','%'.$q.'%');
            })->orderBy('first_name');

        return view('admin.admins',[
            'admins' => $admins->paginate(10)
        ]);
    }

    /**
     * Save Admin
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveAdmin(Request $request){
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        try{
            $admin = new User();
            $admin->first_name = ucfirst($request->first_name);
            $admin->last_name = ucfirst($request->last_name);
            $admin->email = $request->email;
            $admin->mobile = $request->mobile;
            $admin->type = User::ADMIN;
            $admin->password = Hash::make($request->password);
            $admin->save();

            $setting = Setting::where('name',Setting::USER_REGISTER_NOTIFICATIONS)->first();
            if ($setting->value){
                $data = array(
                    'user' => array(
                        'full_name' => $admin->full_name,
                        'email' => $admin->email,
                        'password' => $request->password,
                    ),
                    'type' => "admin",
                );

                UserRegister::dispatch($data);
            }

            return redirect('admins')
                ->with('state',true)
                ->with('message','Admin save successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Admin save failed !!');
        }
    }

    /**
     * Change Admin State
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeAdminState(Request $request){
        $admin = User::find($request->id);
        if($admin != null){
            $admin->state = $admin->state == User::ACTIVE ? User::DE_ACTIVE : User::ACTIVE;
            $admin->save();
            return redirect()->back()
                ->with('state',true)
                ->with('message',$admin->state == User::DE_ACTIVE ? 'Admin disable successfully' : 'Admin active successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Admin state change failed !!');
        }
    }

    /**
     * Delete Admin
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin(Request $request){
        $admin = User::find($request->id);
        if($admin != null){
            $admin->delete();
            return redirect()->back()
                ->with('state',true)
                ->with('message','Admin delete successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Admin delete failed !!');
        }
    }

    /**
     * Show Update Admin View
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateAdminView(Request $request){
        $admin = User::find($request->id);
        if($admin != null){
            return view('admin.update-admin',[
                'admin' => $admin,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Admin not found !!');
        }
    }

    /**
     * Update Admin
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(Request $request){
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);

        $admin = User::find($request->id);
        if($admin != null){
            try{
                $admin->first_name = ucfirst($request->first_name);
                $admin->last_name = ucfirst($request->last_name);
                $admin->email = $request->email;
                $admin->mobile = $request->mobile;
                $admin->save();

                return redirect('admins')
                    ->with('state',true)
                    ->with('message','Admin update successfully');
            }
            catch(\Throwable $e){
                return redirect()->back()
                    ->with('state',false)
                    ->with('message','Admin update failed !!');
            }
        }
        else{
            return redirect('admins')
                ->with('state',false)
                ->with('message','Admin not found !!');
        }
    }

    /**
     * Update Admin Password View
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateAdminPasswordView(Request $request){
        $admin = User::find($request->id);
        if($admin != null){
            return view('admin.update-admin-password',[
                'admin' => $admin,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Admin not found !!');
        }
    }

    /**
     * Update Admin Password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdminPassword(Request $request){
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = User::find($request->id);
        if($admin != null){
            try{
                $admin->password = Hash::make($request->password);
                $admin->save();

                return redirect('admins')
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
            return redirect('admins')
                ->with('state',false)
                ->with('message','Admin not found !!');
        }
    }
}
