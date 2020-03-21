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
        <div class="card mt-1">
          <div class="card-body">
            <div class="float-right">
              <small>{{ $bid->order->created_at }}</small>
            </div>

            <div class="mt-1">
              <h6>
                Order #{{ orderNumber($bid->order->id) }}
                @if ($bid->status == 'accepted')
                <span class="badge badge-pill badge-warning">accepted</span>
                @endif

                @if ($bid->status == 'no_show')
                <span class="badge badge-pill badge-danger">no show</span>
                @endif

                @if ($bid->status == 'fulfilled')
                <span class="badge badge-pill badge-success">fulfilled</span>
                @endif

                @if ($bid->status == 'cancelled')
                <span class="badge badge-pill badge-danger">cancelled</span>
                @endif
              </h6>
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
            
            @if ($bid->status == 'posted' || $bid->status == 'accepted')
            <button class="btn btn-sm btn-danger float-right" data-id="{{ $bid->id }}" id="show-bid-cancel-modal">Cancel</button>
            @endif

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

<div class="modal fade" id="bid-cancel-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cancel Bid</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            <form action="#" method="POST" id="bid-cancel-form">
                @method('PATCH')
                @csrf
                @honeypot
                <div class="container">
                    <div class="form-group row">
                      <label for="cancel_reason" class="col-sm-5 col-form-label">Cancellation Reason</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control @error('cancel_reason') is-invalid @enderror" name="cancel_reason" id="cancel_reason" placeholder="eg. The dog ate my homework" value="{{ old('cancel_reason') }}">

                        @error('cancel_reason')
                          <span class="invalid-feedback bid-modal-error" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    </div>

                    <button class="btn btn-danger float-right">Cancel Bid</button>
                </div>
            </form>
        </div>
      </div>

  </div>
</div>
@endsection

@section('foot_scripts')
<script src="{{ mix('js/bids.js') }}"></script>
@endsection
