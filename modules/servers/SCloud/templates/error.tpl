<link rel="stylesheet" href="{$systemurl}modules/servers/SCloud/templates/assets/css/error.css">
<div id="SCloud">
    <div class="error-msg">
        <i class="fa fa-exclamation-triangle warning-img" aria-hidden="true"></i>
        <div class="error-text">{$info}</div>
        {if $page eq 'clientarea'}
        <a href="clientarea.php">
            <button class="btn btn-default btn-md"><i class="fa fa-angle-left" aria-hidden="true"></i> 返回客户中心</button>
        </a>
        {/if}
    </div>
</div>