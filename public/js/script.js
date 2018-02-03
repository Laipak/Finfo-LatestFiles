$(document).ready(function(){
    $('#menu li').each(function(){
        var url = window.location.pathname;
        if(url.indexOf($(this).attr('id')) > -1)
        {
            $(this).addClass('active');
        }
    });

    if(!$('#menu li').hasClass('active'))
    {
        $('#menu li#dashboard').addClass('active');
    }

    $('.btn-delete-all').click(function(){
        if($("[type=checkbox]:checked").length > 0)
        {
            if(confirm('Are you sure want to delete this?'))
            {
                var $form = $('#form');
                $form.attr('action',$form.find('#url').val() + "/soft-delete-multi");
                $form.submit();
            }else{
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });

    $('.btn-publish-all').click(function(){
        if($("[type=checkbox]:checked").length > 0)
        {
            if(confirm('Are you sure want to publish this?'))
            {
                var $form = $('#form');
                $form.attr('action',$form.find('#url').val() + "/publish-multi");
                $form.submit();
            }else{
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });

    $('.btn-unpublish-all').click(function(){
        if($("[type=checkbox]:checked").length > 0)
        {
            if(confirm('Are you sure want to unpublish this?'))
            {
                var $form = $('#form');
                $form.attr('action',$form.find('#url').val() + "/unpublish-multi");
                $form.submit();
            }else{
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });
});
