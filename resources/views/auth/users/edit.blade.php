@extends('layouts.default')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<div class="well_N">
    <div class="row align-items-center ">
        <div class="col-md-6">
            <!-- <h1>User Create</h1> -->
        </div>
    </div>

    <div class="row align-items-center ">
        <div class="col-md-8">
    <h3>Edit User</h3>
    <form id="editUserForm" action="{{ route('users.update', $user->id) }}" method="POST">
        <input type="hidden" value="{{ csrf_token() }}" name="_token">
        <input type="hidden" value="PUT" name="_method">
        <input type="hidden" id="url" value="{{ route('users.index') }}">
        <div class="row">
            <!-- Employee -->

              
            <div class="col-md-12 mb-3">
                <label for="employee" class="form-label">Employee</label>
                <select class="form-select select2" id="employee" name="employee" style="width: 100%;">
                    <option value="">Select Employee</option>
                 

                 

                    @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                        <option value="{{ $row->id }}" data-email="{{ $row->email }}" data-name="{{ $row->name }}"
                            {{ $row->name == $user->name ? 'selected' : '' }}>
                            {{ $row->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" readonly>
            </div>

            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="text" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
            </div>
      <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input placeholder="Password" type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input placeholder="Confirm Password" type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
            <!-- Status -->
            <div class="col-md-12 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select select2" name="status" id="status" style="width: 100%;">
                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Roles -->
            <div class="col-md-12 mb-3">
                <label for="roles">Select Roles:</label>
                <div class="row">
                    @foreach($roles as $role)
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       id="role_{{ $role->id }}"
                                       name="roles"
                                       value="{{ $role->id }}"
                                       {{ $role->id == $user->ba_role_id ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label class="control-label" for="shop_location">Location.</label>
                            <input type="checkbox"  
                                name="shop_location" 
                                id="shop_location" 
                                onclick="shopLocation()" 
                                value="1" 
                                {{ $user->locations->count() > 0 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            
                <div class="row get_location" style="{{ $user->locations->count() > 0 ? '' : 'display:none;' }}">
                    <div class="col-md-12">

                        {{-- ✅ Add More Button Upar --}}
                        <div class="mb-2">
                            <button type="button" class="btn btn-success btn-sm" id="add_more">+ Add More</button>
                        </div>

                        {{-- ✅ Wrapper for all locations --}}
                        <div id="locations_wrapper">
                            {{-- Existing Locations --}}
                            @if($user->locations->count())
                                @foreach($user->locations as $index => $location)
                                    <div class="location_section border p-2 mb-3 rounded">
                                        <input type="text" name="map[]" class="form-control mb-2 search-input" placeholder="Search location"/>
                                        <div class="map" style="height: 300px; border:1px solid #ddd;"></div>

                                        <table class="table mt-2">
                                            <thead>
                                                <tr>
                                                    <th>Location Title</th>
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                    <th>Radius (KM)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="location_name[]" value="{{ $location->location_name }}" placeholder="Location Title"/></td>
                                                    <td><input type="number" step="any" readonly class="form-control lat" name="latitude[]" value="{{ $location->latitude }}" placeholder="Latitude"/></td>
                                                    <td><input type="number" step="any" readonly class="form-control lon" name="longitude[]" value="{{ $location->longitude }}" placeholder="Longitude"/></td>
                                                    <td><input type="number" step="any" class="form-control" name="radius[]" value="{{ $location->radius }}" placeholder="Radius (KM)"/></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove_section">X</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </div>
    </form>
</div>
    </div>

<script>
    $(document).ready(function () {
        $('#employee').change(function () {
            var selectedOption = $(this).find('option:selected');
            $('#name').val(selectedOption.data('name') || '');
            $('#email').val(selectedOption.data('email') || '');
        });
    });
</script>

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#status').select2();
</script>
<script>
    $('#employee').select2();
</script>



<script>

    var latitude = parseFloat({{ isset($tso->latitude)?$tso->latitude:24.8607343}}); // Example latitude
    var longitude = parseFloat({{ isset($tso->longitude)?$tso->longitude:67.0011364}}); // Example longitude


    $(document).ready(function() {
        
 if ($('#shop_location').prop('checked')) {
        $('.get_location').show();
        initMaps();
    }


       });


</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMaps"></script>

<script>
    function shopLocation(){
        var checked = $('#shop_location').prop('checked');
        if (checked) {
            $('.get_location').show();
            initMaps();
        } else {
            $('.get_location').hide();
        }
    }


    function initMaps() {
    $('.location_section').each(function(index, section) {
        let $mapEl = $(section).find('.map');
        if ($mapEl.data('map-initialized')) return;

        // Get existing lat/lng if available
        let latInput = $(section).find('.lat');
        let lonInput = $(section).find('.lon');

        let lat = parseFloat(latInput.val()) || 24.8607; // fallback Karachi
        let lng = parseFloat(lonInput.val()) || 67.0011;

        let map = new google.maps.Map($mapEl[0], {
            center: {lat: lat, lng: lng},
            zoom: 12
        });

        let marker = new google.maps.Marker({
            position: {lat: lat, lng: lng},
            map: map,
            draggable: true
        });

        // Set inputs if empty
        if (!latInput.val()) latInput.val(lat);
        if (!lonInput.val()) lonInput.val(lng);

        // Marker drag event
        marker.addListener('dragend', function(e) {
            latInput.val(e.latLng.lat());
            lonInput.val(e.latLng.lng());
        });

        // Map click
        map.addListener('click', function(e) {
            marker.setPosition(e.latLng);
            latInput.val(e.latLng.lat());
            lonInput.val(e.latLng.lng());
        });

        // Autocomplete
        let searchInput = $(section).find('.search-input')[0];
        let autocomplete = new google.maps.places.Autocomplete(searchInput);
        autocomplete.bindTo("bounds", map);
        autocomplete.addListener("place_changed", function () {
            let place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            map.setCenter(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);
            latInput.val(place.geometry.location.lat());
            lonInput.val(place.geometry.location.lng());
        });

        $mapEl.data('map-initialized', true);
    });
}


    // Add More Section
    $('#add_more').on('click', function() {
        let newSection = `
        <div class="location_section border p-2 mb-3 rounded">
            <input type="text" name="map[]" class="form-control mb-2 search-input" placeholder="Search location"/>
            <div class="map" style="height: 300px; border:1px solid #ddd;"></div>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Location Title</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Radius (KM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" name="location_name[]" placeholder="Location Title"/></td>
                        <td><input type="number" step="any" readonly class="form-control lat" name="latitude[]" placeholder="Latitude"/></td>
                        <td><input type="number" step="any" readonly class="form-control lon" name="longitude[]" placeholder="Longitude"/></td>
                        <td><input type="number" step="any" class="form-control" name="radius[]" placeholder="Radius (KM)"/></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove_section">X</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        `;
        $('#locations_wrapper').append(newSection);
        initMaps();
    });

    // Remove Section
    $(document).on('click', '.remove_section', function() {
        $(this).closest('.location_section').remove();
    });
</script>
@endsection
