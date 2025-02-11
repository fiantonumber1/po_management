@extends('layouts.universal')


@section('container2')
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb bg-white px-2 float-left">
                    <li class="breadcrumb-item"><a href="">Invoicing</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('container3')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <h3 class="card-title text-bold">Invoicing<span class="badge badge-info ml-1"></span></h3>
                </div>
                <div class="card-body">
                    {{-- Filter Status --}}
                    {{-- Filter Status dan Tombol Add Purchase Order --}}
                    <div class="d-flex align-items-center mb-3">
                        <form action="{{ route('purchase_orders.index') }}" method="GET" class="form-inline mr-3">
                            <label for="status" class="mr-2">Status:</label>
                            <select name="status" id="status" class="form-control mr-2" onchange="this.form.submit()">
                                <option value="ALL" {{ $status == 'ALL' ? 'selected' : '' }}>ALL</option>
                                <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="PAID" {{ $status == 'PAID' ? 'selected' : '' }}>PAID</option>
                            </select>
                        </form>
                        <button id="addPurchaseOrder" class="btn btn-primary">Add Purchase Order</button>
                    </div>

                    <table id="example2" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="checkAll">
                                </th>
                                <th>No</th>
                                <th>PO Number</th>
                                <th>PO Date</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Buyer</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                            class="orderCheckbox">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->po_number }}</td>
                                    <td>{{ $order->po_date }}</td>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->invoice_date }}</td>
                                    <td>{{ $order->due_date }}</td>
                                    <td>{{ $order->buyer }}</td>
                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>
                                        <a href="{{ route('purchase_orders.updateStatus', ['order' => $order->id, 'status' => 'PAID']) }}"
                                            class="btn btn-success">Mark as PAID</a>
                                        <a href="{{ route('purchase_orders.updateStatus', ['order' => $order->id, 'status' => 'Pending']) }}"
                                            class="btn btn-warning">Mark as Pending</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('checkAll').addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('.orderCheckbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true, // Aktifkan opsi length menu
                "lengthMenu": [10, 20, 50], // Pilihan jumlah data per halaman
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });

            document.getElementById('addPurchaseOrder').addEventListener('click', function () {
                Swal.fire({
                    title: 'Add Purchase Order',
                    html: `
                                                            <form id="purchaseOrderForm" action="{{ route('purchase_orders.store') }}" method="POST">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="po_number">PO Number</label>
                                                                    <input type="text" name="po_number" id="po_number" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="po_date">PO Date</label>
                                                                    <input type="date" name="po_date" id="po_date" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="invoice_number">Invoice Number</label>
                                                                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="invoice_date">Invoice Date</label>
                                                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="due_date">Due Date</label>
                                                                    <input type="date" name="due_date" id="due_date" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="buyer">Buyer</label>
                                                                    <input type="text" name="buyer" id="buyer" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="grand_total">Grand Total</label>
                                                                    <input type="number" step="0.01" name="grand_total" id="grand_total" class="form-control" required>
                                                                </div>
                                                            </form>
                                                        `,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('purchaseOrderForm');
                        if (!form.checkValidity()) {
                            Swal.showValidationMessage('Please complete the form');
                        } else {
                            form.submit();
                        }
                    }
                });
            });
        });

    </script>
@endpush