<link rel="stylesheet" type="text/css" media="screen" href="{$systemurl}modules/servers/SCloud/templates/assets/css/style.css?v={$versionHash}">
<!-- 默认WHMCS已经引用该ICON源，如果您的主题无法正常显示ICON，请取消该注释。-->
<!--
<link rel="stylesheet" type="text/css" media="screen" href="{$systemurl}modules/servers/SCloud/templates/assets/icons/font-awesome-4.7.0/css/font-awesome.min.css">
-->
<script type="text/javascript" src="{$systemurl}modules/servers/SCloud/templates/assets/javascript/main.js?v={$versionHash}"></script>
<script type="text/javascript" src="{$systemurl}modules/servers/SCloud/templates/assets/javascript/layer/layer.js?v={$versionHash}"></script>
<script type="text/javascript" src="{$systemurl}modules/servers/SCloud/templates/assets/javascript/clipboard.min.js"></script>
<div id="SCloud">
    <div class="row">
        <div class="col-md-12">
            {if $notice|@count neq 0}
            <div class="notice-area">
                <div class="notice-box">
                    {foreach $notice as $value}
                    <div class="ann-text">{$value}</div>
                    {/foreach}
                </div>
            </div>
            {/if}
            <div class="top-area">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-xs-12 col-12">
                        <div class="product-box">
                            <div class="product-title">
                                {$product}
                            </div>
                            <div class="product-smalltitle">
                                <span class="next-due"{if $nextduedate eq '-'} style="visibility: hidden;"{/if}>下次付款 : {$nextduedate}</span>
                                <button class="btn btn-primary btn-xs"{if $nextduedate eq '-'} style="visibility: hidden;"{/if} onclick="window.open('{$systemurl}index.php?m=renewal&action=renew&sid={$serviceid}', '_blank');"><i class="fa fa-credit-card" aria-hidden="true"></i> 续费服务</button>
                            </div>
                            <div class="product-info">
                                <div class="info-list">
                                    <span class="info-title">产品编号</span>
                                    <span class="info-text">{$serviceid}</span>
                                </div>
                                <div class="info-list">
                                    <span class="info-title">客户端限制数量</span>
                                    <span class="info-text">{$templates['clientlimit']} 个</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-xs-12 col-12">
                        <div class="product-box ips">
                            <div class="ipset">
                                <div class="inputip-area">
                                    <div class="input-title">
                                        <span>流量使用情况</span>
                                        <label>剩余: {$templates['lefted']} G</label>
                                    </div>
                                    <p>上传已使用： {$templates['uploaded']} M</p>
                                    <p>下载已使用： {$templates['downloaded']} M</p>
                                    <div class="progress progress-striped progress-sm">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{($templates['used']/$templates['bandwidth'])*100}" aria-valuemin="0" aria-valuemax="100" style="width: {($templates['used']/$templates['bandwidth'])*100}%">
                                            <span class="sr-only">{($templates['uploaded']/$templates['bandwidth'])*100}% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="currentip-area">
                                    <div class="current-ip">您的设备当前公网 IP: <span id="public-ip-content">{$templates['client_ip']}</span></div>
                                </div>
                            </div>
                            <div class="ip-list">
                                <div class="ip-title">
                                    <span>节点使用情况</span>
                                </div>
                                <div class="ip-group">
                                    <div class="no-ip">
                                        编写中
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="node-area">
                <div class="row">
                    <div class="{if $document|@count eq 0}col-sm-12{else}col-lg-8 col-md-12{/if} col-xs-12 col-12">
                        {foreach $node as $key => $value}
                            {foreach $value['nodes'] as $k => $v}
                                <div class="node-card" id="node-{$value["id"]}-{$v["id"]}">
                                    <div class="top-box">
                                        <div class="node-title-area col-lg-3 col-sm-5 col-xs-12 col-12">
                                            {if !empty($v["country"]|trim)}
                                            <div class="node-flag">
                                                <img src="{$systemurl}modules/servers/SCloud/templates/assets/images/flags/{$v["country"]|trim}.svg">
                                            </div>
                                            {/if}
                                            <div class="node-name">{$v['name']|trim}</div>
                                        </div>
                                        <div class="node-address-area col-lg-3 col-sm-7 col-xs-12 col-12">
                                            <div class="ip-address">
                                                <span>主:</span>
                                                <a id="main-{$key}" value="{$v["values"]->ip}">{$v["values"]->ip}</a>
                                                <i data-clipboard-target="#main-{$key}" class="fa fa-clone"></i>
                                            </div>
                                            <div class="ip-address">
                                                <span>所属分组:</span>
                                                <a id="standby-{$key}">{$value["name"]|trim}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bottom-box col-md-12">
                                        <div class="node-tag-area">
                                            <div class="node-tag">
                                                <label>查看配置信息</label>
                                            </div>
                                        </div>
                                        <div class="node-open-action">
                                            <button class="btn btn-open-action btn-xs">
                                                <span>展开</span>
                                                <i class="fa fa-chevron-down rotate-back"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="action-box">
                                        {foreach $v["subscribe"] as $key => $subvalue}
                                            <div class="action-input">
                                                <label for=""> {$subvalue["type"]}</label>
                                                <div class="input-group">
                                                    <input id="url-{$value["id"]}-{$v["id"]}-{$subvalue["type"]}" class="form-control" value="{$subvalue["subscribe"]}" type="text" readonly="true">
                                                    <span class="input-group-btn">
                                                        <button id="url-btn-{$key}" data-clipboard-target="#url-{$value["id"]}-{$v["id"]}-{$subvalue["type"]}" class="btn btn-primary copy-btn">复制</button>
                                                    </span>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            {/foreach}
                        {/foreach}
                    </div>
                    {if $subscribe|@count neq 0}
                    <div class="col-lg-4 col-md-12 col-xs-12 col-12">
                        <div class="doc-card">
                            <div class="card-title">
                                <span>订阅链接</span>
                            </div>
                            <div class="card-content">
                                {foreach $subscribe as $value}
                                    {foreach $value as $vvalue}
                                        <p>{$vvalue["type"]}</p>
                                        <div class="input-group">
                                            <input id="subscribe-{$vvalue["id"]}" class="form-control" value="https://{$HTTP_HOST}/modules/servers/Scloud/api.php?sid={$serviceid}&token={$subscribe_token}&{$vvalue["entrance"]}" type="text" readonly="true" >
                                            <span class="input-group-btn">
                                                <button id="subscribe-{$vvalue["id"]}" data-clipboard-target="#subscribe-{$vvalue["id"]}" class="btn btn-primary copy-btn">复制</button>
                                            </span>
                                        </div>
                                        </br>
                                    {/foreach}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                    {/if}
                    {if $document|@count neq 0}
                    <div class="col-lg-4 col-md-12 col-xs-12 col-12">
                        <div class="doc-card">
                            <div class="card-title">
                                <span>帮助文档</span>
                            </div>
                            <div class="card-content">
                                {foreach $document as $value}
                                {$value=("|"|explode:$value)}
                                <div class="card-item" onclick="window.open('{$value[1]}', '_blank');"><i class="fa fa-link"></i> {$value[0]}</div>
                                {/foreach}
                            </div>
                            <div class="card-bottom" onclick="window.open('{$systemurl}submitticket.php', '_blank');">
                                <div class="bottom-item">
                                    <span>联系客服</span>
                                    <i class="fa fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>

