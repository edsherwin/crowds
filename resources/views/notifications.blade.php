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
                    You received a new bid for order <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> from <strong>{{ $notification->data['bidder_name'] }}</strong>
                    </p>
                  @elseif ($notification->data['type'] == 'bid_accepted')
                    <p>
                    Your bid for order <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> was accepted by <strong>{{ $notification->data['requester_name'] }}</strong>. You can now proceed with fulfilling the request.
                    </p>
                  @elseif ($notification->data['type'] == 'bid_no_show')
                    <p>
                    <strong>{{ $notification->data['requester_name'] }}</strong> has marked your bid for order <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> as "no show".
                    </p>
                  @elseif ($notification->data['type'] == 'bid_fulfilled')
                    <p>
                    <strong>{{ $notification->data['requester_name'] }}</strong> has marked your bid for order <strong>#{{ orderNumber($notification->data['order_id']) }}</strong> as "fulfilled".  
                    </p>
                  @elseif ($notification->data['type'] == 'bid_cancelled')
                    <p>
                    {{ $notification->data['bidder_name'] }} cancelled their bid for order <strong>#{{ orderNumber($notification->data['order_id']) }}</strong>
                    </p>
                  @endif
                </div>
              </div>
            </div>

          </div>
        @endforeach
      @endif

      @if (count($unread) == 0)
      <div class="alert alert-info">
        No notifications at this time
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
