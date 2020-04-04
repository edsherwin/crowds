@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h5>User Reputation</h5>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">

          <div class="d-flex flex-row mt-1">
            <div>
              <img src="{{ $user->photo }}" style="width: 50px;" class="img-thumbnail" alt="{{ $user->name }}">
            </div>
            <div class="pl-2">
              <strong>{{ $user->name }}</strong>
            </div>
          </div>
          
          <div class="mt-3">
            <div>
              <div>
                <strong>Requests</strong>
              </div>

              <table class="table table-sm mt-1">
                <thead>
                  <tr>
                    <th>Created</th>
                    <th>Fulfilled</th>
                    <th>No Show</th>
                    <th>Cancelled</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $user->orders->count() }}</td>
                    <td>{{ filterByStatus($user->orders, 'fulfilled') }}</td>
                    <td>{{ filterByStatus($user->orders, 'no_show') }}</td>
                    <td>{{ filterByStatus($user->orders, 'cancelled') }}</td>
                  </tr>
                </tbody>
              </table>  
            </div>

            <div>
              <div>
                <strong>Bids</strong>
              </div>

              <table class="table table-sm mt-1">
                <thead>
                  <tr>
                    <th>Submitted</th>
                    <th>Fulfilled</th>
                    <th>No Show</th>
                    <th>Cancelled</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $user->bids->count() }}</td>
                    <td>{{ filterByStatus($user->bids, 'fulfilled') }}</td>
                    <td>{{ filterByStatus($user->bids, 'no_show') }}</td>
                    <td>{{ filterByStatus($user->bids, 'cancelled') }}</td>
                  </tr>
                </tbody>
              </table>  
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

