@extends('layouts.default')
@section('content')
<div class="well_N">
    <h2>Create Brand</h2>
    <form action="{{ route('brands.store') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">

            {{-- Principal Group Dropdown --}}
            <div class="col-md-6 mb-3">
                <label for="principal_group_id">Principal Group <span class="text-danger">*</span></label>
                <select name="principal_group_id" id="principal_group_id" class="form-control" required>
                    <option value="">-- Select Principal Group --</option>
                    @foreach($principalGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->products_principal_group }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Brand Name --}}
            <div class="col-md-6 mb-3">
                <label for="name">Brand Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            {{-- Brand Description (Full Width) --}}
            <div class="col-md-12 mb-3">
                <label for="description">Brand Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            {{-- Submit Button --}}
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary px-4">Create</button>
            </div>

        </div>
  

    </form>
</div>
@endsection
