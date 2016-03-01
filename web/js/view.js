$(document).ready(function() {

    $(".create-comment-content").first().attr("placeholder", "Write a comment");
    default_action = $("#create-comment-form").attr("action").split("parent_id=0")[0]

    $(document).on('click', '.answer', function () {
        $("#create-comment-form").attr("action", default_action + 'parent_id=' + $(this).attr("comment-id"))
        $(".create-comment-content").first().attr("placeholder", "Write answer for #" + $(this).attr("id-in-post") + " comment")
        jQuery.scrollTo($('.create-comment-content').first(), 500);
    });

    $(document).on("click", ".default-answer", function () {
        $("#create-comment-form").attr("action", default_action + "parent_id=0")
        $(".create-comment-content").first().attr("placeholder", "Write a comment")
        jQuery.scrollTo($('.create-comment-content').first(), 500);
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

    // AJAX Update action
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
                else
                {
                    alert("Comment that you wish to update has been deleted.");
                    reloadComments();
                }
            }
        });

        return false;
    });

    // AJAX Delete action
    $(document).on("click", ".delete-button", function() {
        if(confirm("Are you sure you want to delete this item ?"))
        {
            $.ajax({
                url: $(this).attr('link'),
                type: 'post',
                success: function(data) {
                    if(data['success'] == false)
                    {
                        alert("This comment was already deleted.");
                    }
                    reloadComments();
                }
            });
        }
        return false;
    });

    // AJAX Create action
    $(document).on('beforeSubmit', '#create-comment-form', function() {
        form = $(this);
        if(form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {
                if(data['success'] == false)
                {
                    alert("Comment that you wish to answer has been deleted.");
                }

                reloadComments();
                $("#create-comment-form").attr("action", default_action + "parent_id=0")
                $(".create-comment-content").first().attr("placeholder", "Write a comment")

                if(data['success'] == true)
                {
                    $("#create-comment-form textarea").val("");
                    setTimeout(function(){
                        jQuery.scrollTo($("[id-in-post='" + data['comment-id-in-post'] + "']").first().offset().top-70, 500);
                        $("[id-in-post='" + data['comment-id-in-post'] + "']").first().fadeIn(400).fadeOut(400).fadeIn(400);
                    }, 1000);
                }

            }
        });

        return false;
    });

    // AJAX Index action
    function reloadComments()
    {
        $.ajax({
            url: "/comment/index/" + $(".post-view").attr("post-id"),
            type: 'post',
            success: function (data) {
                $(".comments").html(data['comments']);
            }
        });
    }
});