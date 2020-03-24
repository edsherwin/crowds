@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>My Orders</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4">
      @include('partials.alert')

      @if (count($orders) > 0)
        @foreach ($orders as $order)
          <div class="my-3">
            <div class="card">
              <div class="card-body">
                <div class="float-right">
                  <small>{{ diffForHumans($order->created_at) }}</small>
                </div>

                <div class="mt-1">
                  <h6>
                    Order #{{ orderNumber($order->id) }}
                    @if ($order->status == 'accepted')
                      <span class="badge badge-pill badge-warning">accepted</span>
                    @elseif ($order->status == 'fulfilled')
                      <span class="badge badge-pill badge-success">fulfilled</span>
                    @else
                      @if ($order->bids->count())
                      <span class="badge badge-pill badge-primary">{{ $order->bids->count() }} bids</span>
                      @endif
                    @endif
                  </h6>

                  <p>
                  {{ $order->description }}
                  </p>

                </div>
              </div>
            </div>

            @if (!is_null($order->bids))
              <div class="bids clearfix">
                @foreach ($order->bids as $bid)
                <div class="card mt-1 offset-1">
                  <div class="card-body">

                    <div class="row">
                      <div class="col-md-12">
                        <div class="float-right">
                        <small>{{ diffForHumans($bid->created_at) }}</small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div>
                          <div class="d-flex flex-row mt-2">
                            <div>
                              <img src="{{ $bid->user->photo }}" style="width: 50px;" class="img-thumbnail" alt="{{ $bid->user->name }}">
                            </div>

                            <div class="pl-2">
                              <a href="/user/{{ $bid->user_id }}/reputation">{{ $bid->user->name }}</a>
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
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="mt-2">
                      <strong>Service Fee: </strong> {{ money($bid->service_fee) }}
                    </div>

                    <div class="py-1">
                      {{ $bid->notes }}
                    </div>

                    @if ($order->status == 'posted' && $bid->status == 'posted')
                    <form action="/bid/{{ $bid->id }}/accept" method="POST">
                      @method('PATCH')
                      @csrf
                      @honeypot
                      <input type="hidden" name="_bidder" value="{{ $bid->user->name }}">
                      <input type="hidden" name="_order_id" value="{{ $order->id }}">
                      <button type="button" class="btn btn-sm btn-primary float-right accept-bid" data-bidder="{{ $bid->user->name }}">Accept</button>
                    </form>
                    @endif

                    @if ($bid->status == 'accepted')
                    <div class="mt-2">
                      <div class="float-right">
                        <form action="/bid/{{ $bid->id }}/fulfilled" method="POST">
                          @method('PATCH')
                          @csrf
                          @honeypot
                          <input type="hidden" name="_order_id" value="{{ $order->id }}">
                          <button type="button" class="btn btn-sm btn-success mark-as-fulfilled">Fulfilled</button>
                        </form>
                      </div>

                      <div class="float-right">
                        <form action="/bid/{{ $bid->id }}/no_show" method="POST">
                          @method('PATCH')
                          @csrf
                          @honeypot
                          <input type="hidden" name="_order_id" value="{{ $order->id }}">
                          <button type="button" class="btn btn-sm btn-danger mr-2 mark-as-noshow">No show</button>
                        </form>
                      </div>

                      <div class="float-right">
                        <button class="btn btn-sm btn-secondary mr-2 view-contact" data-userid="{{ $bid->user->id }}" type="button">
                          Contact
                        </button>
                      </div>
                    </div>
                    @endif
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
        You haven't posted any orders yet.
      </div>
      @endif
    </div>
  </div>
</div>

@include('partials.contact-modal')
@endsection

@section('foot_scripts')
<script src="{{ mix('js/orders.js') }}" defer></script>
<script src="{{ mix('js/view-contact.js') }}" defer></script>
@endsection
