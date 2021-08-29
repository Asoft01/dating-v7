<?php 
use App\User;
// echo $datingCount = User::datingProfileExists(Auth::User()['id']); die;
$datingCount = User::datingProfileExists(Auth::User()['id']);
if($datingCount == 1){
  $datingCountText = "My Dating Profile";
  $datingCountText2 = "Update Dating Profile below";
}else{
  $datingCountText = "Add Dating Profile";
  $datingCountText2 = "Add Dating profile by filling out the form below";
}

$datingProfile = User::datingProfileDetails(Auth::User()['id']);
// $datingProfile = json_decode(json_encode($datingProfile), true);
// echo "<pre>"; print_r($datingProfile); die;
?>

@extends('layouts.frontLayout.front_design')
@section('content')
<div id="right_container">
    <div style="padding:20px 15px 30px 15px;">
      <h1>{{ $datingCountText }}</h1>
      <div> <strong> <br />
        {{ $datingCountText2 }}</strong><br />
        <br /></div>
      <div> <br />
        <h6 class="inner">Personal Information:</h6>
        <br />
        <form id="datingForm" name="datingForm" action="{{ url('/step/2') }}" method="post">{{ csrf_field() }}
            @if(!empty($datingProfile->user_id))
              <input type="hidden" name="user_id" value="{{ $datingProfile->user_id }}">
            @endif
            {{-- <input type="text" name="user_id" value="Auth::User()['id']"> --}}
            <table width="80%" cellpadding = "10" cellspacing= "10">
              <tr>
                <td align="left" valign="top" class="body"><strong>Date of Birth: * </strong></td>
                <td align="left" valign="top"><input autocomplete="off" name="dob" id="dob" type="text" @if(!empty($datingProfile['dob'])) value="{{ $datingProfile['dob'] }}" @endif size="22" style="font-size: 14px; width: 200px;"/></td>
              </tr>
             
              <tr>
                <td align="left" valign="top" class="body"><strong>Gender: * </strong></td>
                <td align="left" valign="top">
                    <select name="gender" style="font-size: 14px; height: 25px; width: 200px;">
                        <option>Select</option>
                        <option value="Male"  @if(!empty($datingProfile['gender']) && $datingProfile['gender'] == "Male") selected="" @endif> Male</option>
                        <option value="Female" @if(!empty($datingProfile['gender']) && $datingProfile['gender'] == "Female") selected="" @endif>Female</option>
                    </select>
                </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong>Height: * </strong></td>
                <td align="left" valign="top">
                    <select name="height" style="font-size: 14px; height: 25px; width: 200px;">
                       <option value="">Feet/Inches</option>
                       <option value="4' 0'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 0'") selected="" @endif>4' 0"</option>
                       <option value="4' 1'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 1'") selected="" @endif>4' 1"</option>
                       <option value="4' 2'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 2'") selected="" @endif>4' 2"</option>
                       <option value="4' 3'" @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 3'") selected="" @endif>4' 3"</option>
                       <option value="4' 4'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 4'") selected="" @endif>4' 4"</option>
                       <option value="4' 5'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 5'") selected="" @endif>4' 5"</option>
                       <option value="4' 6'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 6'") selected="" @endif>4' 6"</option>
                       <option value="4' 7'" @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 7'") selected="" @endif>4' 7"</option>
                       <option value="4' 8'" @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 8'") selected="" @endif>4' 8"</option>
                       <option value="4' 9'"  @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 9") selected="" @endif>4' 9"</option>
                       <option value="4' 10'" @if(!empty($datingProfile['height']) && $datingProfile['height'] == "4' 10'") selected="" @endif>4' 10"</option>
                    </select>
                </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong>Marital Status: * </strong></td>
                <td align="left" valign="top">
                    <select name="marital_status" style="font-size: 14px; height: 25px; width: 200px;">
                        <option value="">Select </option>
                        <option value="Unmarried"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Unmarried") selected="" @endif>Unmarried</option>
                        <option value="Married"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Married") selected="" @endif>Married</option>
                        <option value="Divorced"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Divorced") selected="" @endif>Divorced</option>
                        <option value="Widowed"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Widowed") selected="" @endif>Widowed</option>
                        <option value="Separated"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Separated") selected="" @endif>Separated</option>
                        <option value="Anulled"  @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Anulled") selected="" @endif>Anulled</option>
                        <option value="other" @if(!empty($datingProfile['marital_status']) && $datingProfile['marital_status'] == "Other") selected="" @endif>Other</option>
                    </select>
                </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong>Body Type:</strong></td>
                <td align="left" valign="top">
                    <select name="body_type" style="font-size: 14px; height: 25px; width: 200px;">
                        <option value="">Select </option>
                        <option value="Slim" @if(!empty($datingProfile['body_type']) && $datingProfile['body_type'] == "Slim") selected="" @endif>Slim</option>
                        <option value="Average" @if(!empty($datingProfile['body_type']) && $datingProfile['body_type'] == "Average") selected="" @endif>Average</option>
                        <option value="Athlete" @if(!empty($datingProfile['body_type']) && $datingProfile['body_type'] == "Athlete") selected="" @endif>Athletic</option>
                        <option value="Heavy" @if(!empty($datingProfile['body_type']) && $datingProfile['body_type'] == "Heavy") selected="" @endif>Heavy</option>
                    </select>
                </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong>Complexion:</strong></td>
                <td align="left" valign="top">
                    <select name="body_type" style="font-size: 14px; height: 25px; width: 200px;">
                       <option value="">Select</option>
                       <option value="Very Fair" @if(!empty($datingProfile['complexion']) && $datingProfile['complexion'] == "Very Fair") selected="" @endif>Very Fair</option>
                       <option value="Fair" @if(!empty($datingProfile['complexion']) && $datingProfile['complexion'] == "Fair") selected="" @endif>Fair</option>
                       <option value="Wheatish"  @if(!empty($datingProfile['complexion']) && $datingProfile['complexion'] == "Wheatish") selected="" @endif>Wheatish</option>
                       <option value="Dark"  @if(!empty($datingProfile['complexion']) && $datingProfile['complexion'] == "Dark") selected="" @endif>Dark</option>
                    </select>
                </td>
              </tr>
             
              <tr>
                <td align="left" valign="top" class="body"><strong>City:</strong></td>
                <td align="left" valign="top"><input autocomplete="off" name="city" id="city" type="text"  @if(!empty($datingProfile['city'])) value="{{ $datingProfile['city'] }}" @endif size="22" style="font-size: 14px; height: 25px; width: 200px;"/></td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong> State:</strong></td>
                <td align="left" valign="top"><input autocomplete="off" name="state" id="state" type="text" @if(!empty($datingProfile['state'])) value="{{ $datingProfile['state'] }}" @endif size="22" style="font-size: 14px; height: 25px; width: 200px;" /></td>
              </tr>
              
              <tr>
                <td align="left" valign="top" class="body"><strong>Country:</strong></td>
                <td align="left" valign="top">
                    <select name="country" style="font-size: 14px; height: 25px; width: 200px;">
                      <option value="">Select</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->name }}" @if(!empty($datingProfile['country']) == $country->name) selected="" @endif>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong>Languages:</strong></td>
                <td align="left" valign="top">
                  <?php //echo $datingProfile->language; ?>
                    <select name="languages[]" multiple style="font-size: 14px; height: 60px; width: 200px;">
                      <option value="">Select</option>
                        @foreach($languages as $language)
                        <?php //$datingProfile->language = "Urdu"; ?>
                            <option value="{{ $language->name }}" <?php if(!empty($datingProfile->languages) && preg_match('/'.$language->name.'/i', $datingProfile->languages)){ echo "selected"; }?>> {{ $language->name }}</option>
                        @endforeach
                    </select>
                </td>
              </tr>

              
              <tr>
                <td align="left" valign="top" class="body"><strong>Hobbies:</strong></td>
                <td align="left" valign="top">
                    <select name="hobbies[]" multiple style="font-size: 14px; height: 60px; width: 200px;">
                      <option value="">Select</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->title }}" <?php if(!empty($datingProfile->hobbies) && preg_match('/'.$hobby->name.'/i', $datingProfile->hobbies)) { echo "selected"; } ?>>{{ $hobby->title }}</option>
                        @endforeach
                    </select>
                </td>
              </tr>

              <tr>
                  <td colspan="2"><h6 class="inner"> Education & Career</h6></td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong> Highest Education:</strong></td>
                <td align="left" valign="top"><input autocomplete="off" name="education" id="education" type="text" @if(!empty($datingProfile['education'])) value="{{ $datingProfile['education'] }}" @endif style="font-size: 14px; height: 25px; width: 200px;" /></td>
              </tr>
              
              <tr>
                  <td align="left" valign="top" class="body"><strong> Occupation:</strong></td>
                  <td align="left" valign="top">
                      <select name="occupation" id="" style="font-size: 14px; height: 25px; width: 200px;">
                          <option value="">Select</option>
                          <option value="Not Working"  @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Not Working") selected="" @endif>Not Working</option>
                          <option value="Teacher"  @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Teacher") selected="" @endif>Teacher</option>
                          <option value="Mechanic" @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Mechanic") selected="" @endif>Mechanic</option>
                          <option value="Model" @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Model") selected="" @endif>Model</option>
                          <option value="Politician" @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Politician") selected="" @endif>Politician</option>
                          <option value="Real Estate" @if(!empty($datingProfile['occupation']) && $datingProfile['occupation'] == "Real Estate") selected="" @endif>Real Estate</option>>
                      </select>
                  </td>
              </tr>

              <tr>
                <td align="left" valign="top" class="body"><strong> Income:</strong></td>
                <td align="left" valign="top">
                    <select name="income" style="font-size: 14px; height: 25px; width: 200px;">
                        <option value="">Select</option>
                        <option value="Under $25,000" @if(!empty($datingProfile['income']) && $datingProfile['income'] == "Under $25,000") selected="" @endif>Under $25,000</option>
                        <option value="$25,001-50,000"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$25,001-50,000") selected="" @endif>$25,001-50,000</option>
                        <option value="$50,001-75,000"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$50,001-75,000") selected="" @endif>$50,001-75,000</option>
                        <option value="$75,001-50,000"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$75,001-50,000") selected="" @endif>$75,001-100,000</option>
                        <option value="$100,001-150,000"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$100,001-150,000") selected="" @endif>$100,001-150,000</option>
                        <option value="$150,001-200,000"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$150,001-200,000") selected="" @endif>$150,001-200,000</option>
                        <option value="$200,001-above"  @if(!empty($datingProfile['income']) && $datingProfile['income'] == "$200,001-above") selected="" @endif>$200,001-above</option>
                    </select>
                </td>
              </tr>

              <tr>
                    <td colspan="2"><h6 class="inner">About MySelf</h6></td>
                </tr>
              <tr>
                  <td align="left" valign="top" class="body"><strong>About Myself: * </strong></td>
                  <td align="left" valign="top">
                    <textarea name="about_myself" style="font-size: 14px; height: 70px; width: 200px;">@if (!empty($datingProfile['about_myself'])) {{ $datingProfile['about_myself'] }}@endif</textarea>
                </td>
              </tr>

              <tr>
                  <td colspan="2"><h6 class="inner">About My Preferred Partner</h6></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="body"><strong>Partner Details: * </strong></td>
                <td align="left" valign="top">
                  <textarea name="about_partner" style="font-size: 14px; height: 75px; width: 200px;">@if (!empty($datingProfile['about_partner'])) {{ $datingProfile['about_partner'] }}@endif</textarea>
              </td>
            </tr>
              <tr>
              
                <td></td>
                <td><input type="submit" name="submit" class="button" value="Submit" /></td>
              </tr>
            </table>
          </form>
      </div>
     
    </div>
    <div class="clear"></div>
  </div>
  @endsection