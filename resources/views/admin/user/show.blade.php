@extends("admin/layouts.master")
@section('title','All Customers |')
@section("body")


<div class="box">
  <div class="box-header with-border">
    
    

    <div class="box-title">{{ $pagetitle }}</div>
    <div class="pull-right col-md-4 input-group">
      <a href=" {{url('admin/users/create')}} " class="btn btn-success owtbtn">+ Add Customer/Seller/Admin</a>
      <span class="input-group-addon" id="basic-addon1">
        <i class="fa fa-filter" aria-hidden="true"></i>
      </span>
      <select name="filter" id="filter" class="filter form-control select2">
        <option {{ Cookie::get('user_selection') == 'all' ? 'selected' : ""}} value="all">Show All Users</option>
        <option {{ Cookie::get('user_selection') == 'seller' ? 'selected' : ""}} value="seller">Only Sellers</a></option>
        <option {{ Cookie::get('user_selection') == 'admins' ? 'selected' : ""}} value="admins">Only Admins</option>
        <option {{ Cookie::get('user_selection') == 'customer' ? 'selected' : ""}} value="customer">Only Customers</option>
      </select>
    </div>

    


  </div>

  <div class="box-body">

    <table id="userTable" class="table table-hover table-responsive width100">
      <thead>
        <tr class="table-heading-row">
          <th>ID</th>
          <th>User Image</th>
          <th>User Detail</th>
          <th>Added / Updated On</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>

      </tbody>

    </table>
  </div>
</div>

@endsection
@section('custom-script')
@include('admin.user.userscript')
@endsection