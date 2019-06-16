<link rel="stylesheet" href="{$systemurl}modules/addons/{$modulename}/library/templates/assets/css/home.css?v={$versionHash}">
<div id="moduleactioninfo">
    <div class="error-msg">
        <i class="fa fa-check warning-img" aria-hidden="true"></i>
        <div class="error-text">{$text}</div>
        3秒后自动跳转主页
        <script>
            function delayURL() { 
                window.location.href = "{$modulelink}";
            }
            setTimeout("delayURL()", 3000);
        </script>
    </div>
</div>