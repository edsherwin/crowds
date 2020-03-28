@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>Account Settings</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4 mt-3">
      @include('partials.alert')

      <form action="/account/password" method="POST">
        @method('PATCH')
        @csrf
        @honeypot
        <div class="form-group row">
          <label for="current_password" class="col-sm-4 col-form-label">Current Password</label>
          <div class="col-sm-8 mt-2">
            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password">

            @error('current_password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-4 col-form-label">New Password</label>
          <div class="col-sm-8 mt-2">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password">

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="password_confirmation" class="col-sm-4 col-form-label">Confirm Password</label>
          <div class="col-sm-8 mt-2">
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-12">
            <button class="btn btn-primary float-right">Update Account</button>
          </div>
        </div>
      </form>
      
      <hr>

    </div>
  </div>


  <div class="row justify-content-center">
    <div class="col-md-4 mt-3">

      <form action="/account/contact" method="POST">
        @method('PATCH')
        @csrf
        @honeypot
        <div class="form-group row">
          <label for="phone_number" class="col-sm-4 col-form-label">Phone number</label>
          <div class="col-sm-8">
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" value="{{ old('phone_number', $detail->phone_number) }}">
            
            @error('phone_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="messenger_id" class="col-sm-4 col-form-label">Messenger ID</label>
          <div class="col-sm-8">
            <input type="text" class="form-control @error('messenger_id') is-invalid @enderror" name="messenger_id" id="messenger_id" value="{{ old('messenger_id', $detail->messenger_id) }}">
            
            @error('messenger_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-12 mt-2">
            <button class="btn btn-primary float-right">Update Contact Details</button>
          </div>
        </div>
      </form>
      
      <hr>

    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4 mt-3">

      <form action="/account/notifications" method="POST">
        @method('PATCH')
        @csrf
        @honeypot

        <fieldset class="form-group">
          <div class="row">
            <legend class="col-form-label col-sm-4 pt-0">Notifications</legend>
            <div class="col-sm-8">
              
              <div class="custom-control custom-switch">

                <input type="checkbox" class="custom-control-input" name="new_order" id="new_order" {{ isChecked(old('new_order', $settings->is_orders_notification_enabled)) }}>
                <label class="custom-control-label" for="new_order">New orders</label>
              </div>

              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="new_bid" id="new_bid" {{ isChecked(old('new_bid', $settings->is_bid_notification_enabled)) }}>
                <label class="custom-control-label" for="new_bid">New bid</label>
              </div>

              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="bid_accepted" id="bid_accepted" {{ isChecked(old('bid_accepted', $settings->is_bid_accepted_notification_enabled)) }}>
                <label class="custom-control-label" for="bid_accepted">Bid accepted</label>
              </div>

              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="bid_cancelled" id="bid_cancelled" {{ isChecked(old('bid_cancelled', $settings->is_bid_cancelled_notification_enabled)) }}>
                <label class="custom-control-label" for="bid_cancelled">Bid cancelled</label>
              </div>

            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12 mt-4">
              <button class="btn btn-primary float-right">Update Notifications</button>
            </div>
          </div>
        </fieldset>
      </form>

    </div>
  </div>
</div>
@endsection
