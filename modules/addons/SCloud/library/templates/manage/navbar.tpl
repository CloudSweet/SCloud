<nav class="navbar navbar-plugin">
    <div class="modules-logo">
        <div class="addons-name">{$modulename}
         Manager</div>
        <div class="version-text">当前版本: </div>
        <div class="addons-version">{$modulevars['version']}</div>
        <div class="label label-success" id="newVersionTip">新版已发布</div>
    </div>
    <div class="modules-pages" role="tablist">
        <ul role="tablist" class="nav-list">
            <li role="presentation" class="hidden-xs {if $active == "home"}active{/if}"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">设置节点</a></li>
            <li role="presentation" class="hidden-xs {if $active == "groups"}active{/if}"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">节点分配</a></li>
            <li role="presentation" class="hidden-xs {if $active == "products"}active{/if}"><a href="#products" aria-controls="products" role="tab" data-toggle="tab">产品分配</a></li>
            <li role="presentation" class="hidden-xs"><a href="#transfer" aria-controls="transfer" role="tab" data-toggle="tab">转发管理</a></li>
            <li role="presentation" class="hidden-xs"><a href="#record" aria-controls="record" role="tab" data-toggle="tab">转发记录</a></li>
            <li role="presentation" class="hidden-xs"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">操作记录</a></li>
            <li role="presentation" class="hidden-xs"><a href="#traffic" aria-controls="traffic" role="tab" data-toggle="tab">流量记录</a></li>
            <li role="presentation" class="hidden-xs"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">授权信息</a></li>
            <li role="presentation" class="tab-right hidden-sm hidden-md hidden-lg dropdown"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" id="collapseButton"><i class="fa fa-bars" aria-hidden="true"></i></a></li>
        </ul>
    </div>
</nav>
<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
    <ul role="tablist" class="nav-list-sm">
        <li role="presentation" {if $active == "home"}class="active"{/if}><a href="#home" aria-controls="home" role="tab" data-toggle="tab">设置节点</a></li>
        <li role="presentation" {if $active == "groups"}class="active"{/if}><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">节点分配</a></li>
        <li role="presentation" {if $active == "products"}class="active"{/if}><a href="#products" aria-controls="products" role="tab" data-toggle="tab">产品分配</a></li>
        <li role="presentation"><a href="#transfer" aria-controls="transfer" role="tab" data-toggle="tab">转发管理</a></li>
        <li role="presentation"><a href="#record" aria-controls="record" role="tab" data-toggle="tab">转发记录</a></li>
        <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">操作记录</a></li>
        <li role="presentation"><a href="#traffic" aria-controls="traffic" role="tab" data-toggle="tab">流量记录</a></li>
        <li role="presentation"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">授权信息</a></li>
    </ul>
</div>