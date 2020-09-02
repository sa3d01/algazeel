<ul class="main-menu">
    <li class="sub-header">
        <span>اعدادات عامة</span>
    </li>
    <li class=" has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-paperclip"></div>
            </div>
            <span>الصفحات التعريفية</span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                الصفحات التعريفية
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-paperclip"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.page.index')}}">قائمة البيانات</a>
                    </li>
                    <li>
                        <a href="{{route('admin.page.create')}}">اضافة </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
    <li class=" has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-settings"></div>
            </div>
            <span>الاعدادات العامة</span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                الاعدادات العامة
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-settings"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.setting')}}">عرض </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
    <li class="sub-header">
        <span>المستخدمين</span>
    </li>
    <li class="has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-users"></div>
            </div>
            <span>الأعضاء</span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                الأعضاء
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-users"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.user.index')}}">قائمة البيانات</a>
                    </li>
                    <li>
                        <a href="{{route('admin.user.create')}}">إضافة جديد</a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
</ul>
