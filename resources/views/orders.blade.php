@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-4 mt-5">
    @if (count($orders) > 0)
      @foreach ($orders as $order)
        <div class="my-3">
          <div class="card">
            <div class="card-body">
              <div class="float-right">
                <small>{{ $order->created_at }}</small>
              </div>

              <div class="mt-1">
                <h6>Order #{{ orderNumber($order->id) }}</h6>
                
                @if ($order->bids->count())
                <div class="mb-2">
                  <span class="badge badge-pill badge-primary">{{ $order->bids->count() }} bids</span>
                </div>
                @endif

                <p>
                {{ $order->description }}
                </p>

              </div>
            </div>
          </div>
        
          @if (!is_null($order->bids))
            <div class="bids clearfix">
              @foreach ($order->bids as $bid)
              <div class="card mt-1 offset-md-2">
                <div class="card-body">
                  <div>
                  {{ $bid->user->name }}
                  </div>

                  <div class="mt-2">
                    <strong>Service Fee: </strong> {{ money($bid->service_fee) }}
                  </div>

                  <div>
                    {{ $bid->notes }}
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          @endif

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
@endsection