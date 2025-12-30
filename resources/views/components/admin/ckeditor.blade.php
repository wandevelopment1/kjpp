<script>
    // Only initialize CKEditor if content element exists
    if (document.getElementById('content')) {
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    }
</script>
