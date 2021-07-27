@include('partials.errors')
@include('partials.success')


<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <!-- Example row of columns -->
  <h3 align="center">Showing {{$name}} {{$instance[$identifierField]}}</h3>
  <div class="pull-left">
    <a class="btn btn-primary" href="{{ route($url . '.index') }}">Back</a>
  </div>
  <div class="row col-sm-12 col-md-12 col-lg-12" pull-center style="background:white; margin: 10px">
      @foreach ($inputs as $key => $value)
      <div class="form-group row">
      <div class="col-md-9">
        <label for={{$value}} class="col-md-3 col-form-label text-md-right">{{$key}}<span class="required"></span></label>
      </div>
        <div class="col-md-9">
          <input type="text" class="form-control" value="{{$instance[$value]}}" disabled >
        </div>
      </div>
      @endforeach
  </div>
</div>
