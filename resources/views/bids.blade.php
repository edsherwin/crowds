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
        <div class="card card-main mt-1">
          <div class="card-body">
            <div class="float-right">
              <small>{{ diffForHumans($bid->order->created_at) }}</small>
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

            <div class="d-flex flex-row mt-1">
              <div>
                <img src="{{ $bid->order->user->photo }}" style="width: 50px;" class="img-thumbnail" alt="{{ $bid->order->user->name }}">
              </div>
              <div class="pl-2">
                <a href="/user/{{ $bid->order->user_id }}/reputation">{{ $bid->order->user->name }}</a>
              </div>
            </div>

            <div class="mt-1">
              {{ $bid->order->description }}
            </div>
            
            @if ($bid->status == 'accepted')
            <div class="mt-2">
              <button class="btn btn-sm btn-secondary mr-2 float-right view-contact" data-userid="{{ $bid->order->user_id }}" type="button">
                Contact
              </button>
            </div>
            @endif
          </div>
        </div>

        <div class="card offset-1 mt-1 mb-5">
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
                <div class="d-flex flex-row mt-2">
                  <div>
                    <img src="{{ $bid->user->photo }}" style="width: 50px;" class="img-thumbnail" alt="{{ $bid->user->name }}">
                  </div>

                  <div class="pl-2">
                    <div>
                      <strong>Service Fee: </strong> {{ money($bid->service_fee) }}
                    </div>

                    <div class="py-1">
                      {{ $bid->notes }}
                    </div>
                  </div>
                </div>

                @if ($bid->status == 'posted' || $bid->status == 'accepted')
                <div>
                  <button class="btn btn-sm btn-danger float-right show-bid-cancel-modal" data-id="{{ $bid->id }}">Cancel</button>
                </div>  
                @endif
               
              </div>
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
                        <input type="text" class="form-control @error('cancel_reason') is-invalid @enderror" name="cancel_reason" id="cancel_reason" placeholder="eg. I have an emergency" value="{{ old('cancel_reason') }}">

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

@include('partials.contact-modal')

@endsection

@section('foot_scripts')
<script src="{{ mix('js/bids.js') }}"></script>
<script src="{{ mix('js/view-contact.js') }}" defer></script>
@endsection
