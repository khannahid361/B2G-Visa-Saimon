<h1>Payment And Chaceklist</h1>

<table>
    <tr>
        <th>SL</th>
        <th>Application ID</th>
        <th>visa_category</th>
        <th>checklist.title</th>
        <th>checklist.price</th>
        <th>checklist.service_charge</th>
        <th>Payment</th>
        <th>paid_amount</th>
        <th>due_amount</th>
        <th>discount</th>
        <th>service_charge</th>
        <th>visa_fee</th>
    </tr>
    @foreach ($datas as $key => $value)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $value->walkin_app_info_id }}</td>
        <td>{{ $value->visa_category ?? '' }}</td>
        <td>{{ $value->checklist_title ?? '' }}</td>
        <td>{{ $value->checklist_price ?? '' }}</td>
        <td>{{ $value->checklist_service_charge ?? '' }}</td>
        <td>{{ $value->payment }}</td>
        <td>{{ $value->paid_amount }}</td>
        <td>{{ $value->due_amount }}</td>
        <td>{{ $value->discount }}</td>
        <td>{{ $value->service_charge }}</td>
        <td>{{ $value->visa_fee }}</td>

    </tr>
    @endforeach
</table>