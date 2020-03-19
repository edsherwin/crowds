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
        				<button class="btn btn-sm btn-success bid" data-recipient="{{ $order->user->name }}" data-description="{{ $order->description }}" data-datetime="{{ $order->created_at }}">Bid</button>
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
@endsection


