function nl2br (str, replaceMode, isXhtml) {

    var breakTag = (isXhtml) ? '<br />' : '<br>';
    var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
}

var ChuSo =new Array(" không"," một"," hai"," ba"," bốn"," năm"," sáu"," bảy"," tám"," chín");
var Tien =new Array( "", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ");

function DocSo3ChuSo(baso)
{
    var tram;
    var chuc;
    var donvi;
    var KetQua="";
    tram=parseInt(baso/100);
    chuc=parseInt((baso%100)/10);
    donvi=baso%10;
    if(tram==0 && chuc==0 && donvi==0) return "";
    if(tram!=0)
    {
        KetQua += ChuSo[tram] + " trăm";
        if ((chuc == 0) && (donvi != 0)) KetQua += " linh";
    }
    if ((chuc != 0) && (chuc != 1))
    {
        KetQua += ChuSo[chuc] + " mươi";
        if ((chuc == 0) && (donvi != 0)) KetQua = KetQua + " linh";
    }
    if (chuc == 1) KetQua += " mười";
    switch (donvi)
    {
        case 1:
            if ((chuc != 0) && (chuc != 1))
            {
                KetQua += " mốt ";
            }
            else
            {
                KetQua += ChuSo[donvi];
            }
            break;
        case 5:
            if (chuc == 0)
            {
                KetQua += ChuSo[donvi];
            }
            else
            {
                KetQua += " lăm ";
            }
            break;
        default:
            if (donvi != 0)
            {
                KetQua += ChuSo[donvi];
            }
            break;
    }
    return KetQua;
}

function DocTienBangChu(SoTien)
{
    var lan=0;
    var i=0;
    var so=0;
    var KetQua="";
    var tmp="";
    var ViTri = new Array();
    if(SoTien<0) return "Số tiền âm !";
    if(SoTien==0) return "Không đồng !";
    if(SoTien>0)
    {
        so=SoTien;
    }
    else
    {
        so = -SoTien;
    }
    if (SoTien > 8999999999999999)
    {
        //SoTien = 0;
        return "Số quá lớn!";
    }
    ViTri[5] = Math.floor(so / 1000000000000000);
    if(isNaN(ViTri[5]))
        ViTri[5] = "0";
    so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
    ViTri[4] = Math.floor(so / 1000000000000);
    if(isNaN(ViTri[4]))
        ViTri[4] = "0";
    so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
    ViTri[3] = Math.floor(so / 1000000000);
    if(isNaN(ViTri[3]))
        ViTri[3] = "0";
    so = so - parseFloat(ViTri[3].toString()) * 1000000000;
    ViTri[2] = parseInt(so / 1000000);
    if(isNaN(ViTri[2]))
        ViTri[2] = "0";
    ViTri[1] = parseInt((so % 1000000) / 1000);
    if(isNaN(ViTri[1]))
        ViTri[1] = "0";
    ViTri[0] = parseInt(so % 1000);
    if(isNaN(ViTri[0]))
        ViTri[0] = "0";
    if (ViTri[5] > 0)
    {
        lan = 5;
    }
    else if (ViTri[4] > 0)
    {
        lan = 4;
    }
    else if (ViTri[3] > 0)
    {
        lan = 3;
    }
    else if (ViTri[2] > 0)
    {
        lan = 2;
    }
    else if (ViTri[1] > 0)
    {
        lan = 1;
    }
    else
    {
        lan = 0;
    }
    for (i = lan; i >= 0; i--)
    {
        tmp = DocSo3ChuSo(ViTri[i]);
        KetQua += tmp;
        if (ViTri[i] > 0) KetQua += Tien[i];
        if ((i > 0) && (tmp.length > 0)) KetQua += ' ';//&& (!string.IsNullOrEmpty(tmp))
    }
    if (KetQua.substring(KetQua.length - 1) == ' ')
    {
        KetQua = KetQua.substring(0, KetQua.length - 1);
    }
    KetQua = KetQua.substring(1,2).toUpperCase()+ KetQua.substring(2);
    return KetQua + ' đồng';//.substring(0, 1);//.toUpperCase();// + KetQua.substring(1);
}
function PrintElem(elem)
{
    Popup(jQuery(elem).html());
}
function Popup(data)
{
    var mywindow = window.open('height=800,width=1200');
    mywindow.document.write(data);
    mywindow.print();
    mywindow.close();
    return true;
}
function number_format(int){
    if(int > 999 || int < - 999){
        return int.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }else{
        return int;
    }
}
function flash(status = 'success',message = 'Thành công'){
    let color = status ? '#5ba035' : '#bf441d';
    return $.toast({
        heading: "Thông báo!",
        text: message,
        position: "top-right",
        loaderBg: color,
        icon: status,
        hideAfter: 3e3,
        stack: 1
    })
}
function ChangeToSlug()
{
    var title, slug;
    //Lấy text từ thẻ input title
    title = document.getElementById("title").value;
    //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();
    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    //In slug ra textbox có id “slug”
    document.getElementById('alias').value = slug;
    document.getElementById('alias_seo').innerText = slug+ '.html';
}

function uploadPhoto(input){
    if (input.files) {
        let box = input.closest('.box-action-image');
        let showbox = $(box).find('.show-box');
        let filesAmount = input.files.length;
        let classItem = filesAmount == 1 ? "w-100" : "w-50";
        let holder = $(box).find('.image-holder');
        let imgPath = input.value;
        let extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        $(holder).empty();
        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || extn == "jfif") {
            if (typeof (FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(holder);
                        new CBPGridGallery( document.getElementById( 'grid-gallery' ));
                    }
                    holder.show();
                    showbox.show();
                    reader.readAsDataURL(input.files[i]);
                }
            }else{
                alert("This browser does not support FileReader.");
            }
        }else{
            alert("Please select only images");
        }
    }
}

function actionPhoto(input){
    let box = input.closest('.box-action-image');
    let unlink = $(box).find('.unlink-image');
    let checkbox = $(box).find('.checkbox-unlink');
    let image = $(box).find('.image-holder img');
    let watermark = $(box).find('.watermark');
    if($(unlink).is(':checked')){
        $(checkbox).show();
        $(image).css('opacity',1);
    }else{
        $(watermark).prop('checked',false);
        $(checkbox).hide();
        $(image).css('opacity',0.2);
    }
}

$(document).ready(function(){
    $('#fileUpload').on('change',function(){
        uploadPhoto(this);
    })
    $('#fileUploadOgImage').on('change',function(){
        uploadPhoto(this);
    })

    $('#fileUploadMultiple').on('change',function(){
        uploadPhoto(this);
    })

    $('#backgroundUpload').on('change',function(){
        uploadPhoto(this);
    })

    $('#faviconUpload').on('change',function(){
        uploadPhoto(this);
    })

    $('#watermarkUpload').on('change',function(){
        uploadPhoto(this);
    })

    $('.checkbox-unlink-logo label').click(function(){
        actionPhoto(this);
    })
    $('.checkbox-unlink-og label').click(function(){
        actionPhoto(this);
    })
    $('.checkbox-unlink-background label').click(function(){
        actionPhoto(this);
    })
    $('.checkbox-unlink-favicon label').click(function(){
        actionPhoto(this);
    })

    $('.checkbox-unlink-watermark label').click(function(){
        actionPhoto(this);
    })
    $('.checkbox-unlink-image label').click(function(){
        actionPhoto(this);
    })

    $('button[name="delall"]').attr('disabled',true);

    $('input[name="checkAll"]').click(function(){
        if($(this).is(':checked')){
            $('.check_del').prop('checked',true);
            $('button[name="delall"]').attr('disabled',false);
        }else{
            $('.check_del').prop('checked',false);
            $('button[name="delall"]').attr('disabled',true);
        }
    })

    $('.check_del').click(function() {
        $('.check_del').each(function() {
            /* Act on the event */
            if($('.check_del').is(':checked')){
                $('button[name="delall"]').attr('disabled',false);
            }else{
                $('button[name="delall"]').attr('disabled',true);
            }
        });
    });

    // $('.checkbox-unlink-image label').click(function(){
    //     if($('.unlink-image').is(':checked')){
    //         $('.checkbox-unlink-watermark').show();
    //         $('.image-holder img').css('opacity','1');
    //
    //     }else{
    //         $('.checkbox-unlink-watermark').hide();
    //         $('.watermark').prop('checked',false);
    //         $('.image-holder img').css('opacity','0.2');
    //     }
    // })
    //
    // $('.checkbox-unlink-logo label').click(function(){
    //     if($('.unlink-logo').is(':checked')){
    //         $('.logo-holder img').css('opacity','1');
    //     }else{
    //         $('.logo-holder img').css('opacity','0.2');
    //     }
    // })
    //
    // $('.checkbox-unlink-favicon label').click(function(){
    //     if($('.unlink-favicon').is(':checked')){
    //         $('.favicon-holder img').css('opacity','1');
    //     }else{
    //         $('.favicon-holder img').css('opacity','0.2');
    //     }
    // })
    //
    // $('.checkbox-unlink-watermark label').click(function(){
    //     if($('.unlink-watermark').is(':checked')){
    //         $('.watermark-holder img').css('opacity','1');
    //     }else{
    //         $('.watermark-holder img').css('opacity','0.2');
    //     }
    // })
    //
    // $('.checkbox-unlink-background label').click(function(){
    //     if($('.unlink-background').is(':checked')){
    //         $('.background-holder img').css('opacity','1');
    //     }else{
    //         $('.background-holder img').css('opacity','0.2');
    //     }
    // })
    //
    // $('.checkbox-unlink-image label').click(function(){
    //     let holder = $(this).closest('.box-action-image').find('.image-holder img');
    //     let unlink = $('.unlink-image');
    //     if(unlink.is(':checked')){
    //         $('.checkbox-unlink-watermark').show();
    //         holder.css('opacity','1');
    //     }else{
    //         $('.checkbox-unlink-watermark').hide();
    //         $('.watermark').prop('checked',false);
    //         holder.css('opacity','0.2');
    //     }
    // })

    // $('body #fileUpload').each(function(){
    //     $(this).on('change', function () {
    //
    //         //Get count of selected files
    //         var countFiles = $(this)[0].files.length;
    //         var imgPath = $(this)[0].value;
    //         var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //
    //         var box = $(this).parent();
    //         console.log(box);
    //
    //         box.find('.unlink-image').prop('checked',false);
    //
    //         var image_holder = box.find('.image-holder');
    //
    //         image_holder.empty();
    //         var classItem = countFiles == 1 ? "w-100" : "w-50";
    //         if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
    //             if (typeof (FileReader) != "undefined") {
    //                 //loop for each file selected for uploaded.
    //                 for (var i = 0; i < countFiles; i++) {
    //                     var reader = new FileReader();
    //
    //                     reader.onload = function (e) {
    //                         $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(image_holder);
    //                     };
    //                     $('.show-box').show();
    //                     $('.show-one-box').show();
    //                     image_holder.show();
    //                     reader.readAsDataURL($(this)[0].files[i]);
    //                 }
    //             }else{
    //                 alert("This browser does not support FileReader.");
    //             }
    //         }else{
    //             alert("Please select only images");
    //         }
    //     });
    // })
    //
    // $("#backgroundUpload").on('change', function () {
    //     $('.unlink-background').prop('checked',false);
    //     //Get count of selected files
    //     var countFiles = $(this)[0].files.length;
    //     var imgPath = $(this)[0].value;
    //     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //     var image_holder = $("#background-holder");
    //     image_holder.empty();
    //     var classItem = countFiles == 1 ? "w-100" : "w-50";
    //     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
    //         if (typeof (FileReader) != "undefined") {
    //             //loop for each file selected for uploaded.
    //             for (var i = 0; i < countFiles; i++) {
    //                 var reader = new FileReader();
    //
    //                 reader.onload = function (e) {
    //                     $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(image_holder);
    //                     new CBPGridGallery( document.getElementById( 'grid-gallery' ));
    //                 }
    //                 $('.show-box-bg').show();
    //                 $('.show-one-box').show();
    //                 image_holder.show();
    //                 reader.readAsDataURL($(this)[0].files[i]);
    //             }
    //         }else{
    //             alert("This browser does not support FileReader.");
    //         }
    //     }else{
    //         alert("Please select only images");
    //     }
    // });
    //
    // $("#logoUpload").on('change', function () {
    //     $('.unlink-logo').prop('checked',false);
    //     //Get count of selected files
    //     var countFiles = $(this)[0].files.length;
    //     var imgPath = $(this)[0].value;
    //     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //     var image_holder = $("#logo-holder");
    //     image_holder.empty();
    //     var classItem = countFiles == 1 ? "w-100" : "w-50";
    //     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
    //         if (typeof (FileReader) != "undefined") {
    //             //loop for each file selected for uploaded.
    //             for (var i = 0; i < countFiles; i++) {
    //                 var reader = new FileReader();
    //
    //                 reader.onload = function (e) {
    //                     $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(image_holder);
    //                     new CBPGridGallery( document.getElementById( 'grid-gallery' ));
    //                 }
    //                 $('.show-box-logo').show();
    //                 $('.show-one-box').show();
    //                 image_holder.show();
    //                 reader.readAsDataURL($(this)[0].files[i]);
    //             }
    //         }else{
    //             alert("This browser does not support FileReader.");
    //         }
    //     }else{
    //         alert("Please select only images");
    //     }
    // });
    //
    // $("#faviconUpload").on('change', function () {
    //     $('.unlink-favicon').prop('checked',false);
    //     //Get count of selected files
    //     var countFiles = $(this)[0].files.length;
    //     var imgPath = $(this)[0].value;
    //     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //     var image_holder = $("#favicon-holder");
    //     image_holder.empty();
    //     var classItem = countFiles == 1 ? "w-100" : "w-50";
    //     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
    //         if (typeof (FileReader) != "undefined") {
    //             //loop for each file selected for uploaded.
    //             for (var i = 0; i < countFiles; i++) {
    //                 var reader = new FileReader();
    //
    //                 reader.onload = function (e) {
    //                     $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(image_holder);
    //                     new CBPGridGallery( document.getElementById( 'grid-gallery' ));
    //                 };
    //                 $('.show-box-favicon').show();
    //                 $('.show-one-box').show();
    //                 image_holder.show();
    //                 reader.readAsDataURL($(this)[0].files[i]);
    //             }
    //         }else{
    //             alert("This browser does not support FileReader.");
    //         }
    //     }else{
    //         alert("Please select only images");
    //     }
    // });
    //
    // $("#watermarkUpload").on('change', function () {
    //     $('.unlink-background').prop('checked',false);
    //     //Get count of selected files
    //     var countFiles = $(this)[0].files.length;
    //     var imgPath = $(this)[0].value;
    //     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //     var image_holder = $("#watermark-holder");
    //     image_holder.empty();
    //     var classItem = countFiles == 1 ? "w-100" : "w-50";
    //     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
    //         if (typeof (FileReader) != "undefined") {
    //             //loop for each file selected for uploaded.
    //             for (var i = 0; i < countFiles; i++) {
    //                 var reader = new FileReader();
    //
    //                 reader.onload = function (e) {
    //                     $('<li id="item" class="'+classItem+' text-center"><img src="'+e.target.result+'" class="rounded p-1"/></li>').appendTo(image_holder);
    //                     new CBPGridGallery( document.getElementById( 'grid-gallery' ));
    //                 }
    //                 $('.show-box-watermark').show();
    //                 $('.show-one-box').show();
    //                 image_holder.show();
    //                 reader.readAsDataURL($(this)[0].files[i]);
    //             }
    //         }else{
    //             alert("This browser does not support FileReader.");
    //         }
    //     }else{
    //         alert("Please select only images");
    //     }
    // });
    //


})
