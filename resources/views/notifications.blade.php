@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>My Notifications</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4">

      @if (count($unread) > 0)
        <div class="mt-3">
          <form action="/notifications" method="POST">
            @method('PATCH')
            @csrf
            @honeypot
            <button class="btn btn-block btn-primary">Mark all as read</button>
          </form>
        </div>
        @foreach ($unread as $notification)
          <div class="my-2">
            <div class="card">
              <div class="card-body">
                <div class="float-right">
                  <small>{{ $notification->created_at }}</small>
                </div>

                <div class="mt-4">
                  @if ($notification->data['type'] == 'bid_received')
                    <p>
                    You received a new bid for request <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> from <strong>{{ $notification->data['bidder_name'] }}</strong>
                    </p>
                  @elseif ($notification->data['type'] == 'bid_accepted')
                    <p>
                    Your bid for request <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> was accepted by <strong>{{ $notification->data['requester_name'] }}</strong>. You may contact them by clicking on the <strong>contact</strong> button to ensure that they're legit. You can also check their previous request history to see if they have a good reputation. Note that you only have 24 hours to fulfill the request. After that, your bid will automatically be cancelled and it will be reflected on your user profile.
                    </p>
                  @elseif ($notification->data['type'] == 'bid_no_show')
                    <p>
                    <strong>{{ $notification->data['requester_name'] }}</strong> has marked your bid for request <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> as "no show".
                    </p>
                  @elseif ($notification->data['type'] == 'bid_fulfilled')
                    <p>
                    Congrats! <strong>{{ $notification->data['requester_name'] }}</strong> has marked your bid for request <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> as "fulfilled".
                    </p>
                  @elseif ($notification->data['type'] == 'bid_cancelled')
                    <p>
                    {{ $notification->data['bidder_name'] }} cancelled their bid for request <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> because of the following reason: {{ $notification->data['cancel_reason'] }}
                    </p>
                  @endif
                </div>
              </div>
            </div>

          </div>
        @endforeach
      @endif

      @if (count($unread) == 0)
      <div class="alert alert-info mt-3">
        No notifications at this time
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
