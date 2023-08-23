$(document).ready(function(){

    // GLOBAL VARIABLE
    var Bs = window.location.origin+'/';
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var datadashboardmbccare = window.location.href;
    var datadashboardm = datadashboardmbccare.split("/");
    var lock = $("#auth").val();
    var sweet_loader = '<div class="sweet_loader"><img src="../../assets/img/favicon.png" alt="red" width="100" height="100"></div>';
    if(datadashboardm[3] == "auth"){
        $.ajax({
            url      : Bs+"generateQrcodeLogin",
            method   : "GET",
            dataType : "json", 
            data:{ _token:CSRF_TOKEN,lock:lock },
            beforeSend:function(){
                // Swal.showLoading();
                Swal.fire({
                    html: '<h3>Please Wait ...</h3>',
                    showConfirmButton: false,
                    onRender: function() {
                         // there will only ever be one sweet alert open.
                         $('.swal2-content').prepend(sweet_loader);
                    }
                });
            },
            success : function(data){
                Swal.close();
                if(data.results.code == 200){
                    $("#cr").val(data.results.qrcode);
                    var qrcode = new QRCode("showQr", {
                        text: data.results.qrcode,
                        width: 128,
                        height: 128,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                    is_loginx = setInterval(function() {
                      is_login();
                   },3000);
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.results.description,
                        icon: "error",
                        timer:3000,
                    });    
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                Swal.fire({
                    title: "Error",
                    text: 'Failed to Generate QRCode, Please Check Your Internet Connection.',
                    icon: "error",
                    timer:3000,
                });
            }
        });
    }

    $('#loginQr').click(function(){
        var lock = $("#auth").val();
        $.ajax({
            url      : Bs+"generateQrcodeLogin",
            method   : "GET",
            dataType : "json",
            data:{ _token:CSRF_TOKEN,lock:lock },
            beforeSend:function(){
                //Swal.showLoading();
                $("#effectQR").addClass('blurCode');
                $("#showQr").empty();
                document.getElementById("loginQr").disabled = true;
            },
            success : function(data){
                //Swal.close();
                document.getElementById("loginQr").disabled = false;
                if(data.results.code == 200){
                    $("#cr").val(data.results.qrcode);
                    var qrcode = new QRCode("showQr", {
                        text: data.results.qrcode,
                        width: 128,
                        height: 128,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.results.description,
                        icon: "error",
                        timer:3000,
                    });    
                }
                setTimeout( function(){ $("#effectQR").removeClass("blurCode"); }, 2000 );
            },
            error: function (jqXHR, textStatus, errorThrown){
                Swal.fire({
                    title: "Error",
                    text: 'Failed to Generate QRCode, Please Check Your Internet Connection.',
                    icon: "error",
                    timer:3000,
                });
            }
        });
    });

    function is_login(){
        $.ajax({
            url      : Bs+"is_login",
            method   : "GET",
            dataType : "json",
            data:{ _token:CSRF_TOKEN },
            success : function(data){
                if(data.results.code == 205){
                    Swal.fire({
                        title: "QRCode Expired",
                        text: "QRCode expired, Scan Again",
                        icon: "warning",
                        timer:5000,
                    });
                    $("#auth").val(data.results.lock);
                    $.ajax({
                        url      : Bs+"generateQrcodeLogin",
                        method   : "GET",
                        dataType : "json",
                        data:{ _token:CSRF_TOKEN,lock:data.results.lock },
                        beforeSend:function(){
                            $("#showQr").html("");
                        },
                        success : function(data){
                            if(data.results.code == 200){
                                $("#cr").val(data.results.qrcode);
                                var qrcode = new QRCode("showQr", {
                                    text: data.results.qrcode,
                                    width: 128,
                                    height: 128,
                                    colorDark : "#000000",
                                    colorLight : "#ffffff",
                                    correctLevel : QRCode.CorrectLevel.H
                                });
                            }else{
                                Swal.fire({
                                    title: "Error",
                                    text: data.results.description,
                                    icon: "error",
                                    timer:3000,
                                });    
                            }
                        },
                    });
                }else if(data.results.code == 400){
                    console.log(data.results.description);
                }else{
                    window.location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log('check your connection internet');
                // Swal.fire({
                //     title: "Error",
                //     text: 'Failed to Generate QRCode, Please Check Your Internet Connection.',
                //     icon: "error",
                //     timer:3000,
                // });
            }
        });
    }

});

// GLOBAL VARIABLE
var Bs = window.location.origin+'/';
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

function validateEmail(e) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(e);
}