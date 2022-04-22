@push('css')
    <link href="{{asset('adminPanel/vendor/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminPanel/vendor/datatables/dataTables.checkboxes.css')}}" rel="stylesheet"/>
    <style>
        .dt-buttons {
            display: none;
        }

        .pull-right {
            float: right;
            position: absolute;
            top: 0;
            right: 5px;
        }

        .pull-left ul {
            list-style: none;
            margin: 0;
            padding-left: 0;
        }

        .pull-left a {
            text-decoration: none;
            color: #ffffff;
        }

        .pull-left li {
            color: #ffffff;
            background-color: #2f2f2f;
            border-color: #2f2f2f;
            display: block;
            float: left;
            position: relative;
            text-decoration: none;
            transition-duration: 0.5s;
            padding: 12px 30px;
            font-size: .75rem;
            font-weight: 400;
            line-height: 1.428571;
        }

        .pull-left li:hover {
            cursor: pointer;
            background-color: #4e73df;
        }

        .pull-left ul li ul {
            visibility: hidden;
            opacity: 0;
            min-width: 111px;
            position: absolute;
            transition: all 0.5s ease;
            margin-top: 8px;
            left: 0;
            display: none;
            z-index: 999;
        }

        .pull-left ul li:hover > ul,
        .pull-left ul li ul:hover {
            visibility: visible;
            opacity: 1;
            display: block;
        }

        .pull-left ul li ul li {
            clear: both;
            width: 100%;
            color: #ffffff;
        }

        .ul-dropdown {
            margin: 0.3125rem 1px !important;
            outline: 0;
        }

        .firstli {
            border-radius: 0.2rem;
        }

        .firstli .material-icons {
            position: relative;
            display: inline-block;
            top: 0;
            margin-top: -1.1em;
            margin-bottom: -1em;
            font-size: 0.8rem;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
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

        .dataTables_filter {
            width: 100%;
        }
    </style>
@endpush
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{__('Credits')}}</h6>
        <div class="pull-right">
            <div class="pull-left">
                <nav role="navigation">
                    <ul class="ul-dropdown">
                        <li class="firstli">
                            <a href="#"><i class="fas-solid fa-file-export"></i> Export</a>
                            <ul>
                                <li><a href="#">CSV</a></li>
                                <li><a href="#">Excel</a></li>
                                <li><a href="#">PDF</a></li>
                                <li><a href="#">Print</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <div id="category-filters">
                <label class="float-left ml-2">{{__('Credit Type')}}:<select id="creditType"
                                                                             class="form-control form-control-sm">
                        <option value="">{{__('no selected')}}</option>
                        @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                            <optgroup data-id="{{$category->id}}" label="{{$category->name}}">
                                @foreach($category->children as $type)
                                    <option value="{{$type->name}}">{{$type->name}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </label>
            </div>
            <form id="proposals" action="#" method="POST">
                @csrf
                <table id="proposals_table"
                       class="table table-sm table-bordered display responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th scope="col">{{__('Proposal number')}}</th>
                        <th scope="col">{{__('Category')}}</th>
                        <th scope="col">{{__('Credit Type')}}</th>
                        <th scope="col">{{__('Sum')}}</th>
                        <th scope="col">{{__('Date')}}</th>
                        <th scope="col">{{__('Payout amount')}}</th>
                        <th scope="col">{{__('Status')}}</th>
                        <th scope="col">{{__('Deadline')}}</th>
                        <th scope="col">{{__('Full Name')}}</th>
                        <th scope="col">{{__('Phone Number')}}</th>
                        <th scope="col">{{__('Email')}}</th>
                        <th class="not-export-col" scope="col">
                            <span class="sr-only">{{__('Action')}}</span>
                        </th>
                    </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</div>
@push('js')
    <script src="{{asset('adminPanel/vendor/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminPanel/vendor/datatables/dataTables.checkboxes.min.js')}}"/>
    <script src="{{asset('adminPanel/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminPanel/vendor/datatables/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminPanel/vendor/datatables/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminPanel/vendor/datatables/pdfmake.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminPanel/vendor/datatables/vfs_fonts.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminPanel/vendor/datatables/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('adminPanel/vendor/datatables/buttons.print.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            let proposal_form = $('form#proposals');
            let creditType = $("#creditType");
            let table = $('#proposals_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'csv',
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'pdf',
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'TABLOID',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                        customize: function (doc) {
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        }
                    },
                    {
                        text: 'print',
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                ],
                responsive: false,
                autoWidth: true,
                processing: true,
                serverSide: true,
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'All']],
                order: [[3, 'desc']],
                ajax: '{!! request()->routeIs('proposal.draft')?route('proposal.draft'):route('proposal.index') !!}',
                columns: [
                    {data: 'number', name: 'number'},
                    {data: 'category.parent.name', name: 'category.parent.name', searchable: false},
                    {data: 'category.name', name: 'category.name', visible: false},
                    {data: 'creditAmount', name: 'creditAmount'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'payoutAmount', name: 'payoutAmount', orderable: false, searchable: false},
                    {data: 'status', name: 'status'},
                    {data: 'deadline', name: 'deadline'},
                    {data: 'fullName', name: 'fullName', orderable: false},
                    {data: 'phoneNumber', name: 'phoneNumber'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ], createdRow: function (row, data, index) {
                    $(row).addClass('cursor-pointer');
                    $('td', row).eq(0).addClass(data['bgColor']);
                    $('td', row).eq(5).addClass(data['statusBgColor']);
                },
            });
            $("ul li ul li").click(function () {
                let i = $(this).index() + 1
                if (i === 1) {
                    table.button('.buttons-csv').trigger();
                } else if (i === 2) {
                    table.button('.buttons-excel').trigger();
                } else if (i === 3) {
                    table.button('.buttons-pdf').trigger();
                } else if (i === 4) {
                    table.button('.buttons-print').trigger();
                }
            });
            $('input[type=search]', proposal_form).addClass(function () {
                $(this).parent().addClass('inline-flex align-items-center');
                return 'form-control form-control-sm';
            });
            table.on('click', 'tbody>tr:not(.group)', function (e) {
                let link = $(this).find('a.edit-link')[0];
                if (link && e.target.tagName.toLowerCase() === 'td') {
                    link.click();
                }
            });
            $("#category-filters > label").each(function (i, el) {
                $("#proposals_table_filter.dataTables_filter").prepend(el);
            });
            creditType.on('change', function () {
                let category = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
                table.columns(1).search(category ? '^' + category + '$' : '', true, false);
                table.columns(2).search(this.value ? '^' + this.value + '$' : '', true, false);
                table.draw();
            });
        });
    </script>
@endpush
