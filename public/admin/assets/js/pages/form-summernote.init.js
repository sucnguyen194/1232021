jQuery(document).ready(function($){
    $(".summernote").summernote({
        height:250,
        minHeight:null,
        maxHeight:null,
        focus:!1,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],

            ['fontname', ['fontname']],

            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        callbacks: {
            onBlurCodeview: function () {
                let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                $(this).val(codeviewHtml);
            }
        }
    }),
        $(".summerbody").summernote({
            height:550,
            minHeight:null,
            maxHeight:null,
            focus:!1,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],

                ['fontname', ['fontname']],

                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onBlurCodeview: function () {
                    let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                    $(this).val(codeviewHtml);
                }
            }
        }),
        $(".inline-editor").summernote({
            airMode:!0
        })
});
