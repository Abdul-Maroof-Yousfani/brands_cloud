@extends('layouts.default')
@section('content')
<div class="well_N">
    <h2>Edit Brand</h2>
      <form action="{{ route('brands.update',$brand->id) }}" method="POST">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for="name">Brand Name</label>
            <input type="text" name="name" id="name" value="{{ $brand->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Brand Description</label>
            <textarea name="description" id="description" class="form-control">{{ $brand->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="principal_group_id">Principal Group</label>
            <select name="principal_group_id" id="principal_group_id" class="form-control" required>
                <option value="">-- Select Principal Group --</option>
                @foreach($principalGroups as $group)
                    <option value="{{ $group->id }}"
                        {{ $brand->principal_group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->products_principal_group }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
