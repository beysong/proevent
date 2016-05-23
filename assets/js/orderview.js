$(document).ready(function() {
    $('#alipay_pay').click(function(){
        var order_id = $('input[name=order_id]').val();
        $.request($('input[name=form_request]').val(), {
            data: {order_id : order_id},
            success: function(data) {
                //付款
                pingppPc.createPayment(data.result, function(result, err){
                // 处理错误信息
                console.log(result);
                });
            }
        })
    });

    $('input:radio[name="payment"]').on('change', function () {
        $('input:radio[name="payment"]').each(function() {
            attr_id = $(this).attr('id');
            if ($(this).is(':checked') == true) {
                $("#"+attr_id+"_text").show(300);
            }else{
                $("#"+attr_id+"_text").hide(300);
            }
        });
    });


});


