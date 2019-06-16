<div role="tabpanel" class="tab-pane {if $active == "groups"}active{/if}" id="groups">
    <div class="row" id="plugin">
    {if $page['name'] eq 'home'}
        {if !empty($templates['groups'])}
        <div class="col-xs-12 col-sm-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 当前页面能控制分组能否在SCloud产品服务中使用
            </div>
        </div>
        <div>
            <span class="badge">共找到 {$templates['groups']|@count} 个分组</span>
            <div class="home-title">当前已存在的分组  <button class="btn btn-default btn-md" onClick="javascript:group_create();"><i class="fa fa-plus" aria-hidden="true"></i> 添加分组</button></div>
        </div>
        <div class="home-body">
            <table class="table home-table">
                <thead>
                    <tr>
                        <th>分组ID</th>
                        <th>分组名称</th>
                        <th>分组节点</th>
                        <th>分组状态</th>
                        <th>创建时间</th>
                        <th>上次修改时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $templates['groups'] as $key => $value}
                        <tr>
                        <td>{$value['id']}</td>
                        <td>{$value['value']->name}</td>
                        <td>{$value['value']->nodes}</td>
                        <td>{$value['status']}</td>
                        <td>{if $value['value']->created_at eq '0000-00-00'}-{else}{$value['value']->created_at}{/if}</td>
                        <td>{if $value['value']->updated_at eq '0000-00-00'}-{else}{$value['value']->updated_at}{/if}</td>
                        <td>
                            <div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group">
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="group_info" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs"{if !$value['status']} disabled="disabled"{/if}>
                                        编辑分组信息
                                    </button>
                                </form>
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="group_nodes" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs"{if !$value['status']} disabled="disabled"{/if}>
                                        编辑分组节点
                                    </button>
                                </form>
                                <button class="btn btn-xs btn-primary autoset" onClick="javascript:group_status_change('{$value['id']}', '{$value['sign']}');">启用/禁用</button>
                                <button class="btn btn-xs btn-danger autohides" onClick="javascript:group_delete('{$value['id']}', '{$value['sign']}');">删除</button>
                            </div>
                            </td>
                        </tr>
                    {/foreach}                
                </tbody>
            </table>
        </div> 
        {/if}
        {if empty($templates['groups'])}
            <div class="error-msg">
                <i class="fa fa-exclamation-triangle warning-img" aria-hidden="true"></i>
                <div class="error-text">您目前还未设置有效的分组</div>
                <a href="#">
                    <button class="btn btn-default btn-md" onClick="javascript:group_create();"><i class="fa fa-plus" aria-hidden="true"></i> 添加分组</button>
                </a>
            </div>
        {/if}
    {elseif $page['name'] eq 'group_info'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑分组信息</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>分组名称</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="group_info">
                            <input type="hidden" name="action" value="group_info_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" name="info">{$templates['group']->name}</textarea>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('group_info').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {elseif $page['name'] eq 'group_nodes'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑分组节点</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>选中分组节点</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="group_nodes">
                            <input type="hidden" name="action" value="group_nodes_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                {foreach $templates['nodes'] as $key => $value}
                                    <label class="form-control"><input name="nodes[]" type="checkbox" value="{$value['id']}" /{if $value['checked']}checked{/if}>    {$value['name']} (ID: {$value['id']}, UUID: {$value['uuid']})</label>
                                {/foreach}
                                </br>
                                <label class="form-control"><input type="checkbox" id="selectAll" />   全选</label>
                                <label class="form-control"><input type="checkbox" id="unSelectAll" />   全不选</label>
                                <script>
                                    $("#selectAll").click(function(){
                                        var items = document.getElementsByName("nodes[]");
                                        for(var x = 0; x < items.length; x++){
                                            items[x].checked = "checked";
                                        }
                                        document.getElementById("unSelectAll").checked = "";
                                    })
                                    $("#unSelectAll").click(function(){
                                        var items = document.getElementsByName("nodes[]");
                                        for(var x = 0; x < items.length; x++){
                                            items[x].checked = "";
                                        }
                                        document.getElementById("selectAll").checked = "";
                                    })
                                </script>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('group_nodes').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {/if}
    </div>
</div>