<div role="tabpanel" class="tab-pane" id="about">
    <div class="developer-logo">
        QwQ
    </div>
    <div class="about-body">
        <div class="about-item">
            <span class="about-title">当前版本: </span>
            <div class="input-group">
                <input class="form-control" value="{$modulevars['version']}" type="text" readonly="true">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="checkButton">
                        <span>检查更新</span>
                    </button>
                </span>
            </div>
        </div>
        <div class="about-item">
            <div class="noUpdate" id="noUpdate"><i class="fa fa-check-circle" aria-hidden="true"></i><span class="noUpdateContents"> </span></div>
            <div class="newUpdate" id="newUpdate"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span class="newUpdateContents"> </span></div>
            <div class="errorUpdate" id="errorUpdate"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="errorUpdateContents"> </span></div>
        </div>
    </div>
</div>