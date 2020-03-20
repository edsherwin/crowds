@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>Orders Feed</h5>
    </div>
  </div>

  <div class="row justify-content-center">
	<div class="col-md-4 mt-3">
		@include('partials.alert')

		<form method="POST" action="/order/create">
			@csrf
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
        	<div class="card mb-2">
        		<div class="card-body">
        			<div class="float-right">
        				<small>{{ $order->created_at }}</small>
        			</div>

        			<div class="mt-1">
        				<h6>{{ $order->user->name }}</h6>

        				<p>
        				{{ $order->description }}
        				</p>

                @if (Auth::id() != $order->user->id)
        				<button class="btn btn-sm btn-success bid" data-id="{{ $order->id }}" data-recipient="{{ $order->user->name }}" data-address="{{ $order->user->detail->address }}" data-description="{{ $order->description }}" data-datetime="{{ $order->created_at }}">Bid</button>
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
    		No orders in your neighborhood yet.
    	</div>
      	@endif
  	</div>
  </div>
</div>

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
                <input type="hidden" name="order_id" id="order_id" value="{{ old('order_id') }}">
                <input type="hidden" name="order_recipient" id="order_recipient" value="{{ old('order_recipient') }}">
                <input type="hidden" name="order_address" id="order_address" value="{{ old('order_address') }}">
                <input type="hidden" name="order_description" id="order_description" value="{{ old('order_description') }}">
                <input type="hidden" name="order_created_at" id="order_created_at" value="{{ old('order_created_at') }}">

                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Recipient</strong>
                        </div>
                        <div class="col-md-8" id="order-recipient">
                        {{ old('order_recipient') }}
                      </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <strong>Address</strong>
                        </div>
                        <div class="col-md-8 mt-2" id="order-address">
                        {{ old('order_address') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <strong>Description</strong>
                        </div>
                        <div class="col-md-8 mt-2 mb-3" id="order-description">
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
@endsection

@section('foot_scripts')
<script src="{{ mix('js/orders-feed.js') }}"></script>
@endsection


