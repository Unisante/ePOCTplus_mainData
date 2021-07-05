@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">

<!-- Example row of columns -->
<h3 align="center">Edit {{$name}} </h3>
    <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form method="post" action="{{route($url . '.update',$instance->id)}}">
        {{csrf_field()}}
        {{ method_field('PUT') }}
        @foreach ($inputs as $key => $input)
        <div class="form-group row">
        <label for={{$input['keyword']}} class="col-md-3 col-form-label text-md-right">{{$key}}</label>
        <div class="col-md-6">
            @if ($input['type'] == 'dropdown')
            <select class="form-control" name={{$input['keyword']}} id={{$input['keyword']}}>
            @foreach ($input['options'] as $option)
            @if ($option == $instance[$input['keyword']])
            <option value={{$option}} selected="selected">{{$option}}</option>
            @else
            <option value={{$option}}>{{$option}}</option>
            @endif
            @endforeach
            </select> 
            @endif

            @if ($input['type'] == 'text') 
            <input id={{$input['keyword']}} 
                    type="text" 
                    class="form-control"
                    name={{$input['keyword']}} 
                    value={{$instance[$input['keyword']]}}
                    required autocomplete={{$input['keyword']}} 
                    autofocus >    
            @endif
        </div>
           
            
        </div>
        @endforeach
        <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
        </div>
    </form>
    </div>  