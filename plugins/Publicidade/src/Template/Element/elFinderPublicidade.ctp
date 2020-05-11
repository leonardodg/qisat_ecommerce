<!-- elFinder CSS (REQUIRED) -->
<?= $this->Html->css('elfinder.min.css') ?>
<?= $this->Html->css('theme.css') ?>
<!-- elFinder JS (REQUIRED) -->
<?= $this->Html->script('elfinder.min.js') ?>

<!-- elFinder initialization (REQUIRED) -->
<script type="text/javascript" charset="utf-8">
    // Documentation for client options:
    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
    $(document).ready(function() {
        $('#elfinder').elfinder({
            url : '<?= $this->request->here ?>'  // connector URL (REQUIRED)
            // , lang: 'ru'                    // language (OPTIONAL)
            ,handlers : {
                select : function(event, elfinderInstance) {
                    if(event.data.selected.length > 0){
                        var dir = decode64(event.data.selected[0].substr(3));
                        var pos = dir.lastIndexOf("\\");
                        var arquivo = dir.substr(pos+1).replace(/[^A-Za-z0-9._-]/g, '');
                        var re = new RegExp("^([a-zA-Z0-9._-]{1,}[.]{1}[a-zA-Z0-9]{2,})$");
                        if(re.test(arquivo)){
                            dir = dir.substr(0, pos).replace('\\', '/');
                            $("input[name='src']").val("campanhas/e-convites/"+dir);
                            $("input[name='arquivo']").val(arquivo);
                        }
                    }
                }
            }
            ,rememberLastDir : false
        });
    });

    var keyStr = "ABCDEFGHIJKLMNOP" +
    "QRSTUVWXYZabcdef" +
    "ghijklmnopqrstuv" +
    "wxyz0123456789+/" +
    "=";

    function encode64(input) {
        //input = escape(input);
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        do {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            keyStr.charAt(enc1) +
            keyStr.charAt(enc2) +
            keyStr.charAt(enc3) +
            keyStr.charAt(enc4);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);

        return output;
    }

    function decode64(input) {
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
        var base64test = /[^A-Za-z0-9\+\/\=]/g;
        if (base64test.exec(input)) {
            alert("There were invalid base64 characters in the input text.\n" +
            "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
            "Expect errors in decoding.");
        }
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        do {
            enc1 = keyStr.indexOf(input.charAt(i++));
            enc2 = keyStr.indexOf(input.charAt(i++));
            enc3 = keyStr.indexOf(input.charAt(i++));
            enc4 = keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";

        } while (i < input.length);

        return unescape(output);
    }
</script>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
