@php    
    $counter = 1;
    $makeTotalAmount = 0;

@endphp

@foreach ($cheque as $item )
<tr id="tr_{{ $counter }}">

    <td class="text-center">
        {{ $counter++ }}
    </td>
    <td class="text-center">
        {{ strtoupper($item->customer_name) }}
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
    <td class="text-center">
        {{ strtoupper($item->issue_code) }}
    </td>
    <td class="text-center">
        {{ $item->issue_date }}
    </td>
    <td class="text-center">
        {{ number_format($item->amount , 2) }}
    </td>
    <td class="text-center">
        {{ strtoupper($item->issue_status) }}
    </td>

    
    <td class="text-center hidden-print">
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>
                    @if($item->issued == 1)
                        <a href="{{ url("finance/chequeReturnFromSupplier/$item->id") }}">
                            <i class="fa-regular fa-pencil"></i>
                            Return From supplier
                        </a>
                    @endif

                    @if($item->issued == 0 || $item->issued == 2)
                        <a href="{{ url("finance/chequeReturnToCustomer/$item->id") }}">
                            <i class="fa-regular fa-pencil"></i>
                            Return To Customer
                        </a>
                    @endif

                    @if($item->issued == 0 || $item->issued == 2 )
                        <a href="{{ url("finance/convertToCash/$item->id") }}">
                            <i class="fa-regular fa-pencil"></i>
                            Convert To Cash
                        </a>
                    @endif

                    {{-- @if($item->issued == 0)
                        <a href="#">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    @endif --}}
                       

                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach