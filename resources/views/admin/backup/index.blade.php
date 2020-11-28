@extends('admin.layouts.master')
@section('title','Backup Manager | ')
@section('body')
<div class="box">
    <div class="box-header with-border">
        <div class="box-title">
            {{ __('Backup Manager') }}
        </div>
    </div>

    <div class="box-body">

        <div class="well">

            <div class="row">

                <div class="col-md-8">

                    <ul>
                        <li>
                            {{ __('It will generate full backup of your site and exclude some folder which is vendor and node_module') }}
                        </li>
                        <li>
                            Make sure mysql dump is enabled on your server for database backup and before run this or
                            run only database backup command make sure you save the mysql dump path in
                            <b>config/database.php</b>.
                        </li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <a href="{{ url('admin/backups/process?type=all') }}" class="btn btn-md btn-success">
                        <i class="fa fa-refresh"></i> {{ __('Genrate full site backup') }}
                    </a>
                </div>

                <div class="col-md-8">

                    <ul>
                        <li>
                            {{ __('It will generate only site backup of your script and exclude some folder which is vendor and node_module') }}
                        </li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <a href="{{ url('admin/backups/process?type=onlyfiles') }}" class="btn btn-md btn-success">
                        <i class="fa fa-refresh"></i> {{ __('Genrate only site backup') }}
                    </a>
                </div>

                <div class="col-md-8">

                    <ul>
                        <li>
                            {{ __('It will generate only database backup of your site.') }}
                        </li>

                        <li>
                            Make sure mysql dump is enabled on your server for database backup and before run this or
                            run only database backup command make sure you save the mysql dump path in
                            <b>config/database.php</b>.
                        </li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <br>
                    <a href="{{ url('admin/backups/process?type=onlydb') }}" class="btn btn-md btn-success">
                        <i class="fa fa-refresh"></i> {{ __('Genrate only Database backup') }}
                    </a>
                </div>

            </div>


        </div>

        {!! $html !!}
    </div>
</div>
@endsection