{literal}
<script>
    'use script';
    $(document).ready(function() {
        //解决six主题宽度过小的问题
        if ("{/literal}{$template}{literal}" == "six") {
            $(".container").each(function(){
                $('.sidebar').attr("class","hidden");
                $('.pull-md-right').attr("class","col-sm-12");
                $('.header-lined').parent().css("display","none")
            });
        }
        //脚本复制
        $('.copy-btn').click(function() {
            let copyscript = new ClipboardJS('.copy-btn');
            let copyid = $(this).attr('id')
            copyscript.on('success', function(e) {
                layer.tips('复制成功',$('#'+copyid),{
                    tips: [1, '#333333']
                })
            });
            copyscript.on('error', function(e) {
                layer.tips('复制失败',$('#'+copyid),{
                    tips: [1, '#333333']
                })
            });
        });
        //IP地址复制
        $('.fa-clone').click(function() {
            let copyip = new ClipboardJS('.fa-clone');
            let copyid = $(this).prev('a').attr('id');
            copyip.on('success', function(e) {
                layer.tips('复制成功',$("#"+copyid),{
                    tips: [1, '#333333']
                })
            });
            copyip.on('error', function(e) {
                layer.tips('复制失败',$("#"+copyid),{
                    tips: [1, '#333333']
                })
            });
        });
        //展开操作面板
        $('.btn-open-action').click(function() {
            $('.action-box').each(function(){
                $(this).slideUp(300);
            })
            $('.btn-open-action').each(function(){
                $(this).children('span').text('展开');
                $(this).children('i').removeClass('rotate');
                $(this).children('i').addClass('rotate-back');
            })
            let open_node = $(this).parent().parent().parent('.node-card').attr('id');
            if ($('#'+open_node+" .action-box").css('display') == "none") {
                $(this).children('span').text('收起');
                $(this).children('i').removeClass('rotate-back');
                $(this).children('i').addClass('rotate');
                $('#'+open_node+" .action-box").slideDown(300);
            } else {
                $('#'+open_node+" .action-box").slideUp(300);
                $(this).children('span').text('展开');
                $(this).children('i').removeClass('rotate');
                $(this).children('i').addClass('rotate-back');
            }
        });
        $('#addAuthIP').click(function() {
            let auth_ip = $('#auth-ip-input').val();
            if (auth_ip == '') {
                layer.msg('待添加的授权地址不能为空！');
                return;
            }
            addAuthIP(auth_ip);
        });
        $('#add-if-empty').click(function() {
            layer.prompt({
                formType: 0,
                title: '请输入值',
                placeholder: '请输入您希望添加的IP',
                area: ['800px', '350px'],
            }, function(value, index, elem){
                layer.close(index);
                addAuthIP(value);
            });
        });
        $('#add-from-pub-ip').click(function() {
            let server = $('#public-ip-content').html();
            addAuthIP(server);
        });
        $('#update-ip').click(function() {
            $.ajax({
                method: "POST",
                url: '{/literal}{$systemurl}{literal}index.php?m=SCloud',
                data: {
                    action: 'getPubIP',
                },
                dataType: 'json',
                cache: false,
                html: true,
                beforeSend:function() {
                    loadtip = layer.load(1, {
                      shade: [0.3, '#000000'],
                    });
                },
                complete:function() {
                    layer.close(loadtip);
                },
                success: function(data) {
                    if(data.status == 'success') {
                        $('#public-ip-content').html(data.msg);
                    } else if (data.status == 'error') {
                        layer.msg('公网IP获取失败: ' + data.msg);
                    };
                },
                error:function() {
                    layer.msg('网络错误');
                }
            });
        });
    });
    function addAuthIP(auth_ip) {
        layer.confirm('您是否想添加 ' + auth_ip + ' 为授权地址?',{
            btn: ['确定', '取消']
        }, function() {
            let loadtip;
            $.ajax({
                method: "POST",
                url: '{/literal}{$systemurl}{literal}index.php?m=SCloud',
                data: {
                    action: 'setIP',
                    sid: '{/literal}{$serviceid}{literal}',
                    method: 'add',
                    server: auth_ip,
                },
                dataType: 'json',
                cache: false,
                html: true,
                beforeSend: function() {
                    loadtip = layer.load(1, {
                      shade: [0.3, '#000000'],
                    });
                },
                complete: function() {
                    layer.close(loadtip);
                },
                success: function(data) {
                    if(data.status == 'success') {
                        layer.msg('添加成功！');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (data.status == 'error') {
                        layer.msg('添加失败: ' + data.msg);
                    };
                },
                error:function() {
                    layer.msg('网络错误');
                }
            });
        }, function() {
            layer.close(layer.index);
        });
    }
    function delAuthIP(obj) {
        let auth_ip = obj.parentNode.children[0].innerHTML;
        layer.confirm('您是否想删除授权地址 ' + auth_ip + ' ?',{
            btn: ['确定', '取消']
        }, function() {
            let loadtip;
            $.ajax({
                method: "POST",
                url: '{/literal}{$systemurl}{literal}index.php?m=SCloud',
                data: {
                    action: 'setIP',
                    sid: '{/literal}{$serviceid}{literal}',
                    method: 'del',
                    server: auth_ip,
                },
                dataType: 'json',
                cache: false,
                html: true,
                beforeSend: function() {
                    loadtip = layer.load(1, {
                      shade: [0.3, '#000000'],
                    });
                },
                complete: function() {
                    layer.close(loadtip);
                },
                success: function(data) {
                    if(data.status == 'success') {
                        layer.msg('删除成功！');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (data.status == 'error') {
                        layer.msg('删除失败: ' + data.msg);
                    };
                },
                error:function() {
                    layer.msg('网络错误');
                }
            });
        }, function() {
            layer.close(layer.index);
        });
    }
</script>
{/literal}