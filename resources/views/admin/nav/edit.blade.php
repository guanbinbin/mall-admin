@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        .m-bot15 {
            margin-bottom: 0;
        }

        .img-check {
            border: 1px solid #c09853;
            margin: 2px;
        }

        .img-check:hover {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        编辑导航标题
                    </header>

                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data"
                                  action="{{ route('admin.demo.nav.edit.action') }}">

                                {{ csrf_field() }}

                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">导航标题</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="title" value="{{ $data->title }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">导航商品</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="goods" value="{{ $data->goods }}"
                                               placeholder="填写商品编号,用逗号分割">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-2">
                                        <button type="reset" class="btn btn-warning">重&nbsp;置</button>
                                    </div>

                                    <div class="col-lg-offset-4 col-lg-2">
                                        <button type="submit" class="btn btn-primary">提&nbsp;交</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </section>

                </section>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
    <script>

        $(function () {

            $('.img-check').on('click', function () {
                $(this).next().click();
            });

            $("[type='file']").on("change", function () {
                var self = $(this);
                var objUrl = getObjectURL(this.files[0]); //获取图片的路径，该路径不是图片在本地的路径
                if (objUrl) {
                    self.prev().attr("src", objUrl); //将图片路径存入src中，显示出图片
                }
            });

            $("[type='reset']").on('click', function () {
                $('.img-check').attr('src', "{{ asset('/static/img/default-banner.jpg') }}");
            });
        });

        function getObjectURL(file) {
            var url = null;
            if (window.createObjectURL != undefined) { // basic
                url = window.createObjectURL(file);
            } else if (window.URL != undefined) { // mozilla(firefox)
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL != undefined) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        }

    </script>
@endsection