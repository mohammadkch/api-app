<?php
$this->extend('admin/_layout_/layout');
$this->section('content');
?>

    <div class="page-body">

        <!-- New Product Add Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>ایجاد محصول جدید</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">نام محصول</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text"
                                                       placeholder="عنوان محصول">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">نوع محصول</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option disabled>منو ثابت</option>
                                                    <option>ساده</option>
                                                    <option>طبقه بندی شده</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">دسته بندی</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option disabled>منو دسته ها</option>
                                                    <option>الکترونیک</option>
                                                    <option>تلویزیون و مانیتور</option>
                                                    <option>خانه و آشپزخانه</option>
                                                    <option>لوازم بهداشتی</option>
                                                    <option>گوشت و خوارکی</option>
                                                    <option>سلامتی دهان و دندان</option>
                                                    <option>دسته بندی نشده</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">زیرمجموعه</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option disabled>منو زیرمجموعه</option>
                                                    <option>لباس قومی</option>
                                                    <option>پایین قومی</option>
                                                    <option>لباس وسترن زنانه</option>
                                                    <option>صندل</option>
                                                    <option>کفش</option>
                                                    <option>زیبایی و آراستگی</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">برند محصول</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100">
                                                    <option disabled>منو برند</option>
                                                    <option value="puma">پوما</option>
                                                    <option value="hrx">اپل</option>
                                                    <option value="roadster">آدیداس</option>
                                                    <option value="zara">زراعت</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">واحد</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100">
                                                    <option disabled>منو واحد</option>
                                                    <option>کیلو گرم</option>
                                                    <option>قطعه</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">برچسب ها</label>
                                            <div class="col-sm-9">
                                                <div class="bs-example">
                                                    <input type="text" class="form-control"
                                                           placeholder="برچسب را تایپ کنید" id="#inputTag"
                                                           data-role="tagsinput">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4 row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">قابل ویرایش</label>
                                            <div class="col-sm-9">
                                                <label class="switch">
                                                    <input type="checkbox"><span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">بازگشت وجه</label>
                                            <div class="col-sm-9">
                                                <label class="switch">
                                                    <input type="checkbox" checked=""><span
                                                            class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>توضیحات</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <label class="form-label-title col-sm-3 mb-0">توضیحات محصول</label>
                                                    <div class="col-sm-9">
                                                        <div id="editor"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>تصویر محصول</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label
                                                    class="col-sm-3 col-form-label form-label-title">تصاویر</label>
                                            <div class="col-sm-9">
                                                <input class="form-control form-choose" type="file" id="formFile"
                                                       multiple>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">تصویر شاخص</label>
                                            <div class="col-sm-9">
                                                <input class="form-control form-choose" type="file"
                                                       id="formFileMultiple1" multiple>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>ویدئو محصول</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">مرجع ویدئو</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option>آپارت</option>
                                                    <option>یوتیوب</option>
                                                    <option>فیلیمو</option>
                                                    <option>ویمو</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">آدرس ویدئو</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text"
                                                       placeholder="لینک را وارد کنید">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>ویژگی محصول</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">نوع ویژگی</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option>رنگ</option>
                                                    <option>سایز</option>
                                                    <option>جنس</option>
                                                    <option>استایل</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">مقدار</label>
                                            <div class="col-sm-9">
                                                <div class="bs-example">
                                                    <input type="text" class="form-control"
                                                           placeholder="تایپ کنید سپس اینتر را بزنید" id="#inputTag"
                                                           data-role="tagsinput">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <a href="#" class="add-option"><i class="ri-add-line me-2"></i> افزودن ویژگی
                                        جدید</a>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>تنظیمات ارسال</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">وزن
                                                (kg)</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" placeholder="وزن محصول">
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">ابعاد
                                                (cm)</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option>طول</option>
                                                    <option>عرض</option>
                                                    <option>ارتفاع</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>قیمت محصول</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 form-label-title">قیمت</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 form-label-title">قیمت با تخفیف</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 form-label-title">تعداد</label>
                                            <div class="col-sm-5">
                                                <input class="form-control" type="number" placeholder="0">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>تخفیف :</label>
                                                <span>25%</span>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>سود :</label>
                                                <span>5 تومان</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>موجودی انبار</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">کد بارکد محصول</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">وضعیت انبار</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100" name="state">
                                                    <option>موجود</option>
                                                    <option>در حال اتمام</option>
                                                    <option>عدم موجودی</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table variation-table table-responsive-sm">
                                        <thead>
                                        <tr>
                                            <th scope="col">نوع</th>
                                            <th scope="col">قیمت</th>
                                            <th scope="col">کد بارکد</th>
                                            <th scope="col">تعداد</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>قرمز</td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <ul class="order-option">
                                                    <li><a href="javascript:void(0)" data-toggle="modal"
                                                           data-target="#deleteModal"><i
                                                                    class="ri-delete-bin-line"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>آبی</td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" placeholder="0">
                                            </td>
                                            <td>
                                                <ul class="order-option">
                                                    <li><a href="javascript:void(0)" data-toggle="modal"
                                                           data-target="#deleteModal"><i
                                                                    class="ri-delete-bin-line"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>لینک محصولات</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">بیش فروش</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="search">
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">فروش مکمل</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="search">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>سئو و موتور جستجو</h5>
                                    </div>

                                    <div class="seo-view">
                                        <span class="link">https://farscod.ir</span>
                                        <h5>فلش 64 گیگ sandisk با گارانتی 18 ماهه متین</h5>
                                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                            طراحان گرافیک است. </p>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">عنوان صفحه</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="search"
                                                       placeholder="فلش 64 گیگ sandisk با گارانتی 18 ماهه متین">
                                            </div>
                                        </div>

                                        <div class="mb-4 row">
                                            <label class="form-label-title col-sm-3 mb-0">توضیحات متا</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="form-label-title col-sm-3 mb-0">لینک شما</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="search"
                                                       placeholder="https://farscod.ir/">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New Product Add End -->

        <!-- footer Start -->
        <div class="container-fluid">
            <footer class="footer">
                <div class="row">
                    <div class="col-md-12 footer-copyright text-center">
                        <p class="mb-0">راست چین شده توسط : امین احمدی</p>
                    </div>
                </div>
            </footer>
        </div>
        <!-- footer En -->
    </div>


<?php
$this->endSection();
?>