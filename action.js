
var Vers = (function() {
    var that = {
        "db" : {
            "first_loaded"     : false,
            "second_loaded"    : false
        }
    };
    that.init = function() {
        $(window).resize(Vers.rescale);
        Vers.rescale();
    };
    that.rescale = function() {
        var winHeight = $(window).height() - $("#menu-header").height();
        $("#firstTableResult").height(winHeight);
        $("#secondTableResult").height(winHeight);
    };
    that.connectFirst = function() {
        that.load($("#first_host").val(), $("#first_user").val(), $("#first_password").val(), $("#first_database").val(), $("#first_port").val(), function(json) {
            $("#firstTableResult").find(".result-content").html(json.html);
            that.db.first_loaded = true;
            setTimeout(Vers.juxtaposition, 1000);
        });
    };
    that.connectSecond = function() {
        that.load($("#second_host").val(), $("#second_user").val(), $("#second_password").val(), $("#second_database").val(), $("#second_port").val(), function(json) {
            $("#secondTableResult").find(".result-content").html(json.html);
            that.db.second_loaded = true;
            setTimeout(Vers.juxtaposition, 1000);
        });
    };
    that.load = function(host, user, password, database, port, callback) {
        $.post("/action.php", { "host" : host, "user" : user, "password" : password, "database" : database, "port" : port }, function(response) {
            var json = JSON.parse(response);
            callback(json);
        });
    };
    that.toggleBody = function(element) {
        $(element).parent().find(".panel-body").toggle();
    };
    that.toggleAttribute = function(element) {
        $(element).parent().find("#attributeTable").toggle();
    };
    that.toggleIndex = function(element) {
        $(element).parent().find("#indexTable").toggle();
    };
    that.juxtaposition = function() {

        if (!that.db.first_loaded || !that.db.second_loaded) {
            return;
        }

        $("#firstTableResult").find(".item").each(function() {
            if (!$(this).hasClass("success")) {
                var checksum = $(this).data("checksum");
                var search = $("#secondTableResult").find("[data-checksum="+checksum+"]");
                if(search.length) {
                    $(this).addClass("success");
                    search.addClass("success");
                } else {
                    $(this).addClass("danger");
                }
            }
        });

        $("#secondTableResult").find(".item").each(function() {
            if (!$(this).hasClass("success")) {
                var checksum = $(this).data("checksum");
                var search = $("#firstTableResult").find("[data-checksum="+checksum+"]");
                if(search.length) {
                    $(this).addClass("success");
                    search.addClass("success");
                } else {
                    $(this).addClass("danger");
                }
            }
        });

        $(".item-table").each(function() {
            var danger_list = $(this).parent().find('.danger');
            $(this).find(".error-count").html("("+danger_list.length+")");
        });
    };
    return that;
})();
