@extends('layouts.default')
@include("select2")
@section('content')
    <div class="well_N">
        <h2>Customer Discount</h2>
        <form action="{{ route('customerDiscount.update',$customerDiscount->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="name">Brands</label>
                <select
                        name="brand_id" id="brand_id"
                        class="form-control WrequiredField select2 d-block select2"
                       >
                    <option value="">Select Brands</option>
                    @foreach(App\Helpers\CommonHelper::get_all_brand()
                    as $brand)
                        <option value="{{$brand->id}}" {{$brand->id == $customerDiscount->brand_id ? 'selected' : ''}}>
                            {{ $brand->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label for="name">Products</label>
                <select
                        name="product_id" id="product_id"
                        class="form-control WrequiredField select2 d-block select2"
                       >
                    <option value="">Select Products</option>
                    @foreach(App\Helpers\CommonHelper::get_product_names_by_brand_id($customerDiscount->brand_id)
                    as $product)
                        <option value="{{$product->id}}" {{$product->id == $customerDiscount->product_id ? 'selected' : ''}}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">Customers</label>
                <select
                        name="customer_id" id="customer_id"
                        class="form-control WrequiredField select2 d-block select2"
                       >
                    <option value="">Select Customer</option>
                    @foreach(App\Helpers\CommonHelper::get_customer()
                    as $customer)
                        <option value="{{$customer->id}}" {{$customer->id == $customerDiscount->customer_id ? 'selected' : ''}}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

           
            <div class="form-group">
                <label for="name">Discount Percentage</label>
                <input type="text" value="{{$customerDiscount->discount_percentage}}" name="discount_percentage" id="discount_percentage" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>

        </form>
    </div>
@endsection
