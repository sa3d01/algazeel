<ul class="main-menu">
    <li class="sub-header">
        <span>المستخدمين</span>
    </li>
    <li class="has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-users"></div>
            </div>
            <span>المستخدمين</span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                المستخدمين
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-users"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.user.index')}}"> المستخدمين</a>
                    </li>
                    <li>
                        <a href="{{route('admin.user.create')}}"> إضافة مستخدم</a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
{{--    //providers--}}
    <li class="sub-header">
        <span>مزودى الخدمات</span>
    </li>
    <li class=" has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-agenda-1"></div>
            </div>
            <span>مزودى الخدمات</span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                مزودى الخدمات
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-agenda-1"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.provider.index')}}"> مزودى الخدمات </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
{{--    //orders--}}
    <li class="sub-header">
        <span>الطلبات </span>
    </li>
    <li class=" has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-shopping-cart"></div>
            </div>
            <span>الطلبات </span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                الطلبات
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-shopping-cart"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.order.status',['status'=>'new'])}}"> الطلبات الجديدة  </a>
                        <a href="{{route('admin.order.status',['status'=>'in_progress'])}}"> الطلبات الجارية  </a>
                        <a href="{{route('admin.order.status',['status'=>'done'])}}"> الطلبات المنتهية  </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
{{--//notifications--}}
    <li class="sub-header">
        <span>الاشعارات الجماعية </span>
    </li>
    <li class=" has-sub-menu">
        <a href="#">
            <div class="icon-w">
                <div class="os-icon os-icon-alert-octagon"></div>
            </div>
            <span>الاشعارات الجماعية </span></a>
        <div class="sub-menu-w">
            <div class="sub-menu-header">
                الاشعارات الجماعية
            </div>
            <div class="sub-menu-icon">
                <i class="os-icon os-icon-alert-octagon"></i>
            </div>
            <div class="sub-menu-i">
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('admin.notification.admin_notify_type',['admin_notify_type'=>'user'])}}"> اشعارات المستخدمين </a>
                        <a href="{{route('admin.notification.admin_notify_type',['admin_notify_type'=>'provider'])}}"> اشعارات مقدمى الخدمات  </a>
                        <a href="{{route('admin.notification.admin_notify_type',['admin_notify_type'=>'all'])}}"> الاشعارات العامة  </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>

{{--        <li class="sub-header">--}}
{{--            <span>الأقسام</span>--}}
{{--        </li>--}}
{{--        <li class="has-sub-menu">--}}
{{--            <a href="#">--}}
{{--                <div class="icon-w">--}}
{{--                    <div class="os-icon os-icon-calendar-time"></div>--}}
{{--                </div>--}}
{{--                <span>الأقسام</span>--}}
{{--            </a>--}}
{{--            <div class="sub-menu-w">--}}
{{--                <div class="sub-menu-header">--}}
{{--                    الأقسام--}}
{{--                </div>--}}
{{--                <div class="sub-menu-icon">--}}
{{--                    <i class="os-icon os-icon-calendar-time"></i>--}}
{{--                </div>--}}
{{--                <div class="sub-menu-i">--}}
{{--                    <ul class="sub-menu">--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.category.index')}}">الأقسام</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.category.create')}}">إضافة جديد</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--        <li class="sub-header">--}}
{{--            <span>خصائص السيارات</span>--}}
{{--        </li>--}}
{{--        <li class="has-sub-menu">--}}
{{--            <a href="#">--}}
{{--                <div class="icon-w">--}}
{{--                    <div class="os-icon os-icon-trending-up"></div>--}}
{{--                </div>--}}
{{--                <span>خصائص السيارات</span>--}}
{{--            </a>--}}
{{--            <div class="sub-menu-w">--}}
{{--                <div class="sub-menu-header">--}}
{{--                    خصائص السيارات--}}
{{--                </div>--}}
{{--                <div class="sub-menu-icon">--}}
{{--                    <i class="os-icon os-icon-trending-up"></i>--}}
{{--                </div>--}}
{{--                <div class="sub-menu-i">--}}
{{--                    <ul class="sub-menu">--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.mark.index')}}">الماركات</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.model.index')}}">الموديﻻت</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.color.index')}}">الألوان</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.mark.create')}}">إضافة ماركة</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.model.create')}}">إضافة موديل</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.color.create')}}">إضافة لون</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--        <li class="sub-header">--}}
{{--            <span>المزادات</span>--}}
{{--        </li>--}}
{{--        <li class="has-sub-menu">--}}
{{--            <a href="#">--}}
{{--                <div class="icon-w">--}}
{{--                    <div class="os-icon os-icon-wallet-loaded"></div>--}}
{{--                </div>--}}
{{--                <span>المزادات</span>--}}
{{--            </a>--}}
{{--            <div class="sub-menu-w">--}}
{{--                <div class="sub-menu-header">--}}
{{--                    المزادات--}}
{{--                </div>--}}
{{--                <div class="sub-menu-icon">--}}
{{--                    <i class="os-icon os-icon-wallet-loaded"></i>--}}
{{--                </div>--}}
{{--                <div class="sub-menu-i">--}}
{{--                    <ul class="sub-menu">--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.sale.status',['status'=>'active'])}}">المزادات الجارية</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.sale.status',['status'=>'near'])}}">المزادات القريبة</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.sale.status',['status'=>'closed'])}}">المزادات المغلقة</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.sale.create')}}">إضافة جديد</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--        <li class="sub-header">--}}
{{--            <span>رسائل الأعضاء</span>--}}
{{--        </li>--}}
{{--        <li class=" has-sub-menu">--}}
{{--            <a href="{{route('admin.contact.index')}}">--}}
{{--                <div class="icon-w">--}}
{{--                    <div class="os-icon os-icon-email-2-at"></div>--}}
{{--                </div>--}}
{{--                <span>رسائل الأعضاء</span>--}}
{{--            </a>--}}
{{--        </li>--}}
</ul>
