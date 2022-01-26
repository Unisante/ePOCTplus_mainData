@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><a href="{{route('audits.index')}}" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($audit)
              <div class="card card-color2 test-white">
                <div class="card-header">Audit {{$audit->id}}'s Details</div>
                <div class="card-body">
                  <div class="d-flex justify-content-between"> <span>Id:                </span> <span class="border-bottom">{{$audit->id}}              </span></div>
                  <div class="d-flex justify-content-between"> <span>Auditable id:      </span> <span class="border-bottom">{{$audit->auditable_id}}    </span></div>
                  <div class="d-flex justify-content-between"> <span>Auditable type:    </span> <span class="border-bottom">{{$audit->auditable_type}}  </span></div>
                  <div class="d-flex justify-content-between"> <span>Event:             </span> <span class="border-bottom">{{$audit->event}}           </span></div>
                  <div class="d-flex justify-content-between"> <span>Old values:        </span> <span class="border-bottom">{{$audit->old_values}}      </span></div>
                  <div class="d-flex justify-content-between"> <span>New values:        </span> <span class="border-bottom">{{$audit->new_values}}      </span></div>
                  <div class="d-flex justify-content-between"> <span>Created at:        </span> <span class="border-bottom">{{$audit->created_at}}      </span></div>
                  <div class="d-flex justify-content-between"> <span>Updated at:        </span> <span class="border-bottom">{{$audit->updated_at}}      </span></div>
                  <div class="d-flex justify-content-between"> <span>Url:               </span> <span class="border-bottom">{{$audit->url}}             </span></div>
                  <div class="d-flex justify-content-between"> <span>Ip address:        </span> <span class="border-bottom">{{$audit->ip_address}}      </span></div>
                  <div class="d-flex justify-content-between"> <span>User id:           </span> <span class="border-bottom">{{$audit->user_id}}         </span></div>
                  <div class="d-flex justify-content-between"> <span>User agent:        </span> <span class="border-bottom">{{$audit->user_agent}}      </span></div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
