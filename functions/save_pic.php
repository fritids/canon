<?php
/*
 *  保存/取消保存一张图片
 *
 *  @param {int} imageId 图像id（即post_id）
 *  @param {int} userId 用户id
 *  @param {string} optional action 若设置为 unsave 则执行取消保存操作
 *  @return {json}
 */
header('Content-Type: application/json');
require('common.php');

//首先验证ajax请求的有效性
if(verify_ajax(array("imageId"),
               'post',
               true,
               'user_pic_action'))
{

    $image_id = $_POST['imageId'];
    $user_id = get_current_user_id();
    $author_id = get_post($image_id)->post_author;
    if ($user_id == $author_id) {
        $edit_btn = "<div class='editButton actionButton blueButton'>".
                        "<a href='/edit?pid={$image_id}'>编辑信息</a>".
                    "</div>";
    }
    else{
        $edit_btn = "";
    }

    $URL = URL;

    $html = <<<html
<div id="groupsOverlay" data-imageId="{$image_id}">
    <div class="wrap">
        <div class="arrow">
            <span class="up"></span>
            <span class="up-wrapper"></span>
        </div>
        <h3><img src="{$URL}/img/check-circle.png" width="19" height="19">保存成功</h3>
        <div class="topOptions">
            {$edit_btn}
            <div class="closeButton actionButton"><span>完成</span></div>
        </div>
    </div>
    <div class="options">
        <div class="unsaveButton">取消保存</div>
    </div>
</div>
html;
    //首先检查记录是否存在
    global $wpdb;
    $save_row = $wpdb->get_row(
            $wpdb->prepare('SELECT save_id FROM pic_save
                            WHERE pic_id = %d
                            AND user_id = %d',
                            $image_id, $user_id)
    );

    //若保存记录存在
    if ($save_row ) {
        //若执行取消保存的操作，则删除 pic_save 表中的记录
        if ($_POST["action"] == "unsave") {
            $deleted = $wpdb->delete('pic_save',
                                     array("save_id" => $save_row->save_id));
            if ($deleted) {
                $save_count = get_post_meta($image_id, 'save_count', true);
                update_post_meta($image_id, 'save_count', --$save_count);

                send_result(false, "取消保存成功");
            }
            else{
                send_result(false, "取消保存失败");
            }
        }
        else{
            send_result(false, "图片已保存", array("html" => $html,
                                                  "imageId" => $image_id));
        }
    }
    //若保存记录不存在，插入新纪录
    else{
        $inserted = $wpdb->insert('pic_save',
                                array("pic_id" => $image_id,
                                      "user_id" => $user_id),
                                array("%d", "%d"));
        //插入成功
        if ($inserted !== false) {

            $save_count = get_post_meta($image_id, 'save_count', true);
            update_post_meta($image_id, 'save_count', ++$save_count);

            send_result(false, "保存成功", array("html" => $html,
                                                "imageId" => $image_id));
        }
        else{
            send_result(true, "保存失败");
        }
    }
}

?>