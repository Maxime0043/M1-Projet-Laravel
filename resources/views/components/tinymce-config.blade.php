<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce',
        height: 300,

        setup: function (editor) {
            editor.on('init change', function () {
                editor.save();
            });
        },
        plugins: [
            "advlist autolink lists link image charmap preview anchor",
            "searchreplace visualblocks code",
            "insertdatetime table paste imagetools"
        ],
        menubar: 'edit view insert format',
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code visualblocks preview | image link table ",

        relative_urls: false,
        images_upload_handler: function(blobInfo, success, failure) {
            console.log(blobInfo);
            let xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', "{{ route('chapter-upload-image') }}");

            let token = "{{ $token }}";

            xhr.setRequestHeader('X-CSRF-Token', token);
            xhr.onload = function() {
                let json;

                if(xhr.status != 200) {
                    failure('Erreur HTTP: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if(!json || typeof json.location != "string") {
                    failure('JSON Invalide: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    });
</script>
