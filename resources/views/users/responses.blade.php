<?php use App\User; ?>
@extends('layouts.frontLayout.front_design')
@section('content')
<div id="right_container">
    <div style="">
      <h1>Responses </h1>
        <table id="responses" class="display" style="width:98%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Response</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($responses as $response)
                <?php 
                    $sender_name = User::getName($response->sender_id);
                    $sender_city = User::getCity($response->sender_id);
                ?>
                    <tr align="center">
                        <td>{{ $sender_name }}</td>
                        <td>{{ $sender_city }}</td>
                        <td>{{ $response->message }}</td>
                        <td>{{ $response->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
  </div>
  @endsection