@extends('front.layout.master')
@section('title',"Track your package | ")
@section('body')
    <div class="container-fluid">

        <div class="p-3 bg-white package-cn">
            <h4>{{ __('Track Package') }}</h4>
            <hr>
            <form method="get" action="{{url('/awb').'/'.$random}}">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="awb">AWB</label>
                    <input type="text" class="form-control" id="awb" name="awb" placeholder="AWB" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
@endsection