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
        .dt-more-container {
            text-align: center;
            margin: 2em 0;
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
                @if(!request()->routeIs('proposal.draft'))
                    <label class="float-left ml-2">
                        <a href="#"
                           data-toggle="modal"
                           data-target="#sendEmailModal"
                           data-url="{{route('email.send')}}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-fw fa-envelope"></i>
                            <span>{{__("Send message")}}</span>
                        </a>
                    </label>
                @endif
                <label class="float-left ml-2">{{__('Status')}}
                    :<select id="status" class="form-control form-control-sm">
                        <option value="">{{__('no selected')}}</option>
                        @foreach(\App\Constants\Status::getList(false) as $status)
                            <option value="{{$status}}">{{__("status.$status")}}</option>
                        @endforeach
                    </select>
                </label>
                <label class="float-left ml-2">{{__('Search Type')}}
                    :<select id="creditType" class="form-control form-control-sm">
                        <option value="">{{__('no selected')}}</option>
                        @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                            <option value="{{$category->name}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <form id="proposals" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <table id="proposals_table"
                       class="table table-sm table-bordered display responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th class="not-export-col" scope="col">
                            <input name="select_all" value="1" id="select-all" type="checkbox"/>
                        </th>
                        <th scope="col">{{__('Proposal number')}}</th>
                        <th scope="col">{{__('Full Name')}}</th>
                        <th scope="col">{{__('Category')}}</th>
                        <th scope="col">{{__('Credit Type')}}</th>
                        <th scope="col">{{__('Sum')}}</th>
                        <th scope="col">{{__('Date')}}</th>
                        <th scope="col">{{__('Status')}}</th>
                        <th scope="col">{{__('Deadline')}}</th>
                        <th scope="col">{{__('Email Manager')}}</th>
                        <th scope="col">{{__('Payout amount')}}</th>
{{--                        <th class="not-export-col" scope="col">--}}
{{--                            <span class="sr-only">{{__('Action')}}</span>--}}
{{--                        </th>--}}
                    </tr>
                    </thead>
                </table>
                <div class="dt-more-container">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-more"
                            style="display:none">{{__('Show more')}}</button>
                </div>
                @include('manager.includes.modals.sendEmail')
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
    <script src="{{asset('adminPanel/vendor/datatables/dataTables.pageLoadMore.min.js')}}"
            type="text/javascript"></script>
    <script>

        $(document).ready(function () {
            let proposal_form = $('form#proposals');
            let creditType = $("#creditType");
            let status = $("#status");
            let table = $('#proposals_table').DataTable({
                drawCallback: function () {
                    let btnLoadMore = $('#btn-load-more');
                    // If there is some more data
                    if (btnLoadMore.is(':visible')) {
                        // Scroll to the "Load more" button
                        $('html, body').animate({
                            scrollTop: btnLoadMore.offset().top
                        }, 1000);
                    }
                    // Show or hide "Load more" button based on whether there is more data available
                    btnLoadMore.toggle(this.api().page.hasMore());
                },
                dom: 'frt',
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
                responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                lengthMenu: [[20, 50, 100, -1], [20, 50, 100, 'All']],
                order: [[6, 'desc']],
                ajax: '{!! request()->routeIs('proposal.draft') ? route('proposal.draft') : (request()->routeIs('proposal.archive') ? route('proposal.archive') : route('proposal.index')) !!}',
                rowCallback: function (row, data) {
                    let select_all = $('input#select-all');
                    let input = $(`#select_proposal_${data.id}`);
                    let checked = ((select_all.prop('checked') && !select_all.prop('indeterminate')) || input.length);
                    $('input.select-one[type="checkbox"]', row).prop('checked', checked).trigger('change');
                },
                columns: [
                    {
                        data: 'id', name: 'id',
                        searchable: false, orderable: false,
                        className: 'dt-body-center dt-checkboxes-cell',
                        render: function (data, type, full, meta) {
                            return '<input type="checkbox" class="select-one" value="' + data + '">';
                        }
                    },
                    {data: 'number', name: 'number'},
                    {data: 'fullName', name: 'fullName', orderable: false},
                    {data: 'category.parent.name', name: 'category.parent.name', searchable: true,visible: false},
                    {data: 'category.name', name: 'category.name', visible: false},
                    {data: 'creditAmount', name: 'creditAmount'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'deadline', name: 'deadline'},
                    {data: 'email', name: 'email'},
                    {data: 'payoutAmount', name: 'payoutAmount', orderable: false, searchable: false},
                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ], createdRow: function (row, data, index) {
                    $(row).addClass('cursor-pointer');
                    $('td', row).eq(1).addClass(data['bgColor']);
                    $('td', row).eq(5).addClass(data['statusBgColor']);
                },
            });
            $('#btn-load-more').on('click', function (e) {
                e.stopPropagation();
                table.page.loadMore();
            });
            $('#select-all').on('click', function () {
                let rows = table.rows({'search': 'applied'}).nodes();
                $('input.select-one[type="checkbox"]', rows).prop('checked', this.checked).trigger('change');
            });
            proposal_form.on('change', 'input#select-all,input.select-one', function () {
                if ($(this).attr('class') === 'select-one') {
                    let id = $(this).val();
                    let elem_id = `select_proposal_${id}`
                    if (this.checked) {
                        if (!$(`#${elem_id}`).length) $(proposal_form)
                            .prepend($('<input>')
                                .attr('type', 'hidden')
                                .attr('id', elem_id)
                                .attr('class', 'select_proposal')
                                .attr('name', 'ids[]')
                                .val(id));
                    } else $(`#${elem_id}`).remove();
                }
                // If checkbox is not checked
                if (!this.checked) {
                    $('.select_proposal').remove();
                    var el = $('#select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if (el && el.checked && ('indeterminate' in el)) {
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
                }
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
                let link = $(this).find('.edit-link')[0];
                if (link && e.target.tagName.toLowerCase() === 'td') {
                    link.click();
                }
            });
            $("#category-filters > label").each(function (i, el) {
                $("#proposals_table_filter.dataTables_filter").prepend(el);
            });
            creditType.on('change', function () {
                let category = this.value;
                table.columns(3).search(category ? category : '', true, false);
                table.draw();
            });
            status.on('change', function () {
                let category = this.value;
                table.columns(7).search(category ? `${category}` : '', true, false);
                table.draw();
            });
            proposal_form.on('submit', async function (e) {
                let form = this;
                let rows_selected = table.column(0).checkboxes.selected();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function (index, rowId) {
                    // Create a hidden element
                    let proposal_id = parseInt($(rowId).text());
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'ids[]')
                            .val(proposal_id)
                    );
                });
                let classList = document.querySelectorAll(".border-red");
                [].forEach.call(classList, function (el) {
                    el.classList.remove("border-red");
                });
                let elems = document.querySelectorAll("p.text-danger");
                [].forEach.call(elems, function (el) {
                    el.remove();
                });

                try {
                    let data = new FormData(this);
                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: data,
                        cache: false,
                        contentType: false,
                        processData:false,
                        success: (response) => {
                            let success = response.status === 'success';
                            if (success) {
                                form.reset();
                                $('.summernote', proposal_form).summernote('code', '');
                            }
                            $('.modal', proposal_form).modal('hide');
                            let classNames = 'alert alert-' + (!success ? 'danger' : 'success');
                            $('#alert').attr('class', classNames).text(response.msg);
                        },
                        error: (response) => {
                            let data = response.responseJSON.errors;
                            for (let [name, errors] of Object.entries(data)) {
                                let field = form.querySelector(`[name="${name}"]`);
                                if (field === null) {
                                    let split = name.split('.');
                                    let iter = split[1] || 0;
                                    name = split[0];
                                    if (split.length > 2) {
                                        field = form.querySelector(`[name="${name}[${iter}][${split[2]}]`);
                                    } else {
                                        field = form.querySelector(`[name="${name}[${iter}]`);
                                    }
                                }

                                if (field) {
                                    let errElement = document.createElement("p");
                                    errElement.className = 'text-danger text-sm';
                                    errElement.textContent = errors[0];
                                    field.closest('div').appendChild(errElement);
                                    field.classList.add('border-red');
                                }
                            }
                        }
                    });
                } catch (e) {
                    console.error(e);
                }
                // Prevent actual form submission
                e.preventDefault();
                return false;
            });
            $('.modal', proposal_form).on('shown.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let url = button.data('url');
                proposal_form.attr('action', url);
            }).on('hidden.bs.modal', function (event) {
                proposal_form.attr('action', '#');
            });
        });
    </script>
@endpush
