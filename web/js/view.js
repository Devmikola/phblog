/**
 * Created by Psy on 25.02.2016.
 */
$(document).ready(function() {

    $("#comment-content").attr("placeholder", "Write a comment");
    default_action = $("#create-comment-form").attr("action").split("parent_id=0")[0]

    $(document).on('click', '.answer', function () {
        $("#create-comment-form").attr("action", default_action + 'parent_id=' + $(this).attr("comment-id"))
        $("#create-comment-content").attr("placeholder", "Write answer for #" + $(this).attr("id_in_post") + " comment")
        jQuery.scrollTo('#create-comment-content', 500);
    });

    $(".default-answer").click(function () {
        $("#create-comment-form").attr("action", default_action + "parent_id=0")
        $("#create-comment-content").attr("placeholder", "Write a comment")
        jQuery.scrollTo('#create-comment-content', 500);
    });

    $(document).on('click', '.update-button', function() {

        parent = $(this).parents().eq(1)
        parent.find("form").removeClass('display-none')
        parent.find(".comment-content").addClass('display-none')
        parent.find(".update-button").addClass('display-none')
        parent.find(".discard-button").removeClass('display-none')

    });

    $(document).on('click', '.discard-button', function() {

        parent = $(this).parents().eq(1)
        parent.find("form").addClass('display-none')
        parent.find(".comment-content").removeClass('display-none')
        parent.find(".update-button").removeClass('display-none')
        parent.find(".discard-button").addClass('display-none')

    });

    $(document).on('beforeSubmit', '.update-comment-form', function() {
        form = $(this);
        if(form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {
                if(data['success'] == true)
                {
                    parent = form.parents().eq(2)
                    parent.find("form").addClass('display-none')
                    parent.find(".comment-content").html(data['comment-content'])
                    parent.find(".comment-content").removeClass('display-none')
                    if(parent.find(".updated-at").length)
                    {
                        parent.find(".updated-at").html(data['updated-at']);
                    }
                    else
                    {
                        parent.find(".created-updated-at").append("<br/>Updated at <strong class='updated-at'>" + data['updated-at'] + "</strong>");
                    }

                    parent.find(".update-button").removeClass('display-none')
                    parent.find(".discard-button").addClass('display-none')

                }
            }
        });

        return false;
    });
});