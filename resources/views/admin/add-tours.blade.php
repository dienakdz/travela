@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Thêm Tours</h3>
                    </div>

                    <div class="title_right">
                        <div class="col-md-5 col-sm-5  form-group row pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button">Go!</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">

                    <div class="col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Form</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content add-tours">

                                <!-- Smart Wizard -->
                                <p>Thêm thông tin chi tiết để tạo một tour mới và bắt đầu thu hút khách hàng!</p>
                                <div id="wizard" class="form_wizard wizard_horizontal">
                                    <ul class="wizard_steps">
                                        <li>
                                            <a href="#step-1">
                                                <span class="step_no">1</span>
                                                <span class="step_descr">
                                                    Bước 1<br />
                                                    <small>Bước 1 Nhập thông tin </small>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#step-2">
                                                <span class="step_no">2</span>
                                                <span class="step_descr">
                                                    Bước 2<br />
                                                    <small>Bước 2 Thêm hình ảnh</small>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#step-3">
                                                <span class="step_no">3</span>
                                                <span class="step_descr">
                                                    Bước 3<br />
                                                    <small>Bước 3 Lộ trình</small>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div id="step-1">
                                        <form class="form-info-tour" action="{{ route('admin.add-tours') }}"
                                            method="POST" id="form-step1">
                                            @csrf
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Tên
                                                    <span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="name"
                                                        placeholder="Nhập tên Tour" required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Điểm đến
                                                    <span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="destination"
                                                        placeholder="Điểm đến" required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Khu
                                                    vực<span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <select class="form-control" name="domain" id="domain">
                                                        <option value="">Chọn khu vực</option>
                                                        <option value="b">Miền Bắc</option>
                                                        <option value="t">Miền Trung</option>
                                                        <option value="n">Miền Nam</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Số lượng
                                                    <span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" type="number" name="number"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Giá người
                                                    lớn
                                                    <span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" type="number" name="price_adult"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Giá trẻ em
                                                    <span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" type="number" name="price_child"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày khởi
                                                    hành<span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" class="form-control datetimepicker"
                                                        id="start_date" name="start_date" required>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày kết
                                                    thúc<span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" class="form-control datetimepicker"
                                                        id="end_date" name="end_date" required>
                                                </div>
                                            </div>

                                            <div class="field item form-group bad">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Mô
                                                    tả<span>*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <textarea name="description" id="description" rows="10" required></textarea>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <div id="step-2">
                                        <h2 class="StepTitle">Thêm hình ảnh</h2>
                                        <form action="{{ route('admin.add-images-tours') }}"
                                            class="dropzone dz-clickable" id="myDropzone"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="dz-default dz-message">
                                                <span>Chọn hình ảnh về tours để upload</span>
                                            </div>
                                        </form>
                                    </div>
                                    <form action="{{ route('admin.add-timeline') }}" id="timeline-form" method="POST">
                                        @csrf
                                        <input type="hidden" name="tourId" class="hiddenTourId">
                                        <div id="step-3">
                                            <h2 class="StepTitle">Nhập lộ trình</h2>

                                        </div>

                                    </form>

                                </div>
                                <!-- End SmartWizard Content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>
@include('admin.blocks.footer')
