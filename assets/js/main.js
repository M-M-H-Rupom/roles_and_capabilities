;(function($){
    $(document).ready(function () {
        $('.user_action').on('click',function () { 
            let user_task = $(this).data('task');
            $.ajax({
                url: localize_user.ajax_url,
                type: "POST",
                data: {
                    'action' : 'user_data',
                    'user_nonce' : localize_user.user_nonce,
                    'user_action' : localize_user.user_action,
                    'user_task' : user_task
                },
                success: function (response) {
                    console.log(response['data'].user_email);
                },
                error: function(){
                    alert('failed');
                    console.error('failed');
                    console.log('failed');
                },
            });
        });
    });
})(jQuery);
// function current_user(){
//     alert('hello user')
// }