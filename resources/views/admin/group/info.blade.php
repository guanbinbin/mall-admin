<html>
<head>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/static/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/css/bootstrap-reset.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/static/tree/css/style.css') }}"/>
    <script type="text/javascript" src="{{ asset('/static/tree/js/jquery-1.9.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/static/tree/js/action.js') }}"></script>
    <script src="{{ asset('/static/layer/layer.js') }}"></script>
    <style type="text/css">
        .control-label {
            margin-left: 10%;
        }
    </style>
    <script>
        function success_message(message) {
            layer.open({
                title: message,
                type: 1,
                offset: 'rt', //具体配置参考：offset参数项
                shade: 0, //不显示遮罩
                time: 3000,
                skin: 'layer-ext-success'
            });
        }

        function error_message(message) {
            layer.open({
                title: message,
                type: 1,
                offset: 'rt', //具体配置参考：offset参数项
                shade: 0, //不显示遮罩
                time: 3000,
                skin: 'layer-ext-red'
            });
        }
    </script>
</head>
<body>
<div style="width: 600px;height: 600px;">
    <div class="col-lg-12">
        <section class="panel">
            <div class="panel-body">
                <form class="form-horizontal" role="form">

                    <div class="form-group">
                        <ul class="tree">

                        @foreach($allRule as $item)
                            <!-- 只有一级的菜单 -->
                                @if(!isset($item['son']))
                                    <li>
                                        <a href="javascript:;">
                                            <label id="one04">
                                                <input name="rule" type="checkbox" value="{{ $item['id'] }}"
                                                       data-parent-id="0"
                                                       @if(in_array($item['id'],$roleTorule)) checked @endif>
                                                <i></i>
                                                <p>{{ $item['name'] }}</p>
                                            </label>
                                        </a>
                                    </li>
                                @else

                                <!-- 有两级的菜单 -->
                                    <li>
                                        <a href="javascript:;">
                                            <label id="one01">
                                                <input name="rule" type="checkbox" value="{{ $item['id'] }}"
                                                       data-parent-id="0" class="rule"
                                                       @if(in_array($item['id'],$roleTorule)) checked @endif>
                                                <i></i>
                                                <p>{{ $item['name'] }}</p>
                                            </label>
                                        </a>

                                        <ul>
                                            @foreach($item['son'] as $i)
                                                <li>
                                                    <a href="javascript:;">
                                                        <label id="two02">
                                                            <input name="rule" type="checkbox" value="{{ $i['id'] }}"
                                                                   data-parent-id="{{$item['id']}}"
                                                                   @if(in_array($i['id'],$roleTorule)) checked @endif>
                                                            <i></i>
                                                            <p>{{ $i['name'] }}</p>
                                                        </label>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-8 pull-right">
                            <button type="button" class="btn btn-success" id="save-rule">确&nbsp;定</button>
                        </div>
                    </div>

                    <input type="hidden" name="" id="checkGroup" value="1,2,3,4"/>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
<script>
    $(function () {

        $(".rule").each(function (key, val) {

            var soso = $(val);
            var self = $(val).parent().parent().parent().find('ul');
            self.each(function (sKey, sVal) {
                var lengthAll = $(sVal).find("input[name='rule']").length;
                var length = $(sVal).find("input[name='rule']:checkbox:checked").length;
                if (lengthAll > length) {
                    soso.addClass('soso');
                }
            });
        });


        $('#save-rule').on('click', function () {
            var rule = new Array();
            $("input[name='rule']:checkbox:checked").each(function (key, val) {
                rule[key] = $(val).val();
            });

            var url = "{{ route('admin.rights.group.rule.edit') }}";
            var data = {id: "{{ $id }}", rule: rule, _token: "{{ csrf_token() }}"};

            $.post(url, data, function (e) {
                if (!e.status) {
                    layer.alert(e.message, {
                        icon: 2,
                        skin: 'layui-layer-molv',
                        closeBtn: 0
                    });
                    return false;
                } else {
                    layer.alert(e.message,'success');
                }
            }, 'json');
        });
    });
</script>
</html>