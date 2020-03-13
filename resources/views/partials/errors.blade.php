@if(session('errors'))
     <div class="alert alert-dismissable alert-danger col-md-9 col-lg-9">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true"> &times; </span>
          </button>
    <li><strong>{!! session()->get('errors') !!} </strong></li>
     </div>
@endif