@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        .m-bot15 {
            margin-bottom: 0;
        }

        .img-check {
            width: 375px;
            height: 150px;
            border: 1px solid #c09853;
        }

        .img-check:hover {
            cursor: pointer;
        }

        .text-color {
            color: red;
            font-size: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        编辑轮播图(<span class="text-color">图片规格 600 * 240</span>)
                    </header>

                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data"
                                  action="{{ route('admin.demo.banner.edit.action') }}">

                                {{ csrf_field() }}

                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">轮播标题</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="title" value="{{ $data->title }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">

                                    <label class="control-label col-lg-2">商品分类</label>
                                    <div class="col-lg-8">
                                        <div class="radio checkbox-inline">
                                            <label>
                                                <input type="radio" name="type" value="1"
                                                       @if($data->type == 1) checked @endif>
                                                商品
                                            </label>
                                        </div>
                                        <div class="radio checkbox-inline">
                                            <label>
                                                <input type="radio" name="type" value="2"
                                                       @if($data->type == 2) checked @endif>
                                                帖子
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">外链编号</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="ex_id" value="{{ $data->ex_id }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">轮播图片</label>
                                    <div class="col-lg-8">

                                        <img class="img-check"
                                             src="{{ $data->image ? getCover($data->image)->path
                                             : asset('/static/img/default-banner.jpg') }}">
                                        <input type="file" id="img-goods" class="form-control" name="image"
                                               style="display: none;">
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