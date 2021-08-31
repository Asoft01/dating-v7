
@extends('layouts.frontLayout.front_design')
@section('content')
<div id="right_container">
    <div style="padding:20px 15px 30px 15px;">
      <h1>My Photos</h1>
      <div class="form_container">
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
      <div> You can upload your multiple photos of your choice<strong> <br />
       </strong><br />
        <br />
      </div>
      <div> <br />
        <h6 class="inner">Upload Photos:</h6>
        <br />
        
        <form id="photosForm" name="photosForm" action="{{ url('/step/3') }}" method="post" enctype="multipart/form-data">{{ csrf_field() }}
              <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
            {{-- <input type="text" name="user_id" value="Auth::User()['id']"> --}}
            <table width="80%" cellpadding = "10" cellspacing= "10">
              <tr>
                <td align="left" valign="top" class="body"><strong>Photos: * </strong></td>
                <td align="left" valign="top"><input autocomplete="off" name="photo[]" id="photo" type="file" multiple="multiple" @if(!empty($datingProfile['photo'])) value="{{ $datingProfile['photo'] }}" @endif size="22" style="font-size: 14px; width: 200px;"/></td>
              </tr>

              <tr>
                <td></td>
                <td><input type="submit" name="submit" class="button" value="Submit" /></td>
              </tr>
            </table>
          </form>
      </div>
      
      <div class="recent_add_prifile">
        @foreach ($user_photos as $photo )
          <div class="profile_box first"> 
            <span class="photo"><a href="#"><img src="/images/frontend_images/photos/{{ $photo->photo }}" alt="" /></a></span>
            <p class="left">Status:</p>
            <p class="right">
              @if($photo->status== 1)
                Active
              @else
                Inactive
              @endif

              @if($photo->default_photo == "Yes")
                (Default)
              @endif
            </p>
            <p>&nbsp;</p>

            <table cellspacing="2" cellpadding="2">
              <tr>
                <td>
                  {{-- <a href="/delete-photo/{{ $photo->photo }}"><button type="button" class="btn btn-danger btn-sm">Delete</button></a></p> --}}
                  <a rel="{{ $photo->photo }}" href="javascript:" class="deletePhoto"><button type="button" class="btn btn-danger btn-sm">Delete</button>
                </td>
                <td>
                  @if($photo->default_photo != "Yes")
                    <a href="/default-photo/{{ $photo->photo }}"><button type="button" class="btn btn-danger btn-sm">Default</button>
                  @endif
                </td>
                <tr>
              </a>
            </table>
          </p>
      </div> 
      @endforeach
    </div>
    <div class="clear"></div>
  </div>
  @endsection