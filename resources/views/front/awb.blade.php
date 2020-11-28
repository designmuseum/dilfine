@extends('front.layout.master')
@section('title',"Track my package | ")
@section('body')
    <div class="container-fluid">

        <div class="p-3 bg-white awb-cn">
            <h4>{{ __('Track Package') }}</h4>
            <ul class="list-group ul-one-cn">
                <li class="list-group-item"><span>Trackng ID</span> <span>{{$track_data['awb']}}</span></li>
                <li class="list-group-item"><span>Status</span> <span>{{$track_data['current_status']}}</span></li>
<!--                <li class="list-group-item">current_timestamp :-  {{$track_data['current_timestamp']}}</li>-->
<!--                <li class="list-group-item">order_id :: {{$track_data['order_id']}}</li>-->
                <li class="list-group-item"><span>Arriving by</span> <span>{{$track_data['etd']}}</span></li>
            </ul>
			<h3>Scans</h3>
			  <ul class="list-group ul-two-cn">
                        @foreach($track_data['scans'] as $scan)
                            <li class="list-group-item">
                                <ul class="list-group">
                                    <li class="list-group-item"><span>Date</span>    <span>{{$scan['date']}}</span></li>
                                    <li class="list-group-item"><span>Activity</span>  <span>{{$scan['activity']}}</span></li>
                                    <li class="list-group-item"><span>Location</span> <span>{{$scan['location']}}</span></li>
                                </ul>
                            </li>
                        @endforeach
                    </ul>
        </div>

    </div>
@endsection