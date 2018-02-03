$("#file_feature_image").on('change', function() {
    var file = this.files[0];
    var imagefile = file.type;

    var match = ["image/jpeg", "image/png", "image/jpg"];
    // validate file extension
    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
        alert('Please select image file.');
        $('#file_feature_image').val('');
        return false;
    } else {
        var formData = new FormData($('#frm_add_page')[0]);
        $.ajax({
            url: baseUrl + '/admin/webpage/upload-feature-image',
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function(data) {
                console.log(data);
                $('#feature_image').val(data);
                $('.feature-image').html('<img src="/img/client/webpage/feature-images/'+data+'" class="feature-img" style="width:100%; height:177px; position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
            },
        });
    }
});