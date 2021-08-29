@extends('layouts.adminLayout.admin_design')
@section('content')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Users</a> <a href="#" class="current">View Users</a> </div>
        <h1>Users</h1>

        @if(Session::has('flash_message_error')) 
        <div class="alert alert-error alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_error') !!}</strong>
        </div>
        @endif   
        @if(Session::has('flash_message_success')) 
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_success') !!}</strong>
            </div>
        @endif 
    </div>
    <div style="margin-left: 20px;">
      <a href="{{ url('/admin/export-users') }}" class="btn btn-primary btn-mini">Export</a>
  </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">
          
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
              <h5>Users</h5>
            </div>
            <div class="widget-content nopadding">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                      <tr class="gradeX">
                          <td>{{ $user['id'] }}</td>
                          <td>{{ $user['name'] }}</td>
                          <td>{{ $user['email'] }}</td>
                          <td>{{ $user['created_at'] }}</td>
                          {{-- <td>View User | Edit | Delete</td> --}}
                          <td> 
                            @if(!empty($user['details']['id']))
                                    <a href="#myModal{{ $user['id'] }}" data-toggle="modal" class="btn btn-success btn-mini">View</a> 
                                    <div id="myModal{{ $user['id'] }}" class="modal hide">
                                    <div class="modal-header">
                                        <button data-dismiss="modal" class="close" type="button">x</button>
                                        <h3>User Details</h3>
                                        <input type="checkbox" class="userStatus" rel="{{ $user['id'] }}" data-toggle="toggle" data-on="Enabled" data-off="Disabled" @if ($user['details']['status'] == "0") checked @endif>
                                    </div>
                                    <div class="modal-body">
                                        {{-- <p>Here is the text coming you can put </p> --}}
                                        <table width="100%" cellpadding = "10" cellspacing= "10">
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Date of Birth: * </strong></td>
                                            <td align="left" valign="top">{{ $user['details']['dob'] }}</td>
                                            </tr>
                                        
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Gender: * </strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['gender'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Height: * </strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['height'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Marital Status: * </strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['marital_status'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Body Type:</strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['body_type'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>City:</strong></td>
                                            <td align="left" valign="top">{{ $user['details']['city'] }}</td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong> State:</strong></td>
                                            <td align="left" valign="top">{{ $user['details']['state'] }}</td>
                                            </tr>
                                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Country:</strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['country'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Languages:</strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['languages'] }}
                                            </td>
                                            </tr>
                            
                                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Hobbies:</strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['hobbies'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                                <td colspan="2"><h6 class="inner"> Education & Career</h6></td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong> Highest Education:</strong></td>
                                            <td align="left" valign="top">{{ $user['details']['education'] }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="left" valign="top" class="body"><strong> Occupation:</strong></td>
                                                <td align="left" valign="top">
                                                    {{ $user['details']['occupation'] }}
                                                </td>
                                            </tr>
                            
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong> Income:</strong></td>
                                            <td align="left" valign="top">
                                                    {{ $user['details']['income'] }}
                                                </select>
                                            </td>
                                            </tr>
                            
                                            <tr>
                                                <td colspan="2"><h6 class="inner">About MySelf</h6></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="top" class="body"><strong>About Myself: * </strong></td>
                                                <td align="left" valign="top">
                                                    {{ $user['details']['about_myself'] }}
                                            </td>
                                            </tr>
                            
                                            <tr>
                                                <td colspan="2"><h6 class="inner">About My Preferred Partner</h6></td>
                                            </tr>
                                            <tr>
                                            <td align="left" valign="top" class="body"><strong>Partner Details: * </strong></td>
                                            <td align="left" valign="top">
                                                {{ $user['details']['about_partner'] }}
                                            </td>
                                        </tr>
                                        
                                        </table>
                                    </div>
                                </div>
                            @endif
                                <a href="#" class="btn btn-primary btn-mini">Edit</a>
                                <a href="#" class="btn btn-danger btn-mini">Delete</a>
                        </td>
                      </tr> 
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
@endsection