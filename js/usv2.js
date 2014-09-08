$('#usv2FormID') .ready(function () {
    var echo = function (msg) {
        var c = $('#usv2settingsEcho');
        if (!c.find('ol') .length) {
            c.append('<ol></ol>');
        }
        $('<li>' + msg + '</li>') .appendTo('#usv2settingsEcho ol');
    };
    $('#usv2tabs') .tabs();
    var appName = 'user_servervars2';
    
    // $("#usv2FormID :input[type=button]").click(function (e) {
    //     var s = e.target;
    //     var key = s.name;
    //     var targetName = key.substr(5);
    //     var target = $("#usv2tabs :input[name='" + targetName + "']");
    //     // echo('value=' + target.val());
        
    //     var url = OC.generateUrl('apps/user_servervars2/api/settings/conf/');
    //     var data = { 'file': target.val() };
    //     $.post( url, data, function(result){
    //         var pre = $("#pre_"+targetName);
    //         pre.html(result);
    //     }).fail(function() {
    //         echo("Failure");
    //     });

    // }); 
    $('#usv2FormID :input') .change(function (e) {
        $('#usv2settingsError') .empty();
        var s = e.target;
        var jqTarget = $('#usv2tabs :input[name=\'' + s.name + '\']') .parent();
        // introduire type
        var fn = function (key, value, okFn) {
            jqTarget.removeClass('usv2changed usv2error usv2saved');
            jqTarget.addClass('usv2changed');
            var url = OC.generateUrl('apps/user_servervars2/api/settings/');
            $.post(url, {
                app: appName,
                key: key,
                value: value
            }, function (result) {
                echo('OK:'+key+"="+value);
                jqTarget.removeClass('usv2changed usv2error usv2saved');
                jqTarget.addClass('usv2saved');
                jqTarget.removeClass('usv2saved', 2000);
                if ( result.conf != undefined) {
                     var pre = $("#show_"+s.name);
                    pre.html(result.conf);
                }
            }, 'json') .fail(function (jqXHR, textStatus, errorThrown) {
                echo('KO:'+key+"="+value);
                jqTarget.removeClass('usv2changed usv2error usv2saved');
                jqTarget.find('')
                jqTarget.addClass('usv2error');
                $('#usv2settingsError') .text(jqXHR.responseJSON.msg);
            });
        }
        switch (s.type) {
        case 'text':
            fn(s.name, s.value);
            break;
        case 'checkbox':
            fn(s.name, s.checked);
            break;
        default:
            console.error('unhandled event ' + s);
        }
    });
});