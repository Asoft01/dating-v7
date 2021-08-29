<?php

namespace App\Http\Controllers;

use App\Country;
use App\Hobby;
use App\Language;
use App\User;
use App\UsersDetail;
use App\UsersPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use Image;

class UsersController extends Controller
{
    public function register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin'=> 0])){
                // echo "success"; die;
                Session::put('frontSession', $data['email']);
                return redirect('/step/2');
            }else{
                // echo "failed"; die;
                return redirect::back()->with('flash_message_error', 'Invalid Username or Password');
            }

        }
        return view('users.register');
    }

    public function step2(Request $request){

        // Check if dating profile already exists and under review 
        $userProfileCount = UsersDetail::where(['user_id' => Auth::User()['id'], 'status' => 0])->count();
        // if($userProfileCount > 0){
        //     echo "more than 1"; die;
        // }else{
        //     echo "less than 0"; die;
        // }
        if($userProfileCount> 0){
            return redirect('/review');
        }
        
        // echo "<pre>"; print_r(Auth::user()); die;
        // echo Auth::User()['id'];
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if(empty($data['user_id'])){
                $userDetail = new UsersDetail;
                $userDetail->user_id = Auth::User()['id'];
            }else{
                $userDetail = UsersDetail::where('user_id', $data['user_id'])->first();
                // $userDetail->status = 0;
                $userDetail->status;
            }
            // $userDetail->user_id =          Auth::user()['id'];
            $userDetail->dob =              $data['dob'];
            $userDetail->gender =           $data['gender'];
            $userDetail->height =           $data['height'];
            $userDetail->marital_status =   $data['marital_status'];
            
            if(empty($data['body_type'])){
                $data['body_type'] = '';
            }

            if(empty($data['city'])){
                $data['city'] = '';
            }
            
            if(empty($data['state'])){
                $data['state'] = '';
            }

            if(empty($data['country'])){
                $data['country'] = '';
            }

            if(empty($data['income'])){
                $data['income'] = '';
            }

            if(empty($data['occupation'])){
                $data['occupation'] = '';
            }

            if(empty($data['complexion'])){
                $data['complexion'] = '';
            }
            
            
            $userDetail->complexion =        $data['complexion'];
            $userDetail->body_type =        $data['body_type'];
            $userDetail->city =             $data['city'];
            $userDetail->state =            $data['state'];
            $userDetail->country =          $data['country'];
            $userDetail->education =        $data['education'];
            $userDetail->occupation =       $data['occupation'];
            $userDetail->income =           $data['income'];
            $userDetail->about_myself =     $data['about_myself'];
            $userDetail->about_partner =    $data['about_partner'];

            $hobbies = "";
            if(!empty($data['hobbies'])){
                foreach($data['hobbies'] as $hobby){
                    $hobbies .= $hobby . ', ';
                }
            }
            
            $userDetail->hobbies = $hobbies;

            $languages = "";
            if(!empty($data['languages'])){
                foreach($data['languages'] as $language){
                    $languages .= $language . ', ';
                }
            }

            $userDetail->languages = $languages;

            $userDetail->save();
            return redirect('/review');
        }
        // Get All Countries 
        $countries = Country::get();

        // Get All Languages 
        $languages = Language::orderBy('name', 'ASC')->get();
        // $languages = Language::where('id', '<', 55)->orderBy('name', 'ASC')->get();

        // Get All Hobbies

        $hobbies = Hobby::orderBy('title', 'ASC')->get();

        return view('users.step2')->with(compact('countries', 'languages', 'hobbies'));
    }

    public function step3(Request $request){
        if($request->isMethod('post')){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($request->hasFile('photo')){
                $files = $request->file('photo');
                foreach($files as $file){
                    // Upload photo at folder
                    // Get photo extension
                    $extension = $file->getClientOriginalExtension();
                    // Give Random name to image and add its extension
                    $fileName = rand(111, 99999).'.'.$extension;
                    // Set Image Path
                    $image_path = 'images/frontend_images/photos/'.$fileName;
                    // Invervention Code for uploading Image
                    // Image::make($file)->save($image_path);
                    Image::make($file)->resize(600, 600)->save($image_path);

                    // Add photo at users_photos table
                    
                    $photo = new UsersPhoto();
                    $photo->photo= $fileName;
                    $photo->user_id = $data['user_id'];
                    $photo->save();
                }
                return redirect('/step/3')->with('flash_message_success', 'Your photos(s) has been uploaded successfullly');
            }
        }
        return view('users.step3');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->input();
            // echo "<pre>"; print_r($data); die;
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin'=> 0])){
                // echo "success"; die;
                Session::put('frontSession', $data['email']);
                return redirect('/step/2');
            }else{
                // echo "failed"; die;
                return redirect::back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('frontSession');
        return redirect()->action('IndexController@index');
    }

    public function checkEmail(Request $request){
        $data = $request->all();
        $usersCount = User::where('email', $data['email'])->count();
        if($usersCount > 0){
            echo 'false';
        }else{
            echo 'true';
        }
    }

    public function review(){
        // echo $user_id = Auth::User()['id']; die;
        $user_id = Auth::User()['id'];
        $userStatus = UsersDetail::select('status')->where('user_id', $user_id)->first();
        if($userStatus->status == 1){
            return redirect('/step/2');
        }else{
             return view('users.review');
        }
    }

    public function viewUsers(){
        // $users = User::get();
        // $users = User::where('admin', '!=', '1')->get();
        $users = User::with('details')->where('admin', '!=', '1')->get();
        $users = json_decode(json_encode($users), true);
        // echo "<pre>"; print_r($users); die;
        return view('admin.users.view_users')->with(compact('users'));
    }

    public function updateUserStatus(Request $request){
        $data = $request->all();
        UsersDetail::where('user_id', $data['user_id'])->update(['status' => $data['status']]);
        // if($userStatus){
        //     echo 'true';
        // }else{
        //     echo 'false';
        // }
    }
}
