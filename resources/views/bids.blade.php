@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>My Bids</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4">
      @include('partials.alert')

      @if (count($bids) > 0)
      <div class="bids clearfix">
        @foreach ($bids as $bid)
        <div class="card mt-1 offset-md-2">
          <div class="card-body">
            <div class="float-right">
              <small>{{ $bid->order->created_at }}</small>
            </div>

            <div class="mt-1">
              <h6>
                Order #{{ orderNumber($bid->order->id) }}
              </h6>

              @if ($bid->status == 'accepted')
              <span class="badge badge-pill badge-warning">accepted</span>
              @endif

              @if ($bid->status == 'no_show')
              <span class="badge badge-pill badge-danger">no show</span>
              @endif

              @if ($bid->status == 'fulfilled')
              <span class="badge badge-pill badge-success">fulfilled</span>
              @endif
            </div>

            <hr>

            <div>
              <strong>{{ $bid->order->user->name }}</strong>
            </div>

            <div>
              {{ $bid->order->description }}
            </div>

            <hr>

            <div>
              <strong>Service Fee: </strong> {{ money($bid->service_fee) }}
            </div>

            <div class="py-1">
              {{ $bid->notes }}
            </div>

          </div>
        </div>
        @endforeach
      </div>
      @else
      <div class="alert alert-info">
        You haven't submitted any bids yet.
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
