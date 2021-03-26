

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

var box = $('.change-seo');

function changeSeo(){
    $('.change-seo').slideToggle();
}
$(document).ready(function(){
    $('.change-seo').hide();

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
})
