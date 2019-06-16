{literal}
<script>
    var completeFlag = true;
    function checkUpdate() {
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'addonmodules.php?module={/literal}{$modulename}{literal}',
            data: {
                action : 'checkUpdate',
            },
            dataType:'json',
            async: true,
            beforeSend: function () {
                $('#newVersionTip').hide();
                $('#newUpdate').hide();
                $('#noUpdate').hide();
                $('#errorUpdate').hide();
                $('#checkButton').html('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i> 正在检查更新');
            },
            success: function (data) {
                if (data.status == 'success') {
                    if (data.version > {/literal}{$modulevars['version']}{literal}) {
                        $('#newVersionTip').show();
                        $('#newUpdate').show();
                        $('#noUpdate').hide();
                        $('#errorUpdate').hide();
                        $('#checkButton').html('检查更新');
                        $('.newUpdateContents').html(data.version + '版本已经发布 <a href="' + data.updateurl + '" target="_blank">' + data.urlname + '</a>');
                    } else {
                        $('#newVersionTip').hide();
                        $('#noUpdate').show();
                        $('#newUpdate').hide();
                        $('#errorUpdate').hide();
                        $('#checkButton').html('检查更新');
                        $('.noUpdateContents').html('当前模块版本为最新版');
                    }
                } else {
                    $('#newVersionTip').hide();
                    $('#noUpdate').hide();
                    $('#newUpdate').hide();
                    $('#errorUpdate').show();
                    $('#checkButton').html('检查更新');
                    $('.errorUpdateContents').html(data.msg);
                }
            },
            error: function() {
                $('#newVersionTip').hide();
                $('#noUpdate').hide();
                $('#newUpdate').hide();
                $('#errorUpdate').show();
                $('#checkButton').html('检查更新');
                $('.errorUpdateContents').html('网络错误');
            }
        });
    }
    $(function(){
        $('#checkButton').click(function() {
            checkUpdate();
        });
        $(".nav-list-sm > li > a").click(function() {
            $("#collapseButton").click();
        });
        checkUpdate();
    });
    $('#table').bootstrapTable({
        ajax : function (request) {
            $.ajax({
                cache: false,
                type: 'POST',
                url: '{/literal}{$modulevars['modulelink']}{literal}',
                data: {
                    action : 'getLogData',
                },
                dataType:'json',
                async: true,
                success : function (data) {
                    if (data.status == 'success') {
                        request.success({
                            row : data.msg
                        });
                        $('#table').bootstrapTable('load', data.msg);
                    } else {
                        $('#table').bootstrapTable('removeAll');
                        $('#table').bootstrapTable('hideLoading');
                        $('#table').bootstrapTable('load', [{'old_amount' : '出现错误 ' + data.msg}]);
                    }
                },
                error:function(){
                    $('#table').bootstrapTable('removeAll');
                    $('#table').bootstrapTable('hideLoading');
                    $('#table').bootstrapTable('load', [{'old_amount' : '网络错误，无法加载表格内容'}]);
                }
            });
        },
        toolbar: '#toolbar',
        pagination: true,
        pageList: '[10, 25, 50, 100, 200]',
        pageSize: '25',
        paginationLoop: false,
        showPaginationSwitch: true,
        search: true,
        searchOnEnterKey: true,
        trimOnSearch: true,
        showRefresh: true,
        striped: true,
        showColumns: true,
        showExport: true,
        exportDataType: 'all',
        exportOptions:{
            fileName: '续费记录',
            worksheetName: 'sheet1',
        },
        columns: [{
            checkbox: true
        }, {
            field: 'id',
            title: 'ID'
        }, {
            field: 'level',
            title: '日志等级'
        }, {
            field: 'log',
            title: '日志内容'
        }, {
            field: 'created_at',
            title: '创建时间'
        }],
    });
    function delLogData() {
        var rows = $("#table").bootstrapTable('getSelections');
        if (rows.length == 0) {
            swal("请先选择要删除的记录", "", "error");
            return;
        }
        var id = '';
        for (var i = 0; i < rows.length; i++) {
            id += rows[i]['id'] + "|";
        }
        id = id.substring(0, id.length - 1);
        swal({
            title: "您确定要删除选中的记录吗?",
            text: "删除操作不可逆",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
        },
        function(){
            if(!completeFlag) {
                return;
            }

            $.ajax({
                cache: false,
                type: 'GET',
                url: 'addonmodules.php?module={/literal}{$modulename}{literal}',
                data: {
                    action : 'delLogData',
                    select : id,
                },
                dataType:'json',
                async: true,
                success: function (data) {
                    if (data.status == 'success') {
                        swal({
                            title: "选中记录删除成功",
                            text: "共删除 " + data.rows + " 条记录",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            $('#table').bootstrapTable('refreshOptions', true);
                        });
                    } else {
                        swal({
                            title: "选中记录删除失败",
                            text: data.msg,
                            confirmButtonText: "完成",
                            type: "error"
                        });
                    }
                },
                error: function () {
                    swal({
                        title: "网络错误",
                        confirmButtonText: "完成",
                        type: "error"
                    });
                }
            });
        });
    }
    function node_create() {
        swal({
            title: "您确定要手动创建节点吗?</br>我们更推荐使用自动开启服务",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'node_create'},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "创建成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
    function node_status_change(id, sign) {
        swal({
            title: "您确定要修改节点 ID " + id + " 在{/literal}{$modulename}{literal}中的状态吗?",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#66CCFF",
            confirmButtonText: "修改",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'node_status_change', id: id, sign: sign},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "修改成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
    function node_delete(id, sign) {
        swal({
            title: "您确定要删除节点 ID " + id + " 吗?",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'node_delete', id: id, sign: sign},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "删除成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
    function group_create() {
        swal({
            title: "您确定要创建新分组吗?",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'group_create'},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "创建成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
    function group_status_change(id, sign) {
        swal({
            title: "您确定要修改分组 ID " + id + " 在{/literal}{$modulename}{literal}中的状态吗?",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#66CCFF",
            confirmButtonText: "修改",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'group_status_change', id: id, sign: sign},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "修改成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
    function group_delete(id, sign) {
        swal({
            title: "您确定要删除分组 ID " + id + " 吗?",
            type: "warning",
            showCancelButton: true,
            html: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
        },
        function(){
            if(!completeFlag) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "addonmodules.php?module={/literal}{$modulename}{literal}",
                data: {action: 'group_delete', id: id, sign: sign},
                dataType: 'json',
                cache: false,
                html: true,
                async: true,
                beforeSend:function() {
                    completeFlag = false;
                },
                complete:function() {
                    completeFlag = true;
                },
                success: function(data) {
                    if(data.status=='success') {
                        swal({
                            title: "删除成功",
                            confirmButtonText: "完成",
                            type: "success"
                        },
                        function(){
                            window.location.reload();
                        });
                    } else if (data.status=='error') {
                        swal({
                            title: "出现错误",
                            text: data.msg,
                            type: "error"
                        });
                    };
                },
                error:function() {
                    swal("服务器忙，请稍后重试");
                }
            });
        });
    }
</script>
{/literal}