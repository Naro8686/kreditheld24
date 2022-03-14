<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{__('Credits')}}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div id="category-filters">
                <label class="float-left">{{__('Credit Type')}}:<select id="creditType"
                                                                        class="form-control form-control-sm">
                        <option value="">{{__('no selected')}}</option>
                        @foreach(\App\Models\Proposal::$creditTypes as $key => $creditType)
                            <option value="{{$creditType}}">{{trans("proposal.creditTypes.$creditType")}}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <table class="table table-sm table-bordered text-center display responsive nowrap" id="proposals_table"
                   width="100%"
                   cellspacing="0">
                <thead>
                <tr>
                    <th scope="col">{{__('Id')}}</th>
                    <th scope="col">{{__('Credit Type')}}</th>
                    <th scope="col">{{__('Proposal number')}}</th>
                    <th scope="col">{{__('Manager')}}</th>
                    <th scope="col">{{__('Sum')}}</th>
                    <th scope="col">{{__('Date')}}</th>
                    <th scope="col">{{__('Status')}}</th>
                    <th scope="col">{{__('Payout amount')}}</th>
                    <th scope="col">{{__('Deadline')}}</th>
                    <th scope="col">{{__('Files')}}</th>
                    <th scope="col">
                        <span class="sr-only">{{__('Action')}}</span>
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@push('css')
    <link href="{{asset('adminPanel/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        .table td, .table th {
            vertical-align: middle;
        }

        select.form-control {
            display: inline;
            width: 200px;
            margin-left: 5px;
        }

        #category-filters {
            display: none;
        }
    </style>
@endpush
@push('js')
    <script src="{{asset('adminPanel/vendor/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('adminPanel/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let creditType = $("#creditType");
            let table = $('#proposals_table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                lengthMenu: [[25, 50, 100], [25, 50, 100]],
                order: [[0, 'desc']],
                ajax: '{!! route('admin.proposals.index') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'creditType', name: 'creditType', orderable: false, searchable: false},
                    {data: 'number', name: 'number'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'creditAmount', name: 'creditAmount'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'payoutAmount', name: 'payoutAmount', orderable: false, searchable: false},
                    {data: 'deadline', name: 'deadline'},
                    {data: 'files', name: 'files', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ], createdRow: function (row, data, index) {
                    $('td', row).eq(0).addClass(data['bgColor']);
                },
            });
            $("#category-filters > label").each(function (i, el) {
                $("#proposals_table_filter.dataTables_filter").prepend(el);
            });
            creditType.on('change', function () {
                table.columns(1).search(this.value ? '^' + this.value + '$' : '', true, false).draw();
            });
        });
    </script>
@endpush
