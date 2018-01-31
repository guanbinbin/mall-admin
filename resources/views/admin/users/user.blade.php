@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
    </style>
@endsection

@section('content')
    <section class="wrapper wrapper-margin-padding">
        <!-- page start-->
        <div class="row">
            <aside class="profile-nav col-lg-3">
                <section class="panel">
                    <div class="user-heading round">
                        <a href="#">
                            <img src="{{ getCover($data->avatar)->path }}">
                        </a>
                        <h1>{{ $data->name }}</h1>
                        <p>{{ $data->email }}</p>
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="javascript:void(0);" class="base">
                                <i class="icon-user"></i> 基本信息
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="role">
                                <i class="icon-calendar"></i> 角色设置
                            </a>
                        </li>
                    </ul>

                </section>
            </aside>
            <aside class="profile-info col-lg-9 div-all">
                <section class="panel div-base">
                    <div class="panel-body bio-graph-info">
                        <h1>基本信息</h1>
                        <div class="row">
                            <div class="bio-row">
                                <p><span>昵称 </span>: {{ $data->name }}</p>
                            </div>
                            <div class="bio-row">
                                <p><span>邮箱 </span>: {{ $data->email }}</p>
                            </div>
                            <div class="bio-row">
                                <p><span>电话 </span>: {{ $data->phone }}</p>
                            </div>
                            <div class="bio-row">
                                <p><span>Birthday</span>: 13 July 1983</p>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="panel div-role" style="display: none;">
                    <div class="panel-body bio-graph-info">
                        <h1>所属角色</h1>
                        <div class="row">
                            <div class="panel-body role-panel">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        @foreach($role as $item)
                                            <label class="checkbox-inline">
                                                <input name="role" type="checkbox" value="{{ $item->id }}"
                                                       @if(in_array($item->id,$UserToRole))checked @endif>
                                                {{ $item->name }}
                                            </label>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-success" id="rule-save-btn">更&nbsp;新
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        <!-- page end-->
    </section>
@endsection

@section('js')
    @parent
    <script src="{{ asset('/static/assets/jquery-knob/js/jquery.knob.js') }}"></script>
    <script>
        //knob
        $(".knob").knob();

        $(function () {
            $('.nav-stacked a').on('click', function () {
                var self = $(this);
                self.parent().parent().find('li').removeClass('active');

                var aClass = self.attr('class');
                var div = "div-" + aClass;

                var all = $('.div-all').find('section').hide('slow');

                $("." + div).show('slow');
            });

            $('#rule-save-btn').on('click', function () {

                var rule = new Array();

                $("input[name='role']:checkbox:checked").each(function (key, val) {
                    rule[key] = $(val).val();
                });

                var url = "{{ route('admin.rights.users.rule.edit') }}";
                var data = {id: "{{ $id }}", rule: rule, _token: "{{ csrf_token() }}"};
                $.post(url, data, function (e) {
                    if (!e.status) {
                        layer.alert(e.message, {
                            icon: 2,
                            skin: 'layui-layer-molv'
                        });
                        return false;
                    } else {
                        success_message(e.message);
                    }
                }, 'json');
            });
        });
    </script>
@endsection