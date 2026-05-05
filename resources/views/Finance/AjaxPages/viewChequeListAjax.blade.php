@php    
        $counter = 1;
    $makeTotalAmount = 0;

@endphp

@foreach ($cheque as $item)
    <tr id="tr_{{ $counter }}">

        <td class="text-center">
            {{ $counter++ }}
        </td>
        <td class="text-center">
            {{ strtoupper($item->customer_name != "-" ? $item->customer_name : ($item->supplier_name != "-" ? $item->supplier_name : "-")) }}
        </td>
        <td class="text-center">
            {{ strtoupper($item->reci_code) }}
        </td>
        <td class="text-center">
            {{ $item->reci_date }}
        </td>
        <td class="text-center">
            {{ $item->cheque_no }}
        </td>
        <td class="text-center">
            {{ $item->cheque_date }}
        </td>


        <!-- <td class="text-center">
            {{ strtoupper($item->supplier_name) }}
        </td> -->
        <td class="text-center issue_cols">
            {{ strtoupper($item->issue_code) }}
        </td>
        <td class="text-center issue_cols">
            {{ $item->issue_date }}
        </td>
        <td class="text-center">
            {{ number_format($item->amount, 2) }}
        </td>
        <td class="text-center issue_cols">
            @if(isset($item->remaining_amount))
                {{ number_format($item->remaining_amount, 2) }}
            @else
                -
            @endif
        </td>
        <td class="text-center issue_cols">
            @if(isset($item->remaining_amount))
                @php
                    $rem = $item->remaining_amount;
                    $tot = $item->amount;
                @endphp
                @if($rem == $tot)
                    <span class="label label-danger">Not Issued</span>
                @elseif($rem > 0 && $rem < $tot)
                    <span class="label label-warning">Partial</span>
                @elseif($rem == 0)
                    <span class="label label-success">Completed</span>
                @endif
            @else
                -
            @endif
        </td>
        <td class="text-center">
            <select class="form-control" onfocus="setPrevStatus(this)" 
                onchange="changeStatus(this, '{{ $item->cheque_id ?? 'new' }}', '{{ $item->reci_code }}', '{{ $item->cheque_no }}')" 
                style="width: 150px; margin: 0 auto;">
                <option value="0" {{ $item->issued == 0 ? 'selected' : '' }}>Cheque In Hand</option>
                <option value="1" {{ $item->issued == 1 ? 'selected' : '' }}>Deposit in Bank</option>
                <option value="2" {{ $item->issued == 2 ? 'selected' : '' }}>Bounce</option>
                <option value="3" {{ $item->issued == 3 ? 'selected' : '' }}>Return to Customer</option>
                <option value="4" {{ $item->issued == 4 ? 'selected' : '' }}>Cancel</option>
                <option value="5" {{ $item->issued == 5 ? 'selected' : '' }}>Clear</option>
            </select>
        </td>


        <!-- <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        @if(isset($item->cheque_id))
                            @if($item->issued == 1)
                                <a href="{{ url("finance/chequeReturnFromSupplier/$item->cheque_id") }}">
                                    <i class="fa-regular fa-pencil"></i>
                                    Return From supplier
                                </a>
                            @endif

                            @if($item->issued == 0 || $item->issued == 2)
                                <a href="{{ url("finance/chequeReturnToCustomer/$item->cheque_id") }}">
                                    <i class="fa-regular fa-pencil"></i>
                                    Return To Customer
                                </a>
                            @endif

                            @if($item->issued == 0 || $item->issued == 2)
                                <a href="{{ url("finance/convertToCash/$item->cheque_id") }}">
                                    <i class="fa-regular fa-pencil"></i>
                                    Convert To Cash
                                </a>
                            @endif
                        @else
                            <i>No Cheque Actions</i>
                        @endif

                        {{-- @if($item->issued == 0)
                        <a href="#">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                        @endif --}}


                    </li>
                </ul>
            </div>
        </td> -->
    </tr>
@endforeach