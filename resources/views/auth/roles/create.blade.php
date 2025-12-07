@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="well_N">
        <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>Roles</h1>
            </div>
        </div>


        <!-- <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>Roles</h1>
            </div>
        </div> -->
        <div class="row align-items-center ">
            <div class="col-md-8">
                <form id="submitadv" action="{{route('roles.store')}}" method="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                    <input type="hidden" id="url" value="{{route('roles.index')}}">
                    <div class="mb-3">
                        <label for="brands" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="customers" class="form-label">status</label>
                        <select class="form-select select2"  name="status"   id="status"  style="width: 100%;">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="permissions">Select Permissions:</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="permission_{{ $permission->id }}"
                                               name="permissions[]"
                                               value="{{ $permission->id }}">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button style="margin-top: 10px" type="submit" class="btn btn-primary my-2">Create</button>
                </form>

            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#status').select2();
    </script>
@endsection