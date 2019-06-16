<!-- Invitation Code Manager
ajax check invotation code status -->
<script>
    function checkCode() {
        var code = $('#customfield{$fieldid}').val().trim();
        var email = $('#inputEmail').val().trim();
        $.ajax({
            cache: false,
            type: 'POST',
            url: '{$systemurl}index.php?m=invitation_manager',
            data: {
                action : 'checkCode',
                code : code,
                email : email,
            },
            dataType:'json',
            async: true,
            beforeSend: function () {
                $('#InvitationCode').remove();
                $('#customfield{$fieldid}').after('<div class="InvitationCode" id="InvitationCode" style="color: #999; margin-top: 5px;"><span class="glyphicon glyphicon-refresh fa-spin" aria-hidden="true"></span><span class="InvitationContents"> 正在查询邀请码可用性...</span></div>');
            },
            success: function (data) {
                if (data.status == 'active') {
                    $('#InvitationCode').remove();
                    $('#customfield{$fieldid}').after('<div class="InvitationCode" id="InvitationCode" style="color: #5cb85c; margin-top: 5px;"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span><span class="InvitationContents"> ' + data.msg + '</span></div>');
                } else {
                    $('#InvitationCode').remove();
                    $('#customfield{$fieldid}').after('<div class="InvitationCode" id="InvitationCode" style="color: #fa2121; margin-top: 5px;"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span class="InvitationContents"> ' + data.msg + '</span></div>');
                }
            },
            error: function() {
                $('#InvitationCode').remove();
                $('#customfield{$fieldid}').after('<div class="InvitationCode" id="InvitationCode" style="color: #fa2121; margin-top: 5px;"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span class="InvitationContents"> 网络错误</span></div>');
            }
        });
    }
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $(function(){
        $('#customfield{$fieldid}').on('keyup',function(){
            var code = $('#customfield{$fieldid}').val();
            code = code.trim();
            var codeLength = code.length;
            if (code) {
                if (codeLength >= 6) {
                    delay(function(){
                        checkCode();
                    }, 500);
                }
            } else {
                $('#InvitationCode').remove();
            }
        });
        if ($('#customfield{$fieldid}').val().trim()) {
            checkCode();
        }
    });
</script>