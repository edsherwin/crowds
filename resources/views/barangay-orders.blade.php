@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-4">
			<h5>My Barangay Requests (Brgy. {{ Auth::user()->barangay->name }})</h5>
		</div>
	</div>
  
  <div class="row justify-content-center">
    <div class="col-md-4 mt-3">
    @if (Auth::user()->user_type == 'user')
	
  		@include('partials.alert')

  		<form method="POST" action="/barangay-order/create">
  			@csrf
  			@honeypot
  			<div class="form-group">
  				<label for="description">What do you need?</label>
  				<textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="eg. Relief goods.."></textarea>

  				@error('description')
  				<span class="invalid-feedback" role="alert">
  				<strong>{{ $message }}</strong>
  				</span>
  				@enderror
  			</div>
  			<button class="btn btn-primary float-right">Post</button>
  		</form>
    @else
      @include('partials.alert')
    @endif
    </div>
  </div>

	<div class="row justify-content-center">
    <div class="col-md-4 mt-2">
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
                    @if ($order->status == 'fulfilled')
                    <div>
                      <span class="badge badge-pill badge-success">fulfilled</span>
                    </div>
                    @endif
                  </div>
                </div>

                <div class="mt-1">
                  <p>
                  {{ $order->description }}
                  </p>
                </div>
                
                @if (Auth::user()->user_type == 'officer')
                <div class="mt-1">
                  <form action="/barangay-order/fulfilled" method="POST">
                    @method('PATCH')
                    @csrf
                    @honeypot
                    <input type="hidden" name="_order_id" value="{{ $order->id }}">
                    <button type="button" class="btn btn-sm btn-success float-right fulfill-order" data-user="{{ $order->user->name }}">Fulfill</button>
                  </form>
                  
                  <button class="btn btn-sm btn-secondary mr-2 float-right view-contact" data-userid="{{ $order->user_id }}" type="button">
                    Contact
                  </button>
                </div>
                @endif

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
        No requests yet.
      </div>
      @endif
    </div>
	</div>	
</div>

@include('partials.contact-modal')
@endsection

@section('foot_scripts')
<script src="{{ mix('js/barangay-orders.js') }}" defer></script>
<script src="{{ mix('js/view-contact.js') }}" defer></script>
@endsection