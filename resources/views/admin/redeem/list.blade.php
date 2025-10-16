@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Redeem Requests</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Redeem Requests</a></li>
                    <li class="breadcrumb-item active">Redeem Requests</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row g-2">
                    <div class="col-sm-4">
                        <h5 class="card-title mb-0">Withdrawal Request List</h5>
                    </div>
                    {{-- You can enable Add button if needed in future --}}
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th data-ordering="false">Sno.</th>
                            <th data-ordering="false">Name</th>
                            <th data-ordering="false">Mobile Number</th>
                            <th data-ordering="false">Email</th>
                            <th data-ordering="false">Amount</th>
                            <th data-ordering="false">Status</th>
                            <th data-ordering="false">Requested At</th>
                            <th data-ordering="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $list)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $list->name }}</td>
                            <td>{{ $list->phone }}</td>
                            <td>{{ $list->email }}</td>
                            <td>₹{{ number_format($list->amount) }}</td>
                            <td>
                                @if($list->paid == 1)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup({{ $list->id }},'withdrawal_table','delete')" href="javascript:void(0);">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                            </a>
                                        </li>
                                        {{-- Optional: Mark as Paid --}}
                                        {{-- <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="markAsPaid({{ $list->id }})">
                                                <i class="ri-check-double-line align-bottom me-2 text-success"></i> Mark as Paid
                                            </a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end row-->

@include('admin.deactivate')



<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>

@endsection()