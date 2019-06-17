<div role="tabpanel" class="tab-pane {if $active == "products"}active{/if}" id="products">
    <div class="row" id="plugin">
    {if $page['name'] eq 'home'}
        {if !empty($templates['products'])}
        <div class="col-xs-12 col-sm-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 当前页面能控制产品可使用的节点分组
            </div>
        </div>
        <div>
            <span class="badge">共找到 {$templates['products']|@count} 个</span>
            <div class="home-title">当前已存在的产品  <a href="configproducts.php?action=create"><button class="btn btn-default btn-md" ><i class="fa fa-plus" aria-hidden="true"></i> 添加产品</button></a></div>
        </div>
        <div class="home-body">
            <table class="table home-table">
                <thead>
                    <tr>
                        <th>产品ID</th>
                        <th>产品名称</th>
                        <th>产品可用分组</th>
                        <th>产品用户数量</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $templates['products'] as $key => $value}
                        <tr>
                        <td>{$value['id']}</td>
                        <td>{$value['value']->name}</td>
                        <td>{$value['value']->configoption4}</td>
                        <td>{$value['users']}</td>
                        <td>
                            <div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group">
                                <a href="configproducts.php?action=edit&id={$value['id']}"><button type="submit" class="btn btn-warning btn-xs">
                                    编辑产品信息
                                </button></a>
                                <form action="{$modulelink}" method="post" style="margin: 0;">
                                    <input name="action" value="product_groups" type="hidden">
                                    <input name="id" value="{$value['id']}" type="hidden">
                                    <input name="sign" value="{$value['sign']}" type="hidden">
                                    <button type="submit" class="btn btn-warning btn-xs">
                                        编辑产品分组
                                    </button>
                                </form>
                            </div>
                            </td>
                        </tr>
                    {/foreach}                
                </tbody>
            </table>
        </div> 
        {/if}
        {if empty($templates['products'])}
            <div class="error-msg">
                <i class="fa fa-exclamation-triangle warning-img" aria-hidden="true"></i>
                <div class="error-text">您目前还未设置有效的产品</div>
                <a href="configproducts.php?action=create">
                    <button class="btn btn-default btn-md"><i class="fa fa-plus" aria-hidden="true"></i> 添加产品</button>
                </a>
            </div>
        {/if}
    {elseif $page['name'] eq 'product_groups'}
        <div class="home-body">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑产品可用分组</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p>
                            格式: <code>选中可用分组</code>
                        </p>
                        <form action="{$modulelink}" method="post" id="product_groups">
                            <input type="hidden" name="action" value="product_groups_edit">
                            <input type="hidden" name="id" value="{$templates['id']}">
                            <input type="hidden" name="sign" value="{$templates['sign']}">
                            <div class="form-group">
                                {foreach $templates['groups'] as $key => $value}
                                    <label class="form-control"><input name="groups[]" type="checkbox" value="{$value['id']}" /{if $value['checked']}checked{/if}>    {$value['name']} (ID: {$value['id']})</label>
                                {/foreach}
                                </br>
                                <label class="form-control"><input type="checkbox" id="selectAll" />   全选</label>
                                <label class="form-control"><input type="checkbox" id="unSelectAll" />   全不选</label>
                                <script>
                                    $("#selectAll").click(function(){
                                        var items = document.getElementsByName("groups[]");
                                        for(var x = 0; x < items.length; x++){
                                            items[x].checked = "checked";
                                        }
                                        document.getElementById("unSelectAll").checked = "";
                                    })
                                    $("#unSelectAll").click(function(){
                                        var items = document.getElementsByName("groups[]");
                                        for(var x = 0; x < items.length; x++){
                                            items[x].checked = "";
                                        }
                                        document.getElementById("selectAll").checked = "";
                                    })
                                </script>
                            </div>
                        </form>
                    </div>
                    <button onclick="javascript:if(confirm('这将会覆盖原来数据库中的信息')) document.getElementById('product_groups').submit();" class="btn btn-warning"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> 提交修改</button>
                </div>
            </div>
        </div>
    {/if}
    </div>
</div>