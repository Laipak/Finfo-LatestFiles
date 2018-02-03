$("#file_logo").on('change', function() {
    var file = this.files[0];
    var imagefile = file.type;

    var match = ["image/jpeg", "image/png", "image/jpg"];
    // validate file extension
    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
        alert('Please select image file.');
        $('#file_logo').val('');
        return false;
    } else {
        var formData = new FormData($('#frm-company')[0]);
        $.ajax({
            url: baseUrl + '/admin/company/upload/logo',
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function(data) {
                console.log(data);
                $('#company_logo').val(data);
                $('.company-logo').html('<img src="/'+data+'" class="img-logo" style="width:100%;position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
            },
        });
    }
});

$("#file_favicon").on('change', function() {
    var file = this.files[0];
    var imagefile = file.type;
    var match = ["image/jpeg", "image/png", "image/jpg", "image/x-icon"];
//    // validate file extension
    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]) )) {
        alert('Please select image file.');
        $('#file_favicon').val('');
        return false;
    } else {
        var formData = new FormData($('#frm-company')[0]);
        $.ajax({
            url: baseUrl + '/admin/company/upload/favicon',
            processData: false,
            contentType: false,
            type: "POST",
            data: formData,
            success: function(data) {
                $('#favicon').val(data);
                $('.favicon').html('<img src="/'+data+'" class="img-favicon" style="width:100%;position: absolute; margin: auto;top: 0;left: 0;right: 0;bottom: 0;" >');
            },
            error:function($data){
                console.log(imagefile+ "error ");
            }
        });
    }
});
