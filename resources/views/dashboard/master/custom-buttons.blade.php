<div class="floated-colors-btn second-floated-btn">
    <div class="os-toggler-w">
        <div class="os-toggler-i">
            <div class="os-toggler-pill"></div>
        </div>
    </div>
    <span>الليلى </span><span>الوضع</span>
</div>
<div class="floated-customizer-btn third-floated-btn">
    <div class="icon-w">
        <i class="os-icon os-icon-ui-46"></i>
    </div>
    <span>تخصيص</span>
</div>
<div class="floated-customizer-panel">
    <div class="fcp-content">
        <div class="close-customizer-btn">
            <i class="os-icon os-icon-x"></i>
        </div>
        <div class="fcp-group">
            <div class="fcp-group-header">
                قائمة التخصيصات
            </div>
            <div class="fcp-group-contents">
                <div class="fcp-field">
                    <label for="">اتجاه القائمة</label>
                    <select class="menu-position-selector">
                        <option value="left">
                            يسار
                        </option>
                        <option value="right">
                            يمين
                        </option>
                        <option value="top">
                            أعلى
                        </option>
                    </select>
                </div>
                <div class="fcp-field" hidden>
                    <label for="">Menu Style</label><select class="menu-layout-selector">
                        <option value="compact">
                            Compact
                        </option>
                        <option value="full">
                            Full
                        </option>
                        <option value="mini">
                            Mini
                        </option>
                    </select>
                </div>
                <div hidden class="fcp-field with-image-selector-w">
                    <label for="">With Image</label>
                    <select class="with-image-selector">
                        <option value="yes">
                            Yes
                        </option>
                        <option value="no">
                            No
                        </option>
                    </select>
                </div>
                <div hidden class="fcp-field">
                    <label for="">Menu Color</label>
                    <div class="fcp-colors menu-color-selector">
                        <div class="color-selector menu-color-selector color-bright "></div>
                        <div class="color-selector menu-color-selector color-dark"></div>
                        <div class="color-selector menu-color-selector color-light"></div>
                        <div class="color-selector menu-color-selector color-transparent selected"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fcp-group" hidden>
            <div class="fcp-group-header">
                Sub Menu
            </div>
            <div class="fcp-group-contents">
                <div class="fcp-field">
                    <label for="">Sub Menu Style</label><select class="sub-menu-style-selector">
                        <option value="flyout">
                            Flyout
                        </option>
                        <option value="inside">
                            Inside/Click
                        </option>
                        <option value="over">
                            Over
                        </option>
                    </select>
                </div>
                <div class="fcp-field">
                    <label for="">Sub Menu Color</label>
                    <div class="fcp-colors">
                        <div class="color-selector sub-menu-color-selector color-bright selected"></div>
                        <div class="color-selector sub-menu-color-selector color-dark"></div>
                        <div class="color-selector sub-menu-color-selector color-light"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fcp-group" hidden>
            <div class="fcp-group-header">
                Other Settings
            </div>
            <div class="fcp-group-contents">
                <div class="fcp-field">
                    <label for="">Full Screen?</label><select class="full-screen-selector">
                        <option value="yes">
                            Yes
                        </option>
                        <option value="no">
                            No
                        </option>
                    </select>
                </div>
                <div class="fcp-field">
                    <label for="">Show Top Bar</label>
                    <select class="top-bar-visibility-selector">
                        <option value="yes">
                            Yes
                        </option>
                        <option value="no">
                            No
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($edit_fields))
    @if($type=='admin' || $type=='user')
        @if(!($row->id == Auth::user()->id && $type=='admin'))
            <div class="floated-chat-btn">
                <i class="os-icon os-icon-mail-07"></i><span>تواصل</span>
            </div>
            <div class="floated-chat-w">
                <div class="floated-chat-i">
                    <div class="chat-close">
                        <i class="os-icon os-icon-close"></i>
                    </div>
                    <div class="chat-head">
                        <div class="user-w with-status status-green">
                            <div class="user-avatar-w">
                                <div class="user-avatar">
                                    <img alt="" src="{{$row->image}}">
                                </div>
                            </div>
                            <div class="user-name">
                                <h6 class="user-title">
                                    {{$row->name}}
                                </h6>
                                <div class="user-role">
                                    {{$row->user_type->name}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-messages">
                        <div class="message self">
                            <div class="message-content">
                                السﻻم عليكم أخى
                            </div>
                        </div>
                    </div>
                    <div class="chat-controls">
                        <input class="message-input" placeholder="اكتب رسالتك هنا..." type="text">
                    </div>
                </div>
            </div>
        @endif
    @endif

@endif
