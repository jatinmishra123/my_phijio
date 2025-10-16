@extends('admin.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Plan Order List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Plan Order Master</a></li>
                    <li class="breadcrumb-item active">Plan Order List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Plan Orders List</h5>

            </div>

            <div class="card-body">
                <table id="OrdersTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Plan Name</th>
                            <th>Amount (₹)</th>
                            <th>Status</th>
                            <th>Payment ID</th>
                            <th>Order Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merged as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order['user']['name'] ?? '-' }}</td>
                        <td>{{ $order['plan']['title'] ?? '-' }}</td>
                        <td>₹{{ number_format($order['plan']['price'], 2) }}</td>
                        <td>
                            @php
                                $status = $order['payment']['status'] ?? null;
                            @endphp
                            @if($status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($status === 'delivered')
                                <span class="badge bg-primary">Delivered</span>
                            @elseif($status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">Unpaid</span>
                            @endif
                        </td>
                        <td>{{ $order['payment']['payment_id'] ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, h:i A') }}</td> 
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.deactivate')

<!-- Scripts -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>

$(document).ready(function () {
    $('#OrdersTable').DataTable();

    $('#kitForm').submit(function (e) {
        e.preventDefault();
        $('.text-danger').text('');
        $('#ajax-loader').show();

        const form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: "{{ url('admin/kits/store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (result) {
                $('#ajax-loader').hide();

                if (result.code === 200) {
                    swal(result.message, '', 'success');
                    $('#basicModal').modal('hide');
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    $.each(result.message, function (key, val) {
                        $('.error-' + key).text(val[0]);
                    });
                    swal("Validation Error", '', 'error');
                }
            },

            error: function (xhr) {
                $('#ajax-loader').hide();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $('.error-' + key).text(val[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            }
        });
    });
});
</script>

@endsection
