@php 
$count = 1;
@endphp 

@foreach($machine_process as $proccessed)
<tr>
    <td class="text-center">{{$count}}</td>
    {{--  <td class="text-center">{{$proccessed->machine_no}}</td> --}}
    <td class="text-center">{{$proccessed->batch_no}}</td>
    <td class="text-center">{{$proccessed->machine_name}}</td>
    <td class="text-center">{{$proccessed->operator_name}}</td>
    <td class="text-center">{{$proccessed->shift}}</td>
    <td class="text-center">{{$proccessed->machine_process_date}}</td>
    <td class="text-center">{{$proccessed->request_qty}}</td>
    <td class="text-center">
        @if($proccessed->machine_process_stage == 1)
            Received
        @elseif($proccessed->machine_process_stage == 2)
            Packing
        @elseif($proccessed->machine_process_stage == 3)
            On Qc
        @elseif($proccessed->machine_process_stage == 4)
            Qc Passed 
        @endif    
    </td>

    {{-- <td class="text-center hidden-print printListBtn">
        <div class="customDropDown"
            style="position: relative">
            <button class="btn btn-success" type="button">
                Transfer
            </button>
            <ul class="dropdown-menu" style="display: none;">
                <li class="dropdown-item">
                    <p>Stage</p>
                </li>
                <li class="dropdown-item">
                    <input type="text" placeholder=""
                        class="form-control requiredField"
                        name="print_area" id="print_area">
                </li>
                <li class="dropdown-item">
                    <button type="button"
                        class="btn btn-xs btn-success">Submit</button>
                </li>
                
            </ul>
        </div>
    </td> --}}
    <td class="text-center">
    <button class="btn btn-success" type="button">
    Print Tag
    </button>
    </td>
</tr>
@php 
$count++;
@endphp 
@endforeach