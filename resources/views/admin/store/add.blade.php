@extends("admin/layouts.master")
@section('title',"Create New Store |")
@section("body")
<div class="box">
  <div class="box-header with-border">

    <h3 class="box-title">Add New Store</h3>
    <a href=" {{url('admin/stores')}} " class="btn btn-success pull-right owtbtn">
      < Back</a> </div> <div class="box-body">
        <form id="demo-form2" method="post" enctype="multipart/form-data" action="{{url('admin/stores')}}"
          data-parsley-validate>
          {{csrf_field()}}
          <div class="row">

            <div class="col-md-4">

              <label class="control-label" for="first-name">
                User Name: <span class="required">*</span>
              </label>

              <select required name="user_id" class="form-control select2">
                <option value="">Please Choose Store Owner</option>
                @foreach($users as $user)
                  <optgroup label="{{ $user->email }}">
                  <option {{ old('user_id') == $user->id ? "selected" : "" }}  value="{{$user->id}}"> {{$user->name}}</option>
                  </optgroup>
                @endforeach
              </select>

            </div>

            <div class="col-md-4">

              <label class="control-label" for="first-name">
                Store name: <span class="required">*</span>
              </label>


              <input placeholder="Please enter store name" type="text" id="first-name" name="name" class="form-control"
                value="{{old('name')}}">
            </div>

            <div class="col-md-4">
              <label class="control-label" for="first-name">
                Business Email: <span class="required">*</span>
              </label>


              <input placeholder="Please enter buisness email" type="email" id="first-name" name="email"
                class="form-control" value="{{old('email')}}">

            </div>

            <div class="last_btn col-md-4">

              <label class="control-label" for="first-name">
                VAT/GSTIN No:
              </label>


              <input placeholder="Please enter your GSTIN/VAT No." type="text" name="vat_no" class="form-control"
                value="{{old('vat_no')}}">
            </div>

            <div class="last_btn col-md-4">
              <label class="control-label" for="first-name">
                Phone: (Optional)
              </label>
              <input pattern="[0-9]+" title="Invalid phone no." placeholder="Please enter phone no." type="text"
                id="first-name" name="phone" value="{{ old('phone') }}" class="form-control">
            </div>

            <div class="last_btn col-md-4">
              <label class="control-label" for="first-name">
                Mobile: <span class="required">*</span>
              </label>
              <input pattern="[0-9]+" title="Invalid mobile no." placeholder="Please enter mobile no." type="text"
                id="first-name" name="mobile" class="form-control" value="{{old('mobile')}}">
            </div>

            <div class="last_btn col-md-4">
              <label class="control-label" for="first-name">
                Store Address: <span class="required">*</span>
              </label>
              <textarea placeholder="Please enter address" type="text" id="first-name" name="address"
                class="form-control" rows="1" cols="1">{{old('address')}}</textarea>
            </div>

            <div class="col-md-4 last_btn">
              <label class="control-label" for="first-name">
                Country: <span class="required">*</span>
              </label>

              <select name="country_id" id="country_id" class="form-control select2 col-md-7 col-xs-12">
                <option value="0">Please Choose</option>
                @foreach($countrys as $country)
                <?php
                              $iso3 = $country->country;

                              $country_name = DB::table('allcountry')->
                              where('iso3',$iso3)->first();

                               ?>
                <option value="{{$country_name->id}} ">{{$country_name->nicename}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 last_btn">
              <label class="control-label" for="first-name">
                State: <span class="required">*</span>
              </label>

              <select name="state_id" id="upload_id" class="select2 form-control">

                <option value="0">Please Choose</option>
                <option value=""></option>
              </select>
            </div>

            <div class="col-md-4 last_btn">
              <label class="control-label" for="first-name">
                City: <span class="required">*</span>
              </label>

              <select name="city_id" id="city_id" class="select2 form-control">
                <option value="0">Please Choose</option>

                <option value=""></option>

              </select>
            </div>

            <div class="last_btn  col-md-4">
              <label class="control-label" for="first-name">
                Pin Code:
              </label>
              <input pattern="[0-9]+" title="Invalid pincode/zipcode" placeholder="Please enter pincode" type="text"
                id="first-name" name="pin_code" class="form-control" value="{{ old('pin_code') }}">
            </div>

            <div class="last_btn col-md-4">
              <label class="control-label" for="first-name">
                Store Logo:
              </label>
              <input type="file" id="first-name" name="store_logo" class="form-control" value="{{old('mobile')}}">
            </div>

            <div class="last_btn col-md-4">
              <label class="control-label" for="first-name">
                Prefer Payout Option:
              </label>
              <select  required name="preferd" id="preferd" class="form-control">
                <option value="">Select Prefer Payment option for payput</option>
                <option {{ old('preferd') == 'paypal' ? 'selected' : "" }} value="paypal">{{ __('Paypal') }}</option>
                <option {{ old('preferd') == 'paytm' ? 'selected' : "" }} value="paytm">{{ __('Paytm') }}</option>
                <option {{ old('preferd') == 'bank' ? 'selected' : "" }} value="bank">{{ __('Bank Transfer') }}</option>
              </select>
            </div>

            <div class="last_btn col-md-4">
                <label>Paypal Email:</label>
                <input value="{{ old('paypal_email') }}" type="text" class="form-control" class="form-control" name="paypal_email" placeholder="eg:seller@paypal.com">
            </div>

            <div class="last_btn col-md-4">
                <label>Paytm Mobile No: (APPLICABLE ONLY IN INDIA)</label>
                <input value="{{ old('paytem_mobile') }}" type="text" class="form-control" class="form-control" name="paytem_mobile" placeholder="eg:7894561230">
            </div>
            

            <div class="col-md-6 last_btn">
              <div class="bankGroup">
                <div class="form-group">
                  <label>{{ __('staticwords.AccountNumber') }}</label>
                  <input class="form-control" pattern="[0-9]+" title="Invalid account no." type="text"  name="account"
                    value="{{old('account')}}" placeholder="{{ __('staticwords.PleaseEnterAccountNumber') }}"> <span
                    class="required">{{$errors->first('account')}}</span>
                </div>
    
                <div class="form-group">
                  <label>{{ __('staticwords.AccountName') }}:</label>
                  <input class="form-control" type="text" name="account_name" value="{{old('account_name')}}"
                    placeholder="{{ __('staticwords.PleaseEnterAccountName') }}"> <span
                    class="required">{{$errors->first('bank_name')}}</span>
                </div>
    
                <div class="form-group">
                  <label> {{ __('staticwords.BankName') }}:</label>
                  <input class="form-control"  type="text" name="bank_name" value="{{old('bank_name')}}"
                    placeholder="{{ __('staticwords.PleaseEnterBankName') }}"> <span
                    class="required">{{$errors->first('bank_name')}}</span>
                </div>
    
                <div class="form-group">
                  <label> {{ __('IFSC Code') }}:</label>
                  <input class="form-control"  type="text" name="ifsc" value="{{old('ifsc')}}"
                    placeholder="{{ __('staticwords.PleaseEnterIFSCCode') }}"> <span
                    class="required">{{$errors->first('ifsc')}}</span>
                </div>
    
                <div class="form-group">
                  <label>{{ __('staticwords.BranchAddress') }}: </label>
                  <input class="form-control"  type="text" id="first-name" name="branch" placeholder="Please Enter Branch Address"
                    value="{{old('branch')}}">
                  <span class="required">{{$errors->first('branch')}}</span>
                </div>
              </div>
            </div>

            <div class="last_btn col-md-2">
              <label class="control-label" for="first-name">
                Status:
              </label>
              <br>
              <label class="switch">
                <input {{ old('status') ? "checked" : "" }} type="checkbox" name="status">
                <span class="knob"></span>
              </label>

              <br>
              <small>(Toggle the store status.)</small>
            </div>

            <div class="last_btn col-md-3">
              <label class="control-label" for="first-name">
                Verified Store: 
              </label>
              <br>
              <label class="switch">
                <input {{ old('verified_store') ? "checked" : "" }} type="checkbox" name="verified_store">
                <span class="knob"></span>
              </label>
              <br>
              <small>(On The Product detail page if store is verified than it will add <i class="fa fa-check-circle text-green"></i> Symbol next to the store name.)</small>

            </div>

            <div class="col-md-12">
              <div class="box-footer">
                
                <button type="reset" onClick="$('.select2').trigger('change')" class="btn btn-md btn-danger"><i class="fa fa-minus-circle"></i> Reset
                  Form</button>

                  <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-plus-circle"></i> Add Store
                  </button>
              </div>
            </div>



            <!--Main Row END-->
          </div>
        </form>
  </div>
</div>
@endsection

@section('custom-script')
<script>
  var baseUrl = "<?= url('/') ?>";
</script>
<script src="{{ url('js/ajaxlocationlist.js') }}"></script>
@endsection