<?php

namespace App\Http\Controllers;

use App\Country;
use App\Friend;
use App\Hobby;
use App\Language;
use App\User;
use App\UsersDetail;
use App\UsersPhoto;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
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
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            // if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin'=> 0])){
            if(Auth::attempt(['username' => $data['username'], 'password' => $data['password'], 'admin'=> 0])){
                // echo "success"; die;
                // Session::put('frontSession', $data['email']);
                Session::put('frontSession', $data['username']);
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
                $userDetail->status = 0;
                // $userDetail->status;
            }
            // $userDetail->user_id =          Auth::user()['id'];
            $userDetail->username =         Session::get('frontSession');
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
                    $photo->username = Session::get('frontSession');
                    $photo->save();
                }
                return redirect('/step/3')->with('flash_message_success', 'Your photos(s) has been uploaded successfullly');
            }
        }

        $user_id = Auth::User()->id;
        $user_photos = UsersPhoto::where('user_id', $user_id)->get();
        $user_photos = json_decode(json_encode($user_photos));
        // echo "<pre>"; print_r($user_photos); die;
        return view('users.step3')->with(compact('user_photos'));
    }

    public function deletePhoto($photo){
        // echo $photo; echo "---";
        //    echo $user_id = Auth::User()->id; die;
        $user_id = Auth::User()->id; 
        UsersPhoto::where(['user_id'=> $user_id, 'photo'=> $photo])->delete();
        // Delete from photos folder with PHP unlink function
        
        //    unlink('images/frontend_images/photos/'.$photo); //Unlink function not working
       File::delete('images/frontend_images/photos/'.$photo);
       return redirect()->back()->with('flash_message_success', 'Photo has been deleted successfully');
    }

    public function defaultPhoto($photo){
        $user_id = Auth::User()->id; 
        // Set all Photos as Non Default
        UsersPhoto::where('user_id', $user_id)->update(['default_photo' => 'No']);
        
        // Make selected Photo Default
        UsersPhoto::where(['user_id'=> $user_id, 'photo'=> $photo])->update(['default_photo' => 'Yes']);
        
        
       return redirect()->back()->with('flash_message_success', 'Default Photo has been set successfully');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->input();
            // echo "<pre>"; print_r($data); die;
            // if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'admin'=> 0])){
            if(Auth::attempt(['username' => $data['username'], 'password' => $data['password'], 'admin'=> 0])){
                // echo "success"; die;

                if(preg_match("/contact/i", Session::get('current_url'))){
                    // echo "A match was found ";
                    Session::put('frontSession', $data['username']);
                    return redirect(Session::get('current_url'));
                    // return redirect('/contact/'.$data['username']);
                }else if(preg_match("/add-new-friend/i", Session::get('current_url'))){
                    // echo Session::get('current_url');
                    // echo "test"; die;
                    Session::put('frontSession', $data['username']);
                    return redirect(Session::get('current_url'));
                }else{
                    Session::put('frontSession', $data['username']);
                    return redirect('/step/2');
                }
                
            }else{
                // echo "failed"; die;
                return redirect::back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }

    public function logout(){
        Cache::flush();
        Auth::logout();
        Session::forget('frontSession');
        Session::forget('current_url');
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

    public function checkUsername(Request $request){
        $data = $request->all();
        $usersCount = User::where('username', $data['username'])->count();
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
        // $users = User::with('details')->where('admin', '!=', '1')->get();
        $users = User::with('details')->with('photos')->where('admin', '!=', '1')->get();
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

    public function updatePhotoStatus(Request $request){
        $data = $request->all();
        UsersPhoto::where('id', $data['photo_id'])->update(['status' => $data['status']]);
    }

    public function viewProfile($username){
        $userCount =  User::where('username', $username)->count();
        if($userCount > 0){
            $userDetails = User::with('details')->with('photos')->where('username', $username)->first();
            $userDetails = json_decode(json_encode($userDetails));
            // echo "<pre>"; print_r($userDetails); die;
            if(Auth::check()){
                // Check if the user is logged or not
                //echo $friendrequest = "true"; die
                $user_id = Auth::user()->id;
                $friend_id = User::getUserId($username);
                // echo $friendCount = Friend::where(['user_id'=> $user_id, 'friend_id' => $friend_id])->count();die;
                $friendCount = Friend::where(['user_id'=> $user_id, 'friend_id' => $friend_id])->count();
                if($friendCount> 0){
                    $friendDetails = Friend::where(['user_id'=> $user_id, 'friend_id' => $friend_id])->first();
                    // $friendDetails = json_decode(json_encode($friendDetails));
                    // echo "<pre>"; print_r($friendDetails); die;
                    if($friendDetails->accept == 1){
                        // $friendrequest = "Friends (Unfriend)";
                        $friendrequest = "(Unfriend)";
                    }else{
                        $friendrequest= "Friend request sent";
                    }
                }else{
                    $friendRequestCount = Friend::where(['friend_id'=> $user_id, 'user_id' => $friend_id])->count();
                    // echo $friendRequestCount; die;
                    if($friendRequestCount> 0){
                        $friendRequest = Friend::where(['friend_id' => $user_id, 'user_id' => $friend_id])->first();
                        if($friendRequest->accept== 1){
                            $friendrequest = "Unfriend";
                        }else{
                            $friendrequest = "Confirm Friend Request";
                        }
                    }else{
                        $friendrequest = "Add Friend";
                    }
                }
            }else{
                $friendrequest = "";
            }
        }else{
            abort(404);
        }
        // $userDetails = User::with('details')->with('photos')->where('username', $username)->first();
        // $userDetails = json_decode(json_encode($userDetails));
        // echo "<pre>"; print_r($userDetails); die;

        ////////////////// Users friends List ////////////////
        // echo $friend_id = User::getUserId($username); die;
        $friend_id = User::getUserId($username);
        $friendCount1= Friend::where(['friend_id' => $friend_id, 'accept' =>1 ])->count();
        $friend_ids1 = array();
        if($friendCount1 > 0){
            // echo "test"; die;
            $friend_ids1 = Friend::select('user_id')->where(['friend_id' => $friend_id, 'accept' => 1])->get();
            $friend_ids1 = array_flatten(json_decode(json_encode($friend_ids1), true));
            // echo "<pre>"; print_r($friend_ids1); die;
        }

        $friendCount2= Friend::where(['user_id' => $friend_id, 'accept' =>1 ])->count();
        $friend_ids2 = array();
        if($friendCount2 > 0){
            // echo "test"; die;
            $friend_ids2 = Friend::select('friend_id')->where(['user_id' => $friend_id, 'accept' => 1])->get();
            $friend_ids2 = array_flatten(json_decode(json_encode($friend_ids2), true));
            // echo "<pre>"; print_r($friend_ids2); die;
        }

        // echo "test2"; die;
        // To merge the two arrays that has been flattened 
        $friends_ids = array();
        $friends_ids = array_merge($friend_ids1, $friend_ids2);
        // echo "<pre>"; print_r($friends_ids); die;

        $friendsList = User::with('details')->with('photos')->whereIn('id', $friends_ids)->orderBy('id', 'Desc')->get();
        $friendsList = json_decode(json_encode($friendsList));

        return view('users.profile')->with(compact('userDetails', 'friendrequest', 'friendsList'));
    }

    public function contactProfile($username, Request $request){
        $userCount =  User::where('username', $username)->count();
        if($userCount > 0){
            $userDetails = User::with('details')->with('photos')->where('username', $username)->first();
            $userDetails = json_decode(json_encode($userDetails));
            // echo "<pre>"; print_r($userDetails); die;

            if($request->isMethod('post')){
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
                // echo "<br>"; 
                // echo $username; die;
                // echo $userDetails->id; die;

                // echo "Sender"; 
                // echo Auth::user()->username; echo "---";
                // echo Auth::user()->id;

                // echo "<br>";
                // // Receiver Username/id;
                // echo "Receiver";
                // echo $username; echo "---"; 
                // echo $userDetails->id;

                // Add Response in response table 
                $response = new Response;
                $response->sender_id = Auth::user()->id;
                $response->receiver_id = $userDetails->id;
                $response->message = $data['message'];
                $response->save();
                // echo "done"; die;
                return redirect()->back()->with('flash_message_success', 'Your response has been sent to this dating profile');
            }
        }else{
            abort(404);
        }
        return view('users.contact')->with(compact('userDetails'));
    }

    public function addFriend($username, Request $request){
       
        $userCount =  User::where('username', $username)->count();
        if($userCount > 0){
            $user_id = Auth()->user()->id;
        //    echo "Going to send friend request to".$username; die;
            $friend_id = User::getUserId($username);
            $friend = new Friend;
            $friend->user_id = $user_id;
            $friend->friend_id = $friend_id;
            $friend->save();
        //    echo "Friend request sent to ".$username; die;
            return redirect()->back();
        }else{
            abort(404);
        }
    }

    public function addNewFriend($username, Request $request){
       
        $userCount =  User::where('username', $username)->count();
        if($userCount > 0){
            $user_id = Auth()->user()->id;
        //    echo "Going to send friend request to".$username; die;
            $friend_id = User::getUserId($username);
            // echo "test"; die;

            // Check if already friends or friend request sent
            $friendCount1= Friend::where(['friend_id' => $friend_id, 'user_id' => $user_id])->count();
            if($friendCount1 > 0){
              return redirect('/profile/'.$username);
            }

            $friendCount2= Friend::where(['user_id' => $friend_id, 'friend_id' => $user_id])->count();
            if($friendCount2 > 0){
               return redirect('/profile/'.$username);
            }

            $friend = new Friend;
            $friend->user_id = $user_id;
            $friend->friend_id = $friend_id;
            $friend->save();
        //    echo "Friend request sent to ".$username; die;
            return redirect('/profile/'.$username);

        }else{
            abort(404);
        }
    }

    public function confirmFriendRequest($username, Request $request){
    //    echo $user_id = Auth::user()->id; die;
       $user_id = Auth::user()->id;
       $friend_id = User::getUserId($username);
       $friendCount = Friend::where(['friend_id' => $user_id, 'user_id' => $friend_id])->count();
       if($friendCount){
           Friend::where(['friend_id' => $user_id, 'user_id' => $friend_id])->update(['accept' => 1]);
           return redirect()->back();
       }else{
           abort(404);
       }
    }

    public function removeFriend($username, Request $request){
        $userCount =  User::where('username', $username)->count();
        if($userCount > 0){
            $user_id = Auth()->user()->id;
            $friend_id = User::getUserId($username);
            Friend::where(['user_id' => $user_id, 'friend_id' => $friend_id])->delete();
            return redirect()->back();
        }else{
            abort(404);
        }
    }

    public function searchProfile(Request $request){
        // echo "test"; die;
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // if(!empty($data['country'])){
            //     // $data['country'] = "";
            //     $searched_users = User::with('details')->with('photos')
            //     ->join('users_details', 'users_details.user_id', '=', 'users.id')
            //     ->where('users_details.gender', $data['gender'])
            //     ->where('users_details.country', $data['country'])
            //     ->orderBy('users.id', 'Desc')->get();
            // }else{
            //     $searched_users = User::with('details')->with('photos')
            //     ->join('users_details', 'users_details.user_id', '=', 'users.id')
            //     ->where('users_details.gender', $data['gender'])
            //     ->orderBy('users.id', 'Desc')->get();
            // }
            // $searched_users = User::with('details')->with('photos')


            ////////////////////////// Second Method ///////////// 
            
            // $searched_users = User::join('users_details', 'users_details.user_id', '=', 'users.id')
            //                         ->with('photos')
            //                         // ->with('details')
            //                         // ->join('users_details', 'users_details.user_id', '=', 'users.id')
            //                         ->where('users_details.gender', $data['gender'])
            //                         ->where('users_details.country', $data['country'])
            //                         ->orderBy('users.id', 'Desc')->get();
            // $searched_users = json_decode(json_encode($searched_users));
            

            ///////////////////// Last And Effective Method //////////////////////////
             $searched_users = User::with('details')->with('photos')
                ->join('users_details', 'users_details.user_id','=', 'users.id')
                ->where('users_details.gender', $data['gender'])
                ->where('users.username', '!=', 'admin');
            if(!empty($data['country'])){
                $searched_users = $searched_users->where('users_details.country', $data['country']);
            }

            $searched_users = $searched_users->orderBy('users.id', 'Desc')->get();
            $searched_users = json_decode(json_encode($searched_users));
            
            $minAge = $data['minAge'];
            $maxAge = $data['maxAge'];
            // echo "<pre>"; print_r($searched_users); die;
            return view('users.search')->with(compact('searched_users', 'minAge', 'maxAge'));
        }
    }

    public function responses(){
        $receiver_id= Auth::user()->id;
        $responses = Response::where('receiver_id', $receiver_id)->orderBy('id', 'Desc')->get();
        // $responses= json_decode(json_encode($responses));
        // echo "<pre>"; print_r($responses); die;
        return view('users.responses')->with(compact('responses'));
    }

    public function friendsRequests(){
        $user_id= Auth::user()->id;
        $friendsRequests = Friend::where(['friend_id'=> $user_id, 'accept'=> 0])->get();
        $friendsRequests = json_decode(json_encode($friendsRequests));
        // echo "<pre>"; print_r($friendsRequests); die;
        return view('users.friends_requests')->with(compact('friendsRequests'));
    }

    public function friends(){

        ////////////// This is the first method that shows list of friends without their details ////////////
        // $user_id= Auth::user()->id;
        // $friendsCount = Friend::where(['friend_id'=> $user_id, 'accept'=> 1])->count();
        // if($friendsCount > 0){
        //     $friends = Friend::where(['friend_id'=> $user_id, 'accept'=> 1])->get();
        // }else{
        //     $friends = Friend::where(['user_id'=> $user_id, 'accept'=> 1])->get();
        // }
        // $friends = json_decode(json_encode($friends));
        // echo "<pre>"; print_r($friends); die;
        

        ////////////////////This is the second method of fetching the lists of ID /////////////////
        // echo $friend_id = Auth::user()->id; die; 
        $friend_id = Auth::user()->id;
        $friendCount1= Friend::where(['friend_id' => $friend_id, 'accept' =>1 ])->count();
        $friend_ids1= array();
        if($friendCount1 > 0){
            // echo "test"; die;
            $friend_ids1 = Friend::select('user_id')->where(['friend_id' => $friend_id, 'accept' => 1])->get();
            $friend_ids1 = array_flatten(json_decode(json_encode($friend_ids1), true));
            // echo "<pre>"; print_r($friend_ids1);
        }

        $friendCount2= Friend::where(['user_id' => $friend_id, 'accept' =>1 ])->count();
        $friend_ids2 = array();
        if($friendCount2 > 0){
            // echo "test"; die;
            $friend_ids2 = Friend::select('friend_id')->where(['user_id' => $friend_id, 'accept' => 1])->get();
            $friend_ids2 = array_flatten(json_decode(json_encode($friend_ids2), true));
            // echo "<pre>"; print_r($friend_ids2); die;
        }

        // echo "test2"; die;
        // To merge the two arrays that has been flattened 
        $friends_ids= array();
        $friends_ids = array_merge($friend_ids1, $friend_ids2);
        // echo "<pre>"; print_r($friends_ids); die;

        $friendsList = User::with('details')->with('photos')->whereIn('id', $friends_ids)->orderBy('id', 'Desc')->get();
        $friendsList = json_decode(json_encode($friendsList));
        // echo "<pre>"; print_r($friendsList); die;
        
        // return view('users.friends')->with(compact('friends'));
        return view('users.friends')->with(compact('friendsList'));
    }


    public function acceptFriendRequest($sender_id){
        $receiver_id = Auth::user()->id;
        Friend::where(['user_id' => $sender_id, 'friend_id'=> $receiver_id])->update(['accept'=> 1]);
        return redirect()->back()->with('flash_message_success', 'Friend accepted successfully');
    }

    public function rejectFriendRequest($sender_id){
        $receiver_id = Auth::user()->id;
        Friend::where(['user_id' => $sender_id, 'friend_id'=> $receiver_id])->delete();
        Friend::where(['user_id' => $receiver_id, 'friend_id'=> $sender_id])->delete();
        return redirect()->back()->with('flash_message_success', 'Friend request successfully rejected');
    }    

    public function updateResponse(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            Response::where('id', $data['response_id'])->update(['seen'=> 1]);
            echo Response::newResponseCount();
        }
    }

    public function deleteResponse($id){
        // echo $id; die;
        Response::where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success', 'Response has been deleted successfully');
    }

    public function sentMessages(){
        $sender_id = Auth::user()->id;
        $sent_msg = Response::where('sender_id', $sender_id)->orderBy('id', 'Desc')->get();
        // $sent_messages = json_decode(json_encode($sent_messages));
        // echo "<pre>"; print_r($sent_messages); die;
        return view('users.sent_messages')->with(compact('sent_msg'));
    }
}
