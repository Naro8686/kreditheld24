<ul class="list-group">
    @php
        $files = [];
        if (isset($invoices)) $files = $invoices->pluck('invoice_file')->map(function ($file) {
            $file = trim($file, '/');
            return public_path("storage/$file");
        })->toArray()
    @endphp
    @foreach($invoices as $invoice)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{$invoice->number ?? $invoice->invoice_file}}
            <span class="badge badge-primary badge-pill">
                    <a class='btn btn-sm text-white'
                       href='{{route('admin.downloadFile', ['path' => $invoice->invoice_file])}}'><i
                            class="fas fa-download"></i></a>
                </span>
        </li>
    @endforeach
    @if(count($files))
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a class='btn btn-block btn-primary'
               href='{{route('admin.downloadFilesZip', ['files' => $files])}}'><i
                    class="fas fa-download"></i></a>
        </li>
    @endif
</ul>
