@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        .m-bot15 {
            margin-bottom: 0;
        }

        .img-check {
            width: 150px;
            border: 1px solid #c09853;
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
                        编辑商品
                    </header>

                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data"
                                  action="{{ route('admin.goods.base.edit.goods') }}">

                                {{ csrf_field() }}

                                <input name="id" value="{{ $data->id }}" type="hidden">

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">商品名称</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">

                                    <label class="control-label col-lg-2">商品分类</label>
                                    <div class="col-lg-8">
                                        <select class="form-control m-bot15" name="class_id">
                                            @foreach($class as $item)
                                                <option value="{{ $item->id }}"
                                                        @if($data->class_id == $item->id)selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">商品价格</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="price" value="{{ $data->price }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">商品主图</label>
                                    <div class="col-lg-8">
                                        <img class="img-check" src="{{ getCover($data->image)->path }}">
                                        <input type="file" id="img-goods" class="form-control" name="image"
                                               style="display: none;">
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">商品详情</label>
                                    <div class="col-lg-8">
                                    <textarea type="text" class="form-control" rows="5"
                                              name="detail">{{ $data->detail }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">商品库存</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="stock" value="{{ $data->stock }}">
                                    </div>
                                </div>

                                <div class="form-group has-warning">
                                    <label class="control-label col-lg-2">更多图片(375*320)</label>
                                    <div class="col-lg-10">
                                        <img class="img-check"
                                             src="{{ isset($image[0]) ? getCover($image[0]['picture_id'])->path : asset('/static/img/favicon.png') }}">
                                        <input type="file" class="form-control" name="imageDeail[]"
                                               style="display: none;">

                                        <img class="img-check"
                                             src="{{ isset($image[1]) ? getCover($image[1]['picture_id'])->path : asset('/static/img/favicon.png') }}">
                                        <input type="file" class="form-control" name="imageDeail[]"
                                               style="display: none;">

                                        <img class="img-check"
                                             src="{{ isset($image[2]) ? getCover($image[2]['picture_id'])->path : asset('/static/img/favicon.png') }}">
                                        <input type="file" class="form-control" name="imageDeail[]"
                                               style="display: none;">

                                        <img class="img-check"
                                             src="{{ isset($image[3]) ? getCover($image[3]['picture_id'])->path : asset('/static/img/favicon.png') }}">
                                        <input type="file" class="form-control" name="imageDeail[]"
                                               style="display: none;">

                                        <img class="img-check"
                                             src="{{ isset($image[4]) ? getCover($image[4]['picture_id'])->path : asset('/static/img/favicon.png') }}">
                                        <input type="file" class="form-control" name="imageDeail[]"
                                               style="display: none;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
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