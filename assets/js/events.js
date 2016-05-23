var ticketValidators = {
        validators: {
            notEmpty: {
                message: '请选择门票'
            }
        }
    },
    users = []
    ;
$(document).ready(function() {

    var paycode = 'huikuan';
    $('#submit_btn').click(function(){
        var eventid = $('input[name=event_id]').val();
        $.request($('input[name=form_request]').val(), {
            data: {userlist : users, event_id : eventid, pay_code : paycode},
            success: function(data) {

                if (paycode == 'alipay') {
                    //付款
                    pingppPc.createPayment(data.result, function(result, err){
                        console.log(data.result);
                    // 处理错误信息
                    });

                } else {
                    document.location.href = data.result;
                }
            }
        })
    });

    $('input:radio[name="payment"]').on('change', function () {
        $('input:radio[name="payment"]').each(function() {
            attr_id = $(this).attr('id');
            if ($(this).is(':checked') == true) {
                paycode = $(this).val();
                $("#"+attr_id+"_text").show(300);
            }else{
                $("#"+attr_id+"_text").hide(300);
            }
        });
    });

	$('.submit').click(function(){
		$('#addPersonForm').submit();
	});
    $('#show_pay_btn').click(function(){
        $(this).hide();
        $('#payment_type').show(300);
    });

    $('#user_add_btn').click(function(){
        $(this).hide();
        $('#addPersonForm')[0].reset();
        // 重置验证http://www.zhihu.com/question/24912747/answer/89048038
        // http://stackoverflow.com/questions/26670478/bootstrap-validator-resetform
        $('#addPersonForm').bootstrapValidator('resetForm', true);
        $('input[name=tmp_user_id]').val(0);
        $('#user_add').show(300);
    });

    $('#addPersonForm').bootstrapValidator({
//        live: 'disabled',
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'The first name is required and cannot be empty'
                    }
                }
            },
            'last_name': {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    }
                }
            },
            'company': {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    }
                }
            },
            'title': {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    }
                }
            },
            'mobile': {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    }
                }
            },
            'email': {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            'tickets[]': {
                validators: {
                    notEmpty: {
                        message: '请选择门票'
                    }
                }
            }
        }
    })    
    .on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var $form = $(e.target);

            var tmp_user_id = 0;
            var tickets_id =  $("input[name='tickets[]']:checked").map(function(){
                                    return this.value;
                                }).get();
            if($('input[name=tmp_user_id]').val() == 0){
                tmp_user_id = users.length == 0 ? 1 : users[users.length - 1].id + 1;

                var tmp_user = {
                    "id": tmp_user_id,
                    "first_name": $('input[name=first_name]').val(),
                    "last_name": $('input[name=last_name]').val(),
                    "company": $('input[name=company]').val(),
                    "title": $('input[name=title]').val(),
                    "mobile": $('input[name=mobile]').val(),
                    "email": $('input[name=email]').val(),
                    "tickets": tickets_id
                };
                //alert(tmp_user);
                users.push(tmp_user);

            }else{
                tmp_user_id =$('input[name=tmp_user_id]').val();
                $.each(users, function(idx, obj) {
                    if(obj.id == tmp_user_id){
                        users[idx].first_name = $('input[name=first_name]').val();
                        users[idx].last_name = $('input[name=last_name]').val();
                        users[idx].company = $('input[name=company]').val();
                        users[idx].title = $('input[name=title]').val();
                        users[idx].mobile = $('input[name=mobile]').val();
                        users[idx].email = $('input[name=email]').val();
                        users[idx].tickets = tickets_id;
                        return false;
                    }
                });
            }
            render_userlist(users);
            $('#show_pay_btn').show(300);



        });

});

function render_userlist(users){
    var user_list_html ="",
        event_type = '',
        total_amount = 0;
    $.each(users, function(idx, obj) {
        user_list_html +='<tr class="ng-scope"><td class="col-md-2 ng-binding">';
        user_list_html +=obj.first_name;
        user_list_html +=' ';
        user_list_html +=obj.last_name;
        user_list_html +='</td><td class="col-md-2 ng-binding">';
        user_list_html +=obj.company;
        user_list_html +='</td><td class="col-md-2 ng-binding">';
        user_list_html +=obj.title;
        user_list_html +='</td><td class="col-md-2 ng-binding">';
        user_list_html +=obj.mobile;
        user_list_html +='</td><td class="col-md-2 ng-binding">';
        user_list_html +=obj.email;
        user_list_html +='</td><td class="col-md-1 text-center"><a onclick="user_edit(';
        user_list_html +=obj.id;
        user_list_html +=')">编辑</a><a onclick="user_del(';
        user_list_html +=obj.id;
        user_list_html +=')">删除</a></td></tr>';

    });

        user_list_html += '<tr class="user_info_all"><td colspan="3" class="col-md-5 ng-binding">';
        user_list_html += event_type;
        user_list_html += '</td><td class="col-md-2 ng-binding">';
        user_list_html += '数量：1';
        user_list_html += '</td><td class="col-md-2 ng-binding">';
        user_list_html += '优惠金额：￥0';
        user_list_html += '</td><td class="col-md-2 text-center ng-binding">';
        user_list_html += total_amount;
        user_list_html += '</td></tr>';
      


    $('.user_list_body').empty();
    $('.user_list_body').append(user_list_html);
    $('#user_list').show(300);
    $('#user_add').hide(300);
    $('#user_add_btn').show(300);
}
function user_edit(id){
    $.each(users, function(idx, obj) {
        if(obj.id == id){
            $('input[name=first_name]').val(obj.first_name);
            $('input[name=last_name]').val(obj.last_name);
            $('input[name=company]').val(obj.company);
            $('input[name=title]').val(obj.title);
            $('input[name=mobile]').val(obj.mobile);
            $('input[name=email]').val(obj.email);
            $('input[name=tmp_user_id]').val(obj.id);

            $('input:checkbox[name="tickets[]"]').each(function(t_index,t_value) {
                if ($.inArray(t_value.value, obj.tickets) >= 0) {
                    t_value.checked = true;
                }else{alert(111);
                    t_value.checked = false;
                }
            });
            return false;
        }
    });
    $('#user_add').show(300);
    $('#user_add_btn').hide(300);
}
function user_del(id){
    $.each(users, function(idx, obj) {
        if(obj.id == id){
            users.splice(idx, 1);
            return false;
        }
    });
    if(users.length == 0){
        $('#addPersonForm')[0].reset();
        $('#addPersonForm').bootstrapValidator('resetForm', true);
        $('input[name=tmp_user_id]').val(0);
        $('#user_add').show(300);
        $('#user_list').hide(300);
        $('#show_pay_btn').hide(300);
    }else{
        render_userlist(users);
    }
}
