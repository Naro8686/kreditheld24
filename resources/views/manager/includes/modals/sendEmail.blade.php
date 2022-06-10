<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">{{__('Send message')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label class="w-100">{{__('Theme')}}
                    <input id="subject" type="text" class="form-control"
                           name="subject" value="{{ old('subject')}}">
                </label>
                <label class="w-100">
                        <textarea rows="7" class="form-control summernote"
                                  name="message">{!! old('message') !!}</textarea>
                </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="attachment">
                    <label class="custom-file-label" for="customFile">{{__("Choose File")}}</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                <input type="submit" class="btn btn-primary">
            </div>
            @auth
                @include('email-templates.templates-list', ['templates' => auth()->user()->emailTemplates])
            @endauth
        </div>
    </div>
</div>
