// Fungsi Render Jam
function FGenerateJam(countinterval=1000){
    let maincontent = document.getElementById('main-content'),
        timer       = document.getElementById('timer'),
        tmstmps     = maincontent.dataset.unixtimestamp,
        localTime   = +Date.now(),
        timeDiff    = tmstmps - localTime;

    return setInterval(function(){
        let time        = new Date(+Date.now() + timeDiff),
            day         = ['minggu','senin','selasa','rabu','kamis','jum\'at','sabtu'],
            month       = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'],
            dy          = time.getDay(),
            d           = time.getDate(),
            m           = time.getMonth(),
            y           = time.getFullYear(),
            h           = time.getHours(),
            min         = time.getMinutes(),
            s           = time.getSeconds(),
            hh          = (h < 10 ? '0'+h : h),
            mm          = (min < 10 ? '0'+min : min),
            ss          = (s < 10 ? '0'+s : s),
            now         = day[dy] +', '+ d +' '+ month[m] +' '+ y +' | '+ hh +':'+ mm +':'+ ss;
        timer.innerHTML = now+' WIB';
    }, countinterval);
}

// Fungsi Bloodhound
function FSetBloodhound(url,value,token){
    return new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace(value),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: url,
            wildcard: '%QUERY',
            prepare: function (query, settings) {
                settings.url = settings.url + '/' + query
                settings.headers = {
                    "Authorization": "Bearer " + token
                };

                return settings;
            },
            cache: false
        },
    });
}

// Fungsi Bloodhound Local
function FSetBloodhoundLocal(source){
    return new Bloodhound({
        initialize: false,
        local : source,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('info'),
        identify: function(obj){
            return obj.text;
        }
    });
}

// Fungsi Bloodhound Search
function FSetBloodhoundSearch(source){
    return new Bloodhound({
        local : source,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('info','text'),
    });
}

// Fungsi RegexSource Typeahead
function FSetRegex(strings){
    return function findMatches(q, cb) {
        let matches, substringRegex,
            substrRegex = new RegExp(q, 'i');

        matches = [];
        $.each(strings, function(i, str) {
            if (substrRegex.test(str.info)) {
                matches.push(str);
            }
        });
        cb(matches);
    };
}

// Fungsi Render DataTables
function FGenerateDataTable(url,token,columns=[]){
    return {
        pageLength: 10,
        processing: true,
        "ajax": {
            "url": url,
            "dataType": 'json',
            "type": "GET",
            "beforeSend": function(xhr){
                xhr.setRequestHeader("Authorization","Bearer " + token);
            }
        },
        "columns": columns,
        "oLanguage":{
            "sSearch":"Cari ",
            "oPaginate": {
                "sPrevious":"Sebelumnnya",
                "sNext":"Selanjutnya",
            },
            "sEmptyTable":"Belum ada Data yang tersedia",
            "sLengthMenu": "Tampilkan  _MENU_  baris",
            "sInfo":"Menampilkan _START_ s/d _END_ dari _TOTAL_ entri",
            "sInfoEmpty": "Menampilkan 0 s/d 0 dari 0 entri",
            "infoFiltered": "",
            "loadingRecords": "&nbsp;",
            "processing": '<div class="spinner"></div>'
        },
        "order":false,
        "bLengthChange" : false,
        language: {
            buttons: {
                colvis: '<i class="ti-view-grid"></i>'
            },
        }
    }
}

// Fungsi untuk ambil JSON dari URL
function FGetJSONFromURL(url='',token='',method='GET',async=false,sender=''){
    let getreturn = Object;
    if(url.length > 0){
        let req;
        if(window.XMLHttpRequest){
            req = new XMLHttpRequest();
        }else{
            req = new ActiveXObject('Microsoft.XMLHTTP');
        }
        req.open(method, url, async);
        if(method === 'POST'){
            req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        if(token.length > 0){
            req.setRequestHeader('Authorization','Bearer ' + token);
        }

        if(sender === ''){
            req.send();
        }else{
            req.send(sender);
        }
        getreturn = req.response;
    }

    return JSON.parse(getreturn);
}

// Fungsi Deteksi Inputan Angka
function FGenerateInputanAngka(event){
    let getKeyCode = (event.which) ? event.which : event.keyCode;
    if(getKeyCode > 31 && (getKeyCode < 48 || getKeyCode > 57) ){
        return false;
    }else{
        if(getKeyCode === 13){
            return false;
        }else{
            return true;
        }
    }
}

// Fungsi Deteksi Inputan Huruf
function FGenerateInputanHuruf(event){
    let getKeyCode = (event.which) ? event.which : event.keyCode;
    if(getKeyCode > 47 && getKeyCode < 58){
        event.preventDefault();
    }
}

// Fungsi Inputan Rupiah
function FGenerateRupiah(angka, prefix){
    let number_string   = angka.toString().replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix === undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
}

// Fungsi Inputan Rupiah ke Angka
function FGenerateAngka(angka){
    return parseInt(angka.toString().replace(/,.*|[^0-9]/g, ''), 10);
}

// Fungsi Input Focust ke Element Berikutnya
function FFocusToNextElement() {
    var focussableElements = 'button.btn-primary, input[type=email]:not([disabled]), input[type=text]:not([disabled]), select:not([disabled]), [tabindex]:not([disabled]):not([tabindex="-1"])';
    if (document.activeElement && document.activeElement.form) {
        var focussable = Array.prototype.filter.call(document.activeElement.form.querySelectorAll(focussableElements),
            function(element) {
                return element.offsetWidth > 0 || element.offsetHeight > 0 || element === document.activeElement
            });
        var index = focussable.indexOf(document.activeElement);
        focussable[index + 1].focus();
    }
}

// Fungsi Transform Text
(function($) {
    $.fn.extend({
        upperFirstAll: function() {
            $(this).keyup(function(event) {
                var box         = event.target;
                var txt         = $(this).val();
                var start       = box.selectionStart;
                var end         = box.selectionEnd;
                $(this).val(txt.toLowerCase().replace(/^(.)|(\s|\-)(.)/g,
                    function(c) {
                        return c.toUpperCase();
                    }));
                box.setSelectionRange(start, end);
            });
            return this;
        },

        upperFirst: function() {
            $(this).keyup(function(event) {
                var box = event.target;
                var txt = $(this).val();
                var start = box.selectionStart;
                var end = box.selectionEnd;

                $(this).val(txt.toLowerCase().replace(/^(.)/g,
                    function(c) {
                        return c.toUpperCase();
                    }));
                box.setSelectionRange(start, end);
            });
            return this;
        },

        lowerCase: function() {
            $(this).keyup(function(event) {
                var box = event.target;
                var txt = $(this).val();
                var start = box.selectionStart;
                var end = box.selectionEnd;

                $(this).val(txt.toLowerCase());
                box.setSelectionRange(start, end);
            });
            return this;
        },

        upperCase: function() {
            $(this).keyup(function(event) {
                var box = event.target;
                var txt = $(this).val();
                var start = box.selectionStart;
                var end = box.selectionEnd;

                $(this).val(txt.toUpperCase());
                box.setSelectionRange(start, end);
            });
            return this;
        }

    });
})(jQuery);
