@extends('layouts.app')

@section('head_scripts')
<script>
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


window.fbAsyncInit = function() {
  FB.init({
    appId      : '568991763714607',
    cookie     : true,
    xfbml      : true,
    version    : 'v6.0'
  });
    
  FB.AppEvents.logPageView();  
};


function checkLoginState() {
  FB.getLoginStatus(function(response) {
    if (response.status === 'connected') { 
      FB.api(
        '/me/picture',
        'GET',
        {"redirect":"false", "type": "large"},
        function(response) {
          if (response.data) {
            $('#_fb_profile_pic').val(response.data.url);
            $('#step-two-form').trigger('submit');
          }
        }
      );
    }
  });
}
</script>
@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>Orders Feed @if (Auth::user()->barangay) (Brgy. {{ Auth::user()->barangay->name }}) @endif</h5>
    </div>
  </div>

  <input type="hidden" id="setup_step" value="{{ Auth::user()->setup_step }}">

  @if (Auth::user()->setup_step == 3)
  <div class="row justify-content-center">
  <div class="col-md-4 mt-3">
    @include('partials.alert')
    
    
    <form method="POST" action="/order/create">
      @csrf
      @honeypot
      <div class="form-group">
          <label for="description">What do you need?</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="12 eggs, 1 loaf bread, 1 fresh milk"></textarea>

        @error('description')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
      <button class="btn btn-primary float-right">Post</button>
    </form>
  </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4 mt-5">
      @if (count($orders) > 0)
        @foreach ($orders as $order)
          <div class="my-3">
            <div class="card">
              <div class="card-body">
               
                <div class="float-right">
                  <small>{{ diffForHumans($order->created_at) }}</small>
                </div>
                
                <div class="d-flex flex-row mt-1">
                  <div>
                    <img src="{{ $order->user->photo }}" style="width: 50px;" class="img-thumbnail" alt="{{ $order->user->name }}">
                  </div>
                  <div class="pl-2">
                    <a href="/user/{{ $order->user_id }}/reputation">{{ $order->user->name }}</a>
                    <div>
                      @if ($order->postedBids->count())
                      <span class="badge badge-pill badge-primary">{{ $order->postedBids->count() }} {{ Str::plural('bid', $order->postedBids->count()) }}</span>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="mt-1">
                  <p>
                  {{ $order->description }}
                  </p>

                  @if (Auth::id() != $order->user->id && Auth::user()->hasNoBids($order->postedBids) && $order->postedBids->count() < 10)
                  <button class="btn btn-sm btn-success float-right bid" data-id="{{ $order->id }}" data-recipient="{{ $order->user->name }}" data-description="{{ $order->description }}" data-datetime="{{ $order->created_at }}" data-friendlydatetime="{{ friendlyDatetime($order->created_at) }}">Bid</button>
                  @endif

                </div>

              </div>
            </div>
          
          </div>
        @endforeach

        <div>
        {{ $orders->links() }}
        </div>
      @endif

      @if (count($orders) == 0)
      <div class="alert alert-info">
        No orders in your neighborhood yet.
      </div>
      @endif
    </div>
  </div>
  @else
  <div class="row justify-content-center">
    <div class="col-md-4 mt-3">
      <div class="alert alert-warning">
      You need to setup your account first before you can start posting or bidding.
      </div>
    </div>
  </div>
  @endif

</div>

@if (Auth::user()->setup_step == 0)
<div class="modal" id="user-setup-modal-0" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Step 1: Where do you live?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="/setup/step-one" method="POST">
        @method('PATCH')
        @csrf
        @honeypot
        <div class="modal-body">
          
          <div class="form-group row">
              <label for="province" class="col-md-4 col-form-label text-md-right">{{ __('Province') }}</label>

              <div class="col-md-6">
                  <select name="province" id="province" class="form-control @error('province') is-invalid @enderror">
                      <option value="">Select province</option>
                      @foreach($provinces as $province)
                      <option value="{{ $province->id }}">{{ $province->name }}</option>
                      @endforeach
                  </select>

                  @error('province')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="form-group row">
              <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>

              <div class="col-md-6">
                  <select name="city" id="city" class="form-control @error('city') is-invalid @enderror">
                    
                  </select>

                  @error('city')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="form-group row">
              <label for="barangay" class="col-md-4 col-form-label text-md-right">{{ __('Barangay') }}</label>

              <div class="col-md-6">
                  <select name="barangay" id="barangay" class="form-control @error('barangay') is-invalid @enderror">
                      
                  </select>

                  @error('barangay')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Next</button>
        </div>
      </form>

    </div>
  </div>
