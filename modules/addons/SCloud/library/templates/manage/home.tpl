<div role="tabpanel" class="tab-pane {if $active == "home"}active{/if}" id="home">
    <div class="row" id="plugin">
    {if $page['name'] eq 'home'}
        {if !empty($templates['nodes'])}
        <div class="col-xs-12 col-sm-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 当前页面能控制节点能否在SCloud产品服务中使用
            </div>
            <div class="alert alert-info alert-dismissible" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> <strong>UUID</strong> 是节点的唯一识别ID
            </div>
        </div>
        <div>
            <span class="badge">共找到 {$templates['nodes']|@count} 个节点</span>
            <div class="home-title">当前已存在的节点  <button class="btn btn-default btn-md" onClick="javascript:node_create();"><i class="fa fa-plus" aria-hidden="true"></i> 添加节点</button></div>
        </div>
        <div class="home-body">
            <table class="table home-table">
                <thead>
                    <tr>
                        <th>节点ID</th>
                        <th>节点国家</th>
                        <th>UUID</th>
                        <th>节点名称</th>
                        <th>节点类型</th>
                        <th>节点IP/域名</th>
                        <th>节点端口</th>
                        <th>节点状态</th>
                        <th>创建时间</th>
                        <th>上次链接时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $templates['nodes'] as $key => $value}
                        <tr>
                        <td>{$value['id']}</td>
                        <td>{$value['value']->country}</td>
                        <td>{$value['value']->uuid}</td>
                        <td>{$value['value']->name}</td>
                        <td>{$value['type']}</td>
                        <td>{$value['value']->ip}</td>
                        <td>{$value['value']->port}</td>
                        <td>{$value['status']}</td>
                        <td>{if $value['value']->created_at eq '0000-00-00'}-{else}{$value['value']->created_at}{/if}</td>
                        <td>{if $value['value']->updated_at eq '0000-00-00'}-{else}{$value['value']->updated_at}{/if}</td>
                        <td>
                            <div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group">
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="node_info" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs"{if !$value['status']} disabled="disabled"{/if}>
                                        编辑节点信息
                                    </button>
                                </form>
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="node_config" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs"{if !$value['status']} disabled="disabled"{/if}>
                                        编辑节点配置文件
                                    </button>
                                </form>
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="node_advanced_config" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs"{if !$value['status']} disabled="disabled"{/if}>
                                        编辑节点高级配置文件
                                    </button>
                                </form>
                                <button class="btn btn-xs btn-primary autoset" onClick="javascript:node_status_change('{$value['id']}', '{$value['sign']}');">启用/禁用</button>
                                <button class="btn btn-xs btn-danger autohides" onClick="javascript:node_delete('{$value['id']}', '{$value['sign']}');">删除</button>
                            </div>
                            </td>
                        </tr>
                    {/foreach}                
                </tbody>
            </table>
        </div> 
        {/if}
        {if empty($templates['nodes'])}
            <div class="error-msg">
                <i class="fa fa-exclamation-triangle warning-img" aria-hidden="true"></i>
                <div class="error-text">您目前还未设置有效的节点</div>
                <a href="#">
                    <button class="btn btn-default btn-md" onClick="javascript:node_create();"><i class="fa fa-plus" aria-hidden="true"></i> 添加节点</button>
                </a>
            </div>
        {/if}
    {elseif $page['name'] eq 'node_info'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑节点信息</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>节点名称|节点所在国家|节点UUID|节点类型|IP/域名|端口</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="node_info">
                            <input type="hidden" name="action" value="node_info_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" name="info">{$templates['node']->name}|{$templates['node']->country}|{$templates['node']->uuid}|{$templates['node']->type}|{$templates['node']->ip}|{$templates['node']->port}</textarea>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('node_info').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {elseif $page['name'] eq 'node_config'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑节点配置文件</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>节点配置文件</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="node_config">
                            <input type="hidden" name="action" value="node_config_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" name="info">{$templates['node']->configoptiontable}</textarea>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('node_config').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {elseif $page['name'] eq 'node_advanced_config'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑节点高级配置文件</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>高级节点配置文件</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="node_advanced_config">
                            <input type="hidden" name="action" value="node_advanced_config_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" name="info">{$templates['node']->advancedconfigoptiontable}</textarea>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('node_advanced_config').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {/if}
    </div>
</div>