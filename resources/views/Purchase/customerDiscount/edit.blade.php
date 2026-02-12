@extends('layouts.default')
@section('content')
    <div class="well_N">
        <h2>Customer Discount</h2>
        <form action="{{ route('customerDiscount.update',$customerDiscount->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="name">Brands</label>
                <select
                        name="brand_id" id="brand_id" onChange="get_product_by_brand(this,1)"
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
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#brand_id').select2({
                placeholder: 'Select Brands',
                allowClear: true
            });
            $('#product_id').select2({
                placeholder: 'Select Products',
                allowClear: true
            });
            $('#customer_id').select2({
                placeholder: 'Select Customer',
                allowClear: true
            });
        });

        function get_product_by_brand(element, number) {
            var value = element.value;
            $('#product_id').empty();
            $.ajax({
                url: '{{ url("/getSubItemByBrand") }}',
                type: 'Get',
                data: {
                    id: value
                },
                success: function(data) {
                    $('#product_id').append(data);
                }
            });
        }
    </script>
@endsection
