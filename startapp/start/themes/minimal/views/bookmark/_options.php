<div class="optionsTitle"><?php echo CHtml::encode($model->title);?></div>
<div class="optionsButtons">
    <div class="optionsEditImage">Edit</div>
    <div class="optionsDelete">Delete</div>
<div class="message">for info</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
    $(".optionsDelete").click(function(){
        
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/delete&id="+gotID+""; 
        // to prevent error message when mousecursor fires event to load 
        // getBookmarkTitle again
        $('#'+gotID+'').unbind();
        $('.message').load(loadUrl,{'id':gotID},function(response, status, xhr) {
            if (status == "error") {
                var msg = "error deleting: ";
                $(this).html(msg + xhr.status + " ");
                }
            if (status == "success") {
                $(this).closest('.tooltip').hide();
                $('#'+gotID+'').prev().slideUp(500,function(){
                    $(this).next().remove();
                    $(this).remove();
                });
            }
        });
    });
    
    $(".optionsEditImage").click(function(){
        $("#dialogEditImage").dialog('open');
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/GetBigImage&id="+gotID+"";
        $("#dialogEditImage").append('<img id="bigImage"/>');
        $("#bigImage").attr('src',loadUrl);
        $("#bigImage").axzoomer({
            'maxZoom':4,
            'sensivity':10
        });
        
    });
});

</script>

