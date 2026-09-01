/**
 * データの取得
 */
$('.edit-btn').on('click',function(){

    //種別
    var datatype = $(this).data("type");

    //枠フォーカス背景色
    $(this).parents("td, th").addClass("bg-focus");

    //登録データの取得
    _ajax_get(datatype);

    //種別をフォームにセット
    $(".modal #datatype").val(datatype);
});
//Ajax
function _ajax_get(datatype) {
    var query = "datatype=" + datatype;
    $.ajax({
        url: get_path,
        dataType: 'json',
        data: query,
        cache: false,
        success : function(json){

            if (json.result == 1) {

                if (json.type == "info") {

                    //内容の代入
                    // $("#infoModal #comment").val(json.comment);//↓
                    CKEDITOR.instances.comment.setData(json.comment);//CKEDITOR

                    //モーダルオープン
                    $('#infoModal').modal('show');
                } else if (json.type == "news") {

                    //内容の代入
                    CKEDITOR.instances.comment.setData(json.comment);//CKEDITOR

                    //モーダルオープン
                    $('#infoModal').modal('show');

                } else {

                    //内容の代入
                    $("#editModal #comment").val(json.comment);

                    //モーダルオープン
                    $('#editModal').modal('show');
                }

            } else {
                alert('処理できませんでした');
            }
        }
    });
}

/**
 * データの更新
 */
$("#infoModal .submit-comment").on('click', function () {
    // var comment = encodeURIComponent($("#infoModal #comment").val());//↓
    var comment = encodeURIComponent(CKEDITOR.instances.comment.getData());//CKEditor
    var datatype = encodeURIComponent($("#infoModal #datatype").val());
    if (datatype) {
        _ajax_edit($(this).attr('href'), comment, datatype);
    } else {
        alert('更新できませんでした');
    }
    return false;
});
$("#editModal .submit-comment").on('click', function () {
    var comment = encodeURIComponent($("#editModal #comment").val());
    var datatype = encodeURIComponent($("#editModal #datatype").val());
    if (datatype) {
        _ajax_edit($(this).attr('href'), comment, datatype);
    } else {
        alert('更新できませんでした');
    }
    return false;
});
//Ajax
function _ajax_edit(path, comment, datatype) {
    var query = "datatype=" + datatype + "&comment=" + comment;

    $.ajax({
        url: path,
        dataType: 'json',
        data: query,
        cache: false,
        success : function(json){

            if (json.result == 1) {

                if (json.type == 'info') {
                    if (json.comment) {
                        $("#info").html(json.comment);
                    } else {
                        $("#info").html('<span class="text-muted">案内情報はありません</span>');
                    }
                } else if (json.type == 'news') {
                    if (json.comment) {
                        $("#news").html(json.comment);
                    } else {
                        $("#news").html('<span class="text-muted">月のお知らせはありません</span>');
                    }
                } else if (json.type == 'schedule') {
                    $("#schedule"+"_"+json.date).html(json.comment);
                }

            } else {
                alert('更新できませんでした');
            }

            //モーダルクローズ
            $('#editModal, #infoModal').modal('hide');
        }
    });
}

/**
 *  枠フォーカス背景色の解除
 */
$('#editModal').on('hidden.bs.modal', function (e) {
    $(".table-schedule td, .table-schedule th").removeClass("bg-focus");
});

/**
 *  編集モード
 */
if (is_login) {
    $(".edit-btn").css("display", "inline-block");
}