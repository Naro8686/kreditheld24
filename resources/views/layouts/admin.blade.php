<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">

    <title>{{ config('app.name', 'Laravel') . ' | '.(optional(auth()->user())->isAdmin() ? 'Admin' : 'Manager') }}</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Custom fonts for this template-->
    <link href="{{asset('adminPanel/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('adminPanel/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminPanel/summernote-0.8.18-dist/summernote.min.css')}}" rel="stylesheet">
    @stack('css')

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
           href="{{url(\App\Providers\RouteServiceProvider::HOME)}}">
            <div class="sidebar-brand-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="sidebar-brand-text mx-3">{{ __('Credit') }}</div>
        </a>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->

        <li @class(['nav-item','active' => request()->routeIs('dashboard')])>
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{__('Dashboard')}}</span></a>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('proposal.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('proposal.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-file-invoice-dollar"></i>
                <span>{{__('My Proposals')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('proposal.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->routeIs('proposal.index')]) href="{{route('proposal.index')}}">{{__('All')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('proposal.draft')]) href="{{route('proposal.draft')}}">{{__('Draft')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('proposal.archive')]) href="{{route('proposal.archive')}}">{{__('Archive')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('proposal.create')]) target="_blank"
                       href="{{route('proposal.create')}}">{{__('Create')}}</a>
                </div>
            </div>
        </li>
        <li @class(['nav-item','active' => request()->routeIs('contacts')])>
            <a class="nav-link" href="{{route('contacts')}}">
                <i class="fas fa-fw fa-address-book"></i>
                <span>{{__('Contacts')}}</span></a>
        </li>
        <li @class(['nav-item','active' => request()->routeIs('formulas')])>
            <a class="nav-link" href="{{route('formulas')}}">
                <i class="fas fa-fw fa-book-reader"></i>
                <span>{{__('Formulas')}}</span></a>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('email-templates.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('email-templates.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-mail-bulk"></i>
                <span>{{__('Templates Email')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('email-templates.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->routeIs('email-templates.index')]) href="{{route('email-templates.index')}}">{{__('All')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('email-templates.create')]) href="{{route('email-templates.create')}}">{{__('Create Email')}}</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">

        @role('admin')
        <div class="sidebar-heading">{{__('Admin')}}</div>
        <li @class(['nav-item','active' => request()->routeIs('admin.managers.*')])>
            <a class="nav-link" href="{{route('admin.managers.index')}}">
                <i class="fas fa-fw fa-users"></i>
                <span>{{__('Managers')}}</span></a>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('admin.proposals.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('admin.proposals.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-file-invoice-dollar"></i>
                <span>{{__('All proposal')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('admin.proposals.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->is('admin/proposals')]) href="{{route('admin.proposals.index')}}">{{__('Proposal')}}</a>
                    <a @class(['collapse-item','active' => request()->is('admin/proposals/archive')]) href="{{route('admin.proposals.archive')}}">{{__('Archive')}}</a>
                </div>
            </div>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('admin.email.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('admin.email.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-envelope"></i>
                <span>{{__('Send message')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('admin.email.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->is('admin/email/manager*')]) href="{{route('admin.email.index',['type'=>'manager'])}}">{{__('Manager')}}</a>
                    <a @class(['collapse-item','active' => request()->is('admin/email/client*')]) href="{{route('admin.email.index',['type'=>'client'])}}">{{__('Client')}}</a>
                </div>
            </div>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('admin.contacts.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('admin.contacts.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-address-book"></i>
                <span>{{__('Contacts')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('admin.contacts.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->routeIs('admin.contacts.index')]) href="{{route('admin.contacts.index')}}">{{__('All')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('admin.contacts.create')]) href="{{route('admin.contacts.create')}}">{{__('Add')}}</a>
                </div>
            </div>
        </li>
        <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
            @class(['nav-item','active' => request()->routeIs('admin.formulas.*')])>
            <a @class(['nav-link','collapsed' => !request()->routeIs('admin.formulas.*')])
               href="#" data-toggle="collapse" aria-expanded="true"
               x-bind:data-target="'#' + collapse_id"
               x-bind:aria-controls="collapse_id">
                <i class="fas fa-fw fa-book-reader"></i>
                <span>{{__('Formulas')}}</span>
            </a>
            <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id" data-parent="#accordionSidebar"
                @class(['collapse','show' => request()->routeIs('admin.formulas.*')])>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a @class(['collapse-item','active' => request()->routeIs('admin.formulas.index')]) href="{{route('admin.formulas.index')}}">{{__('All')}}</a>
                    <a @class(['collapse-item','active' => request()->routeIs('admin.formulas.create')]) href="{{route('admin.formulas.create')}}">{{__('Add')}}</a>
                </div>
            </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        @endrole

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        <hr class="sidebar-divider d-none d-md-block">
        <ul class="navbar-nav" id="accordionSidebarFooter">
            @if($contact = \App\Models\Contact::orderByDesc('id')->first())
                <li x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading') }"
                    @class(['nav-item','contact','active' => true])>
                    <a @class(['nav-link','collapsed' => false])
                       href="#" data-toggle="collapse" aria-expanded="true"
                       x-bind:data-target="'#' + collapse_id"
                       x-bind:aria-controls="collapse_id">
                        <span>{{__('Contact')}}</span>
                    </a>
                    <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id"
                         data-parent="#accordionSidebarFooter"
                        @class(['collapse','multi-collapse','contact-block','show' => false])>
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item"
                               href="javascript:void(0)">
                                <i class="fas fa-fw fa-user"></i>
                                <span>{{"$contact->firstName $contact->lastName"}}</span>
                            </a>
                            <a class="collapse-item" href="mailto:{{$contact->email}}">
                                <i class="fas fa-fw fa-mail-bulk"></i>
                                <span>{{$contact->email}}</span>
                            </a>
                            @if($contact->phone)
                                <a class="collapse-item" href="tel:{{$contact->phone}}">
                                    <i class="fas fa-fw fa-phone"></i>
                                    <span>{{$contact->phone}}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif
            <li x-show="show" x-data="{ collapse_id: $id('collapse'),heading_id:$id('heading'),show:false }"
                @class(['nav-item','formulas','active' => true])>
                <a @class(['nav-link','collapsed' => false])
                   href="#" data-toggle="collapse" aria-expanded="true"
                   x-bind:data-target="'#' + collapse_id"
                   x-bind:aria-controls="collapse_id">
                    <span>{{__('Formulas')}}</span>
                </a>
                <div x-bind:id="collapse_id" x-bind:aria-labelledby="heading_id"
                     data-parent="#accordionSidebarFooter"
                    @class(['collapse','multi-collapse','formulas-block','show' => false])>
                    <div class="bg-white py-2 collapse-inner rounded">
                        @foreach(\App\Models\Formula::orderByDesc('id')->limit(5)->get() as $formula)
                            <a x-init="if(!show) show = true"
                               @class(['collapse-item','active' => request()->routeIs('admin.formulas.index')])
                               target="_blank" href="{{route('readFile', ['path' => $formula->file])}}">
                                <i class="fas fa-fw fa-file-alt"></i>
                                <span>{{$formula->name}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
        </ul>
    </ul>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span
                                class="mr-2 d-none d-lg-inline text-gray-600 small">{{optional(auth()->user())->name}}</span>
                            <img class="img-profile rounded-circle" alt=""
                                 src="{{asset('adminPanel/img/undraw_profile.svg')}}">
                        </a>

                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{route('profile.index')}}">
                                <i class="fas fa-fw fa-user mr-2 text-gray-400"></i>
                                {{ __('Personal Area') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{__('Logout')}}
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @yield('content')
                <div id="alert"
                     @class(['alert','alert-success' => session('success'),'alert-danger' => session('error')]) role="alert">
                    {!! session('success') ?? session('error') !!}
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{ config('app.name', 'Laravel').' '.date('Y') }}</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Ready to Leave?')}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div
                class="modal-body">{{__('Select "Logout" below if you are ready to end your current session.')}}</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('Cancel')}}</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">{{__('Delete')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center">{{__('Are you sure ?')}}</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
                <form action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{__('Yes')}}</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('adminPanel/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('adminPanel/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('adminPanel/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('adminPanel/js/sb-admin-2.min.js')}}"></script>
<script src="{{asset('adminPanel/summernote-0.8.18-dist/summernote.min.js')}}"></script>
<script>
    $(document).ready(function () {
        let accordionSidebarFooter = $("ul#accordionSidebarFooter .collapse");
        $('.js-form').on('submit', function (e) {
            e.preventDefault();
        });
        $('#confirmModal').on('shown.bs.modal', function (event) {
            let modal = $(this);
            let form = modal.find('form');
            let button = $(event.relatedTarget);
            let url = button.data('url');
            let title = button.data('title') ?? '{{__('Delete')}}';
            let method = button.data('method') ?? 'DELETE';
            let method_inp = form.find('input[name="_method"]');
            form.attr('action', url);
            modal.find('#modalLabel').text(title);
            if (method_inp.length) {
                method_inp.val(method.toUpperCase());
            }
        });
        $('textarea.summernote').summernote({
            height: 300,
            focus: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']],
            ]
        });
        $(".custom-file-input").on("change", function () {
            let fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        if ($(window).width() < 768) {
            accordionSidebarFooter.collapse("hide");
        } else {
            accordionSidebarFooter.addClass("show");
        }
        $(window).resize(function () {
            if ($(window).width() < 768) {
                accordionSidebarFooter.collapse("hide");
            } else {
                accordionSidebarFooter.addClass("show");
            }
        });
    });
</script>
@stack('js')
</body>

</html>