</div>
@endif

@if (Auth::user()->setup_step == 1)
<div class="modal" id="user-setup-modal-1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Step 2: Connect Facebook Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
        Your Facebook account will be used to validate your identity. Your profile picture will be used for your posts.
        </div>
      
        <div class="row justify-content-center">
         
          <div class="fb-login-button" data-width="" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true" data-scope="public_profile,email" data-onlogin="checkLoginState();"></div>

        </div>

      </div>
      <div class="modal-footer">
        <form action="/setup/back" method="POST">
          @method('PATCH')
          @csrf
          @honeypot
          <button class="btn btn-secondary">Back</button>
        </form>

        <form action="/setup/step-two" method="POST" id="step-two-form">
          @method('PATCH')
          @csrf
          @honeypot
          <input type="hidden" name="_fb_profile_pic" id="_fb_profile_pic">
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@if (Auth::user()->setup_step == 2)
<div class="modal" id="user-setup-modal-2" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Step 3: How do we contact you?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          Your phone number or your Facebook Messenger ID will be used to facilitate communication between users. This information will only be available to both parties once a request has been accepted.
        </div>
        
        <form action="/setup/step-three" method="POST" id="user-contact-form">
          @method('PATCH')
          @csrf
          @honeypot
          <div class="form-group row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

              <div class="col-md-6">
                  <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', Auth::user()->detail->phone_number) }}" autocomplete="off">

                  @error('phone_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="form-group row">
              <label for="messenger_id" class="col-md-4 col-form-label text-md-right">{{ __('Messenger ID') }}</label>

              <div class="col-md-6">
                  <input id="messenger_id" type="text" class="form-control @error('messenger_id') is-invalid @enderror" name="messenger_id" value="{{ old('messenger_id', Auth::user()->detail->messenger_id) }}" autocomplete="off">

                  @error('messenger_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        
        <form action="/setup/back" method="POST">
          @method('PATCH')
          @csrf
          @honeypot
          <button class="btn btn-secondary">Back</button>
        </form>

        <button class="btn btn-primary" form="user-contact-form">Finish Setup</button>
      </div>
    </div>
  </div>
</div>
@endif

@if (Auth::user()->setup_step == 3)
<div class="modal fade" id="bid-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <form action="/bid/create" method="POST">
                @csrf
                @honeypot
                <input type="hidden" name="order_id" id="order_id" value="{{ old('order_id') }}">
                <input type="hidden" name="order_recipient" id="order_recipient" value="{{ old('order_recipient') }}">
                <input type="hidden" name="order_description" id="order_description" value="{{ old('order_description') }}">
                <input type="hidden" name="order_created_at" id="order_created_at" value="{{ old('order_created_at') }}">

                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Recipient</strong>
                        </div>
                        <div class="col-md-8 mt-1" id="order-recipient">
                        {{ old('order_recipient') }}
                      </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <strong>Description</strong>
                        </div>
                        <div class="col-md-8 mt-1 mb-3" id="order-description">
                        {{ old('order_description') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <strong>Posted at</strong>
                        </div>
                        <div class="col-md-8 mt-2 mb-3" id="order-datetime">
                        {{ old('order_created_at') }}
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="service_fee" class="col-sm-4 col-form-label">How much will you charge?</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control @error('service_fee') is-invalid @enderror" name="service_fee" id="service_fee" placeholder="eg. 200" value="{{ old('service_fee') }}">

                        @error('service_fee')
                          <span class="invalid-feedback bid-modal-error" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="notes" class="col-sm-4 col-form-label">Notes</label>
                      <div class="col-sm-8">
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3" placeholder="eg. I can do this in 1 hour. I'm wearing blue shirt.">{{ old('notes') }}</textarea>

                        @error('notes')
                          <span class="invalid-feedback bid-modal-error" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    </div>

                    <button class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>
      </div>

  </div>
</div>
@endif
@endsection

@section('foot_scripts')
<script src="{{ mix('js/orders-feed.js') }}"></script>
@endsection