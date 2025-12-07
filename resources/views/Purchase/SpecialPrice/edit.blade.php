@extends('layouts.default')
@section('content')
    <div class="well_N">
        <h2>Edit Special Price</h2>
        <form action="{{ route('specialPrice.update',$CustomerSpecialPrice->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="name">Product</label>
                <select
                        name="product_id" id="product_id"
                        class="form-control WrequiredField select2 d-block"
                       >
                    <option value="">Select Products</option>
                    @foreach(App\Helpers\CommonHelper::get_all_product_from_sub_items()
                    as $row)
                        <option value="{{$row->id}}" {{$row->id == $CustomerSpecialPrice->product_id ? 'selected' : ''}}>
                            {{$row->product_name . ' - ' .$row->product_barcode . ' - ' . $row->sku_code}}
                        </option>
                    @endforeach

                </select>
            </div>
            <div class="form-group">
                <label for="name">Customer Name</label>
                <input disabled type="text" value="{{$CustomerSpecialPrice->customer_name}}" name="customer_name" id="customer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">MRP Price</label>
                <input type="text" value="{{$CustomerSpecialPrice->mrp_price}}" name="mrp_price" id="mrp_price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Sale Price</label>
                <input type="text" value="{{$CustomerSpecialPrice->sale_price}}" name="sale_price" id="sale_price" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>

        </form>
    </div>
@endsection
