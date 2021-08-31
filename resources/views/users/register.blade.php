@extends('layouts.frontLayout.front_design')
@section('content')
<div id="right_container">
    <div style="padding:20px 15px 30px 15px;">
      <h1>New User Registration</h1>
      <div> <strong> <br />
        Register for free by filling out the form below:-</strong><br />
        <br /></div>
      <div> <br />
        <h6 class="inner">Register:</h6>
        <br />
        <form id="signupForm" action="{{ url('/register') }}" method="post">{{ csrf_field() }}
          <table width="80%">
            <tr>
              <td align="left" valign="top" class="body"><strong>Username:</strong></td>
              <td align="left" valign="top"><input name="username" id="username" type="text" size="25" /></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="body"><strong>Name:</strong></td>
              <td align="left" valign="top"><input name="name" id="name" type="text" size="25" /></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="body"><strong> Email: </strong></td>
              <td align="left" valign="top"><input name="email" id="user_email" type="text" size="25" /></td>
            </tr>
            
            <tr>
                <td align="left" valign="top" class="body"><strong> Password: </strong></td>
                <td align="left" valign="top"><input name="password" id="user_password" type="password" size="25" /> <span id="passstrength"></span></td>
                
            </tr>

            <tr>
                <td align="left" valign="top" class="body"><strong> Confirm Password: </strong></td>
                <td align="left" valign="top"><input name="confirm_password" id="confirm_password" type="password" size="25" /></td>
            </tr>

            <tr>
                <td align="left" valign="top" class="body"><strong>Please Agree to our policy:</strong></td>
                <td align="left" valign="top"><input name="agree" id="agree" type="checkbox" size="25" /></td>
              </tr>
            <tr>
            
            {{-- <tr>
                <td align="left" valign="top" class="body"><strong>Captcha:</strong></td>
                <td align="left" valign="top">
                    <div class="col-md-6 pull-center">
                        {!! app('captcha')->display() !!}

                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>
                </td>
            </tr> --}}
            <tr>
            
              <td></td>
              <td><input type="submit" name="submit" class="button" value="Register Now" /></td>
            </tr>
          </table>
        </form>
      </div>
     
    </div>
    <div class="clear"></div>
  </div>
  @endsection