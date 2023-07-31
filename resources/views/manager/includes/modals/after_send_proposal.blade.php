<div class="modal fade" id="after_send_proposal" tabindex="-1" role="dialog"
     aria-labelledby="after_send_proposal_label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{--            <div class="modal-header">--}}
            {{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
            {{--                    <span aria-hidden="true">&times;</span>--}}
            {{--                </button>--}}
            {{--            </div>--}}
            {{--            <div class="modal-body"></div>--}}
            <div class="modal-footer" style="justify-content: space-around">
                <a href="{{ url('/') }}" class="btn btn-outline-success">{{ __('Go to home page') }}</a>
                <a href="{{ route('proposal.create') }}"
                   class="btn btn-outline-primary">{{ __('Add new proposal') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
