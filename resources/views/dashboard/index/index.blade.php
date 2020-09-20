@extends('dashboard.master.base')
@section('title',$title)
@section('style')
    <style>
        .image-upload > input {
            visibility:hidden;
            width:0;
            height:0
        }
    </style>
@endsection
@section('content')
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <h5 class="form-header">
                        {{$title}}
                    </h5>
                    <div  class="table-responsive">
                        <table id="datatable" width="100%" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th hidden></th>
                                    @foreach($index_fields as $key=>$value)
                                        <th>{{$key}}</th>
                                    @endforeach
                                    @if(isset($status))
                                        <th>الحالة</th>
                                    @endif
                                    @if(isset($image))
                                        <th>الصورة</th>
                                    @endif
                                    <th>المزيد</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th hidden></th>
                                    @foreach($index_fields as $key=>$value)
                                    <th>{{$key}}</th>
                                    @endforeach
                                    @if(isset($status))
                                        <th>الحالة</th>
                                    @endif
                                    @if(isset($image))
                                        <th>الصورة</th>
                                    @endif
                                    <th>المزيد</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td hidden>{{$row->id}}</td>
                                @foreach($index_fields as $key=>$value)
                                    @if($value=='created_at')
                                        <td>{{$row->published_at()}}</td>
                                    @else
                                        <td>{{$row->$value}}</td>
                                    @endif
                                @endforeach
                                    @if(isset($status))
                                        <td>
                                        {!!$row->getStatusIcon()!!}
                                        </td>
                                    @endif
                                    @if(isset($image))
                                        <td><img width="50px" height="50px" src="{{$row->image}}"></td>
                                    @endif
                                    <td>
                                        <a href="{{route('admin.'.$type.'.show',$row->id)}}"><i class="os-icon os-icon-grid-10"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            var Table = $('#datatable').DataTable({
                "order": [[ 1, "asc" ]],
                "oLanguage": {
                    "sEmptyTable":     "ليست هناك بيانات متاحة في الجدول",
                    "sLoadingRecords": "جارٍ التحميل...",
                    "sProcessing":   "جارٍ التحميل...",
                    "sLengthMenu":   "أظهر _MENU_ مدخلات",
                    "sZeroRecords":  "لم يعثر على أية سجلات",
                    "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty":    "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sInfoPostFix":  "",
                    "sSearch":       "ابحث:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst":    "الأول",
                        "sPrevious": "السابق",
                        "sNext":     "التالي",
                        "sLast":     "الأخير"
                    },
                    "oAria": {
                        "sSortAscending":  ": تفعيل لترتيب العمود تصاعدياً",
                        "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                    }
                },
                // "iDisplayLength": -1,
                // "sPaginationType": "full_numbers",
            });
//length of rows
            var rows=Table.rows().data();
            $(
                ".filters-groups .date-picker-max, .filters-groups .date-picker-min"
            ).change(function() {
                var val = parseInt((new Date(this.value).getTime() / 1000).toFixed(0));
                var op = "min";
                if ($(this).hasClass("date-picker-max")) {
                    op = "max";
                }
                Table.rows().every(function() {
                    var row_id=this.data()[0];
                    var date = Date.parse(this.data()[1])/1000;
                    if (date) {
                        if (op === "min") {
                            if (date < val) {
                                $('#'+row_id).hide();
                            } else {
                                $('#'+row_id).show();
                            }
                        } else {
                            if (date > val) {
                                $('#'+row_id).hide();
                            } else {
                                $('#'+row_id).show();
                            }
                        }
                    }
                });
                Table.draw();
            });
        });
    </script>

@endsection

