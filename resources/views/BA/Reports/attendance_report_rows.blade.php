@foreach($reportData as $index => $ba)
<tr>
    <td>{{ isset($startIndex) ? $startIndex + $index + 1 : $loop->iteration }}</td>
    <td>{{ $ba['emp_id'] }}</td>
    <td style="text-align: left; font-weight: 600;">{{ $ba['name'] }}</td>
    <td style="text-align: left;">{{ $ba['zone'] }}</td>
    <td style="text-align: left;">{{ $ba['customer'] }}</td>
    <td style="text-align: left;">{{ $ba['brands'] }}</td>
    @foreach($dates as $date)
        <td>{{ $ba['days'][$date]['time_in'] }}</td>
        <td>{{ $ba['days'][$date]['time_out'] }}</td>
        <td style="color: #0984e3;">{{ $ba['days'][$date]['target'] }}</td>
        <td style="font-weight: bold; color: {{ $ba['days'][$date]['ach'] >= $ba['days'][$date]['target'] ? '#00b894' : '#d63031' }};">
            {{ $ba['days'][$date]['ach'] }}
        </td>
    @endforeach
    <td style="background: #e1f5fe; font-weight: bold;">{{ $ba['total_present'] }}</td>
    <td style="background: #ffebee; font-weight: bold;">{{ $ba['total_absent'] }}</td>
    <td style="background: #f1f2f6; font-weight: bold; color: #0984e3;">{{ $ba['total_target'] }}</td>
    <td style="background: #f1f2f6; font-weight: bold; color: {{ $ba['total_ach'] >= $ba['total_target'] ? '#00b894' : '#d63031' }};">
        {{ $ba['total_ach'] }}
    </td>
</tr>
@endforeach